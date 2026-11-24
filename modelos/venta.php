<?php
require_once "../config/Conexion.php";

class Venta
{
    public function insertar($idcliente, $idusuario, $tipo_comprobante, $serie_comprobante, $num_comprobante, $fecha_hora, $impuesto, $metodo_pago, $total_venta, $idarticulo, $cantidad, $precio_venta, $descuento)
    {
        global $conexion;
        $conexion->begin_transaction();
        try {
            for ($i = 0; $i < count($idarticulo); $i++) {
                $artId = (int) $idarticulo[$i];
                $cant = (int) $cantidad[$i];
                $fila = ejecutarConsultaSimpleFila("SELECT stock, nombre FROM articulo WHERE idarticulo='$artId'");
                if (!$fila || (int) $fila['stock'] < $cant) {
                    $conexion->rollback();
                    return "Stock insuficiente para " . ($fila['nombre'] ?? 'producto');
                }
            }

            $sql = "INSERT INTO venta (idcliente, idusuario, tipo_comprobante, serie_comprobante, num_comprobante, fecha_hora, impuesto, metodo_pago, total_venta, estado)
                    VALUES ('$idcliente', '$idusuario', '$tipo_comprobante', '$serie_comprobante', '$num_comprobante', '$fecha_hora', '$impuesto', '$metodo_pago', '$total_venta', 'Aceptado')";
            $idventa = ejecutarConsulta_retornarID($sql);

            for ($i = 0; $i < count($idarticulo); $i++) {
                $artId = (int) $idarticulo[$i];
                $cant = (int) $cantidad[$i];
                $pVenta = (float) $precio_venta[$i];
                $desc = (float) $descuento[$i];

                $sqlDetalle = "INSERT INTO detalle_venta (idventa, idarticulo, cantidad, precio_venta, descuento)
                               VALUES ('$idventa', '$artId', '$cant', '$pVenta', '$desc')";
                ejecutarConsulta($sqlDetalle);

                $sqlStock = "UPDATE articulo SET stock = stock - $cant WHERE idarticulo='$artId'";
                ejecutarConsulta($sqlStock);
            }
            $conexion->commit();
            return true;
        } catch (Throwable $e) {
            $conexion->rollback();
            return "Error al registrar la venta.";
        }
    }

    public function anular($idventa)
    {
        global $conexion;
        $conexion->begin_transaction();
        try {
            $cabecera = $this->mostrar($idventa);
            if (!$cabecera || $cabecera['estado'] === 'Anulado') {
                $conexion->rollback();
                return false;
            }
            $detalles = $this->listarDetalle($idventa);
            while ($reg = $detalles->fetch_object()) {
                ejecutarConsulta("UPDATE articulo SET stock = stock + {$reg->cantidad} WHERE idarticulo='{$reg->idarticulo}'");
            }
            ejecutarConsulta("UPDATE venta SET estado='Anulado' WHERE idventa='$idventa'");
            $conexion->commit();
            return true;
        } catch (Throwable $e) {
            $conexion->rollback();
            return false;
        }
    }

    public function mostrar($idventa)
    {
        $sql = "SELECT v.*, c.nombre AS cliente, c.tipo_documento, c.num_documento, c.direccion, u.nombre AS usuario
                FROM venta v
                INNER JOIN persona c ON v.idcliente = c.idpersona
                INNER JOIN usuario u ON v.idusuario = u.idusuario
                WHERE v.idventa='$idventa'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function listarDetalle($idventa)
    {
        $sql = "SELECT d.*, a.nombre AS articulo, a.codigo
                FROM detalle_venta d
                INNER JOIN articulo a ON d.idarticulo = a.idarticulo
                WHERE d.idventa='$idventa'";
        return ejecutarConsulta($sql);
    }

    public function listar()
    {
        $sql = "SELECT v.*, c.nombre AS cliente, u.nombre AS usuario
                FROM venta v
                INNER JOIN persona c ON v.idcliente = c.idpersona
                INNER JOIN usuario u ON v.idusuario = u.idusuario
                ORDER BY v.idventa DESC";
        return ejecutarConsulta($sql);
    }
}
