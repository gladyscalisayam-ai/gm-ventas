<?php
if (strlen(session_id()) < 1) {
    session_start();
}
require_once "../modelos/venta.php";
require_once "../modelos/cliente.php";
require_once "../modelos/articulo.php";

$venta = new Venta();
$idventa = isset($_POST["idventa"]) ? limpiarCadena($_POST["idventa"]) : "";
$idcliente = isset($_POST["idcliente"]) ? limpiarCadena($_POST["idcliente"]) : "";
$idusuario = $_SESSION["idusuario"] ?? 1;
$tipo_comprobante = isset($_POST["tipo_comprobante"]) ? limpiarCadena($_POST["tipo_comprobante"]) : "";
$serie_comprobante = isset($_POST["serie_comprobante"]) ? limpiarCadena($_POST["serie_comprobante"]) : "";
$num_comprobante = isset($_POST["num_comprobante"]) ? limpiarCadena($_POST["num_comprobante"]) : "";
$fecha_hora = isset($_POST["fecha_hora"]) ? limpiarCadena($_POST["fecha_hora"]) : date("Y-m-d");
$impuesto = isset($_POST["impuesto"]) ? (float) $_POST["impuesto"] : 18;
$metodo_pago = isset($_POST["metodo_pago"]) ? limpiarCadena($_POST["metodo_pago"]) : "Efectivo";
$total_venta = isset($_POST["total_venta"]) ? (float) $_POST["total_venta"] : 0;

switch ($_GET["op"] ?? "") {
    case "guardaryeditar":
        $rspta = $venta->insertar(
            $idcliente,
            $idusuario,
            $tipo_comprobante,
            $serie_comprobante,
            $num_comprobante,
            $fecha_hora,
            $impuesto,
            $metodo_pago,
            $total_venta,
            $_POST["idarticulo"] ?? [],
            $_POST["cantidad"] ?? [],
            $_POST["precio_venta"] ?? [],
            $_POST["descuento"] ?? []
        );
        echo $rspta === true ? "Venta registrada y stock descontado" : $rspta;
        break;

    case "anular":
        echo $venta->anular($idventa) ? "Venta anulada y stock restaurado" : "No se pudo anular la venta";
        break;

    case "mostrar":
        header("Content-Type: application/json; charset=utf-8");
        $cab = $venta->mostrar($idventa);
        $det = $venta->listarDetalle($idventa);
        $items = [];
        while ($row = $det->fetch_assoc()) {
            $items[] = $row;
        }
        echo json_encode(["cabecera" => $cab, "detalles" => $items]);
        break;

    case "selectCliente":
        $cli = new Cliente();
        $rspta = $cli->select();
        while ($reg = $rspta->fetch_object()) {
            echo '<option value="' . $reg->idpersona . '">' . $reg->nombre . '</option>';
        }
        break;

    case "listarArticulosVenta":
        $art = new Articulo();
        $rspta = $art->listarActivosVenta();
        $data = [];
        while ($reg = $rspta->fetch_object()) {
            $btn = '<button type="button" class="btn btn-sm btn-gm" onclick="agregarDetalle(' . $reg->idarticulo . ', \'' . addslashes($reg->nombre) . '\', ' . $reg->precio_venta . ', ' . $reg->stock . ')"><i class="bi bi-plus-lg"></i></button>';
            $data[] = [
                "0" => $btn,
                "1" => $reg->nombre,
                "2" => $reg->categoria . ' / ' . $reg->marca,
                "3" => $reg->codigo,
                "4" => $reg->stock,
                "5" => 'S/ ' . number_format((float) $reg->precio_venta, 2),
            ];
        }
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode(["sEcho" => 1, "iTotalRecords" => count($data), "iTotalDisplayRecords" => count($data), "aaData" => $data]);
        break;

    case "listar":
        $rspta = $venta->listar();
        $data = [];
        while ($reg = $rspta->fetch_object()) {
            $opciones = '<button class="btn btn-sm btn-gm me-1" onclick="mostrarComprobante(' . $reg->idventa . ')" aria-label="Imprimir comprobante"><i class="bi bi-printer"></i></button>';
            if ($reg->estado === "Aceptado") {
                $opciones .= '<button class="btn btn-sm btn-danger" onclick="anular(' . $reg->idventa . ')" aria-label="Anular"><i class="bi bi-x-lg"></i></button>';
            }
            $estado = $reg->estado === "Aceptado"
                ? '<span class="badge bg-success-subtle text-success">Aceptado</span>'
                : '<span class="badge bg-danger-subtle text-danger">Anulado</span>';
            $data[] = [
                "0" => $opciones,
                "1" => $reg->fecha_hora,
                "2" => $reg->cliente,
                "3" => $reg->usuario,
                "4" => $reg->tipo_comprobante . ' ' . $reg->serie_comprobante . '-' . $reg->num_comprobante,
                "5" => '<span class="badge bg-light text-dark border">' . $reg->metodo_pago . '</span>',
                "6" => 'S/ ' . number_format((float) $reg->total_venta, 2),
                "7" => $estado,
            ];
        }
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode(["sEcho" => 1, "iTotalRecords" => count($data), "iTotalDisplayRecords" => count($data), "aaData" => $data]);
        break;
}
