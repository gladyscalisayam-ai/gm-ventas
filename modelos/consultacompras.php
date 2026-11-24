<?php
require_once "../config/Conexion.php";

class ConsultaCompras
{
    public function comprasFecha($fecha_inicio, $fecha_fin, $idproveedor = '', $estado = '', $metodo_pago = '')
    {
        $where = "i.fecha_hora >= '$fecha_inicio' AND i.fecha_hora <= '$fecha_fin'";
        if ($idproveedor !== '') {
            $where .= " AND i.idproveedor='$idproveedor'";
        }
        if ($estado !== '') {
            $where .= " AND i.estado='$estado'";
        }
        if ($metodo_pago !== '') {
            $where .= " AND i.metodo_pago='$metodo_pago'";
        }

        $sql = "SELECT i.idingreso, i.fecha_hora, u.nombre AS usuario, p.nombre AS proveedor,
                       i.tipo_comprobante, i.serie_comprobante, i.num_comprobante,
                       i.metodo_pago, i.total_compra, i.impuesto, i.estado
                FROM ingreso i
                INNER JOIN persona p ON i.idproveedor = p.idpersona
                INNER JOIN usuario u ON i.idusuario = u.idusuario
                WHERE $where
                ORDER BY i.fecha_hora DESC, i.idingreso DESC";
        return ejecutarConsulta($sql);
    }

    public function ventasFechaCliente($fecha_inicio, $fecha_fin, $idcliente = '', $estado = '', $metodo_pago = '')
    {
        $where = "v.fecha_hora >= '$fecha_inicio' AND v.fecha_hora <= '$fecha_fin'";
        if ($idcliente !== '') {
            $where .= " AND v.idcliente='$idcliente'";
        }
        if ($estado !== '') {
            $where .= " AND v.estado='$estado'";
        }
        if ($metodo_pago !== '') {
            $where .= " AND v.metodo_pago='$metodo_pago'";
        }

        $sql = "SELECT v.idventa, v.fecha_hora, u.nombre AS usuario, c.nombre AS cliente,
                       v.tipo_comprobante, v.serie_comprobante, v.num_comprobante,
                       v.metodo_pago, v.total_venta, v.impuesto, v.estado
                FROM venta v
                INNER JOIN persona c ON v.idcliente = c.idpersona
                INNER JOIN usuario u ON v.idusuario = u.idusuario
                WHERE $where
                ORDER BY v.fecha_hora DESC, v.idventa DESC";
        return ejecutarConsulta($sql);
    }

    public function resumenKpis()
    {
        $productos = ejecutarConsultaSimpleFila("SELECT COUNT(*) AS total FROM articulo WHERE condicion=1");
        $categorias = ejecutarConsultaSimpleFila("SELECT COUNT(*) AS total FROM categoria WHERE condicion=1");
        $clientes = ejecutarConsultaSimpleFila("SELECT COUNT(*) AS total FROM persona WHERE tipo_persona='Cliente' AND condicion=1");
        $ventas = ejecutarConsultaSimpleFila("SELECT COUNT(*) AS total, COALESCE(SUM(total_venta), 0) AS monto FROM venta WHERE estado='Aceptado'");
        return [
            'productos' => (int) ($productos['total'] ?? 0),
            'categorias' => (int) ($categorias['total'] ?? 0),
            'clientes' => (int) ($clientes['total'] ?? 0),
            'ventas' => (int) ($ventas['total'] ?? 0),
            'monto_ventas' => (float) ($ventas['monto'] ?? 0),
        ];
    }
}
