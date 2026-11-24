<?php
require_once "../modelos/Marca.php";

$marca = new Marca();

$idmarca = isset($_POST["idmarca"]) ? limpiarCadena($_POST["idmarca"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
$descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";

switch ($_GET["op"] ?? "") {
    case "guardaryeditar":
        if (empty($idmarca)) {
            $rspta = $marca->insertar($nombre, $descripcion);
            echo $rspta ? "Marca registrada correctamente" : "No se pudo registrar la marca";
        } else {
            $rspta = $marca->editar($idmarca, $nombre, $descripcion);
            echo $rspta ? "Marca actualizada correctamente" : "No se pudo actualizar la marca";
        }
        break;

    case "desactivar":
        $rspta = $marca->desactivar($idmarca);
        echo $rspta ? "Marca desactivada" : "No se pudo desactivar";
        break;

    case "activar":
        $rspta = $marca->activar($idmarca);
        echo $rspta ? "Marca activada" : "No se pudo activar";
        break;

    case "eliminar":
        $rspta = $marca->eliminar($idmarca);
        echo $rspta ? "Marca eliminada" : "No se pudo eliminar (puede tener productos asociados)";
        break;

    case "mostrar":
        $rspta = $marca->mostrar($idmarca);
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($rspta);
        break;

    case "listar":
        $rspta = $marca->listar();
        $data = [];
        while ($reg = $rspta->fetch_object()) {
            $opciones = '<button class="btn btn-sm btn-warning me-1" onclick="mostrar(' . $reg->idmarca . ')" aria-label="Editar"><i class="bi bi-pencil"></i></button>';
            $opciones .= $reg->condicion
                ? '<button class="btn btn-sm btn-danger me-1" onclick="desactivar(' . $reg->idmarca . ')" aria-label="Desactivar"><i class="bi bi-x-lg"></i></button>'
                : '<button class="btn btn-sm btn-success me-1" onclick="activar(' . $reg->idmarca . ')" aria-label="Activar"><i class="bi bi-check-lg"></i></button>';
            $opciones .= '<button class="btn btn-sm btn-outline-danger" onclick="eliminar(' . $reg->idmarca . ')" aria-label="Eliminar"><i class="bi bi-trash"></i></button>';

            $estado = $reg->condicion
                ? '<span class="badge bg-success-subtle text-success">Activado</span>'
                : '<span class="badge bg-danger-subtle text-danger">Desactivado</span>';

            $data[] = [
                "0" => $opciones,
                "1" => $reg->nombre,
                "2" => $reg->descripcion,
                "3" => $estado,
            ];
        }
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode(["sEcho" => 1, "iTotalRecords" => count($data), "iTotalDisplayRecords" => count($data), "aaData" => $data]);
        break;
}
