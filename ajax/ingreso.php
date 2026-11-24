<?php
if (strlen(session_id()) < 1) {
    session_start();
}
require_once "../modelos/Ingreso.php";
require_once "../modelos/proveedor.php";
require_once "../modelos/articulo.php";

$ingreso = new Ingreso();
$idingreso = isset($_POST["idingreso"]) ? limpiarCadena($_POST["idingreso"]) : "";
$idproveedor = isset($_POST["idproveedor"]) ? limpiarCadena($_POST["idproveedor"]) : "";
$idusuario = $_SESSION["idusuario"] ?? 1;
$tipo_comprobante = isset($_POST["tipo_comprobante"]) ? limpiarCadena($_POST["tipo_comprobante"]) : "";
$serie_comprobante = isset($_POST["serie_comprobante"]) ? limpiarCadena($_POST["serie_comprobante"]) : "";
$num_comprobante = isset($_POST["num_comprobante"]) ? limpiarCadena($_POST["num_comprobante"]) : "";
$fecha_hora = isset($_POST["fecha_hora"]) ? limpiarCadena($_POST["fecha_hora"]) : date("Y-m-d");
$impuesto = isset($_POST["impuesto"]) ? (float) $_POST["impuesto"] : 18;
$metodo_pago = isset($_POST["metodo_pago"]) ? limpiarCadena($_POST["metodo_pago"]) : "Transferencia";
$total_compra = isset($_POST["total_compra"]) ? (float) $_POST["total_compra"] : 0;

switch ($_GET["op"] ?? "") {
    case "guardaryeditar":
        $rspta = $ingreso->insertar(
            $idproveedor,
            $idusuario,
            $tipo_comprobante,
            $serie_comprobante,
            $num_comprobante,
            $fecha_hora,
            $impuesto,
            $metodo_pago,
            $total_compra,
            $_POST["idarticulo"] ?? [],
            $_POST["cantidad"] ?? [],
            $_POST["precio_compra"] ?? [],
            $_POST["precio_venta"] ?? []
        );
        echo $rspta ? "Ingreso registrado y stock incrementado" : "No se pudo registrar el ingreso";
        break;

    case "anular":
        echo $ingreso->anular($idingreso) ? "Ingreso anulado y stock revertido" : "No se pudo anular el ingreso";
        break;

    case "mostrar":
        header("Content-Type: application/json; charset=utf-8");
        $cab = $ingreso->mostrar($idingreso);
        $det = $ingreso->listarDetalle($idingreso);
        $items = [];
        while ($row = $det->fetch_assoc()) {
            $items[] = $row;
        }
        echo json_encode(["cabecera" => $cab, "detalles" => $items]);
        break;

    case "selectProveedor":
        $prov = new Proveedor();
        $rspta = $prov->select();
        while ($reg = $rspta->fetch_object()) {
            echo '<option value="' . $reg->idpersona . '">' . $reg->nombre . '</option>';
        }
        break;

    case "listarArticulos":
        $art = new Articulo();
        $rspta = $art->listarActivos();
        $data = [];
        while ($reg = $rspta->fetch_object()) {
            $btn = '<button type="button" class="btn btn-sm btn-gm" onclick="agregarDetalle(' . $reg->idarticulo . ', \'' . addslashes($reg->nombre) . '\', ' . $reg->precio_venta . ')"><i class="bi bi-plus-lg"></i></button>';
            $data[] = [
                "0" => $btn,
                "1" => $reg->nombre,
                "2" => $reg->categoria . ' / ' . $reg->marca,
                "3" => $reg->codigo,
                "4" => $reg->stock,
            ];
        }
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode(["sEcho" => 1, "iTotalRecords" => count($data), "iTotalDisplayRecords" => count($data), "aaData" => $data]);
        break;

    case "listar":
        $rspta = $ingreso->listar();
        $data = [];
        while ($reg = $rspta->fetch_object()) {
            $opciones = '<button class="btn btn-sm btn-info text-white me-1" onclick="mostrar(' . $reg->idingreso . ')" aria-label="Ver detalle"><i class="bi bi-eye"></i></button>';
            if ($reg->estado === "Aceptado") {
                $opciones .= '<button class="btn btn-sm btn-danger" onclick="anular(' . $reg->idingreso . ')" aria-label="Anular"><i class="bi bi-x-lg"></i></button>';
            }
            $estado = $reg->estado === "Aceptado"
                ? '<span class="badge bg-success-subtle text-success">Aceptado</span>'
                : '<span class="badge bg-danger-subtle text-danger">Anulado</span>';
            $data[] = [
                "0" => $opciones,
                "1" => $reg->fecha_hora,
                "2" => $reg->proveedor,
                "3" => $reg->usuario,
                "4" => $reg->tipo_comprobante . ' ' . $reg->serie_comprobante . '-' . $reg->num_comprobante,
                "5" => '<span class="badge bg-light text-dark border">' . $reg->metodo_pago . '</span>',
                "6" => 'S/ ' . number_format((float) $reg->total_compra, 2),
                "7" => $estado,
            ];
        }
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode(["sEcho" => 1, "iTotalRecords" => count($data), "iTotalDisplayRecords" => count($data), "aaData" => $data]);
        break;
}
