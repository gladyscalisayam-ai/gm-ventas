<?php
require_once "../config/Conexion.php";

class Ingreso
{
    public function insertar($idproveedor, $idusuario, $tipo_comprobante, $serie_comprobante, $num_comprobante, $fecha_hora, $impuesto, $metodo_pago, $total_compra, $idarticulo, $cantidad, $precio_compra, $precio_venta)
    {
        global $conexion;
        $conexion->begin_transaction();
        try {
            $sql = "INSERT INTO ingreso (idproveedor, idusuario, tipo_comprobante, serie_comprobante, num_comprobante, fecha_hora, impuesto, metodo_pago, total_compra, estado)
                    VALUES ('$idproveedor', '$idusuario', '$tipo_comprobante', '$serie_comprobante', '$num_comprobante', '$fecha_hora', '$impuesto', '$metodo_pago', '$total_compra', 'Aceptado')";
            $idingreso = ejecutarConsulta_retornarID($sql);

            for ($i = 0; $i < count($idarticulo); $i++) {
                $artId = (int) $idarticulo[$i];
                $cant = (int) $cantidad[$i];
                $pCompra = (float) $precio_compra[$i];
                $pVenta = (float) $precio_venta[$i];

                $sqlDetalle = "INSERT INTO detalle_ingreso (idingreso, idarticulo, cantidad, precio_compra, precio_venta)
                               VALUES ('$idingreso', '$artId', '$cant', '$pCompra', '$pVenta')";
                ejecutarConsulta($sqlDetalle);

                $sqlStock = "UPDATE articulo SET stock = stock + $cant, precio_venta = '$pVenta' WHERE idarticulo='$artId'";
                ejecutarConsulta($sqlStock);
            }
            $conexion->commit();
            return true;
        } catch (Throwable $e) {
            $conexion->rollback();
            return false;
        }
    }

    public function anular($idingreso)
    {
        global $conexion;
        $conexion->begin_transaction();
        try {
            $cabecera = $this->mostrar($idingreso);
            if (!$cabecera || $cabecera['estado'] === 'Anulado') {
                $conexion->rollback();
                return false;
            }
            $detalles = $this->listarDetalle($idingreso);
            while ($reg = $detalles->fetch_object()) {
                ejecutarConsulta("UPDATE articulo SET stock = GREATEST(0, stock - {$reg->cantidad}) WHERE idarticulo='{$reg->idarticulo}'");
            }
            ejecutarConsulta("UPDATE ingreso SET estado='Anulado' WHERE idingreso='$idingreso'");
            $conexion->commit();
            return true;
        } catch (Throwable $e) {
            $conexion->rollback();
            return false;
        }
    }

    public function mostrar($idingreso)
    {
        $sql = "SELECT i.*, p.nombre AS proveedor, u.nombre AS usuario
                FROM ingreso i
                INNER JOIN persona p ON i.idproveedor = p.idpersona
                INNER JOIN usuario u ON i.idusuario = u.idusuario
                WHERE i.idingreso='$idingreso'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function listarDetalle($idingreso)
    {
        $sql = "SELECT d.*, a.nombre AS articulo, a.codigo
                FROM detalle_ingreso d
                INNER JOIN articulo a ON d.idarticulo = a.idarticulo
                WHERE d.idingreso='$idingreso'";
        return ejecutarConsulta($sql);
    }

    public function listar()
    {
        $sql = "SELECT i.*, p.nombre AS proveedor, u.nombre AS usuario
                FROM ingreso i
                INNER JOIN persona p ON i.idproveedor = p.idpersona
                INNER JOIN usuario u ON i.idusuario = u.idusuario
                ORDER BY i.idingreso DESC";
        return ejecutarConsulta($sql);
    }
}
