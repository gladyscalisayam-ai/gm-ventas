<?php
require_once "../modelos/consultacompras.php";

$consulta = new ConsultaCompras();

switch ($_GET["op"] ?? "") {
    case "comprasfecha":
        $fecha_inicio = limpiarCadena($_GET["fecha_inicio"] ?? date("Y-m-01"));
        $fecha_fin = limpiarCadena($_GET["fecha_fin"] ?? date("Y-m-t"));
        $idproveedor = limpiarCadena($_GET["idproveedor"] ?? "");
        $estado = limpiarCadena($_GET["estado"] ?? "");
        $metodo_pago = limpiarCadena($_GET["metodo_pago"] ?? "");

        $rspta = $consulta->comprasFecha($fecha_inicio, $fecha_fin, $idproveedor, $estado, $metodo_pago);
        $data = [];
        while ($reg = $rspta->fetch_object()) {
            $badgeEstado = $reg->estado === "Aceptado"
                ? '<span class="badge bg-success-subtle text-success">Aceptado</span>'
                : '<span class="badge bg-danger-subtle text-danger">Anulado</span>';
            $data[] = [
                "0" => $reg->fecha_hora,
                "1" => $reg->usuario,
                "2" => $reg->proveedor,
                "3" => $reg->tipo_comprobante,
                "4" => $reg->serie_comprobante . '-' . $reg->num_comprobante,
                "5" => '<span class="badge bg-light text-dark border">' . $reg->metodo_pago . '</span>',
                "6" => 'S/ ' . number_format((float) $reg->total_compra, 2),
                "7" => $badgeEstado,
            ];
        }
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode(["sEcho" => 1, "iTotalRecords" => count($data), "iTotalDisplayRecords" => count($data), "aaData" => $data]);
        break;

    case "ventasfechacliente":
        $fecha_inicio = limpiarCadena($_GET["fecha_inicio"] ?? date("Y-m-01"));
        $fecha_fin = limpiarCadena($_GET["fecha_fin"] ?? date("Y-m-t"));
        $idcliente = limpiarCadena($_GET["idcliente"] ?? "");
        $estado = limpiarCadena($_GET["estado"] ?? "");
        $metodo_pago = limpiarCadena($_GET["metodo_pago"] ?? "");

        $rspta = $consulta->ventasFechaCliente($fecha_inicio, $fecha_fin, $idcliente, $estado, $metodo_pago);
        $data = [];
        while ($reg = $rspta->fetch_object()) {
            $badgeEstado = $reg->estado === "Aceptado"
                ? '<span class="badge bg-success-subtle text-success">Aceptado</span>'
                : '<span class="badge bg-danger-subtle text-danger">Anulado</span>';
            $data[] = [
                "0" => $reg->fecha_hora,
                "1" => $reg->usuario,
                "2" => $reg->cliente,
                "3" => $reg->tipo_comprobante,
                "4" => $reg->serie_comprobante . '-' . $reg->num_comprobante,
                "5" => '<span class="badge bg-light text-dark border">' . $reg->metodo_pago . '</span>',
                "6" => 'S/ ' . number_format((float) $reg->total_venta, 2),
                "7" => $badgeEstado,
            ];
        }
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode(["sEcho" => 1, "iTotalRecords" => count($data), "iTotalDisplayRecords" => count($data), "aaData" => $data]);
        break;
}
