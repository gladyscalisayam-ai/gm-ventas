<?php
require_once "../modelos/Categoria.php";

$categoria = new Categoria();

$idcategoria = isset($_POST["idcategoria"]) ? limpiarCadena($_POST["idcategoria"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
$descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";

switch ($_GET["op"] ?? "") {
    case "guardaryeditar":
        if (empty($idcategoria)) {
            $rspta = $categoria->insertar($nombre, $descripcion);
            echo $rspta ? "Categoría registrada correctamente" : "No se pudo registrar la categoría";
        } else {
            $rspta = $categoria->editar($idcategoria, $nombre, $descripcion);
            echo $rspta ? "Categoría actualizada correctamente" : "No se pudo actualizar la categoría";
        }
        break;

    case "desactivar":
        $rspta = $categoria->desactivar($idcategoria);
        echo $rspta ? "Categoría desactivada" : "No se pudo desactivar";
        break;

    case "activar":
        $rspta = $categoria->activar($idcategoria);
        echo $rspta ? "Categoría activada" : "No se pudo activar";
        break;

    case "eliminar":
        $rspta = $categoria->eliminar($idcategoria);
        echo $rspta ? "Categoría eliminada" : "No se pudo eliminar (puede tener productos asociados)";
        break;

    case "mostrar":
        $rspta = $categoria->mostrar($idcategoria);
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($rspta);
        break;

    case "listar":
        $rspta = $categoria->listar();
        $data = [];
        while ($reg = $rspta->fetch_object()) {
            $opciones = '<button class="btn btn-sm btn-warning me-1" onclick="mostrar(' . $reg->idcategoria . ')" aria-label="Editar"><i class="bi bi-pencil"></i></button>';
            $opciones .= $reg->condicion
                ? '<button class="btn btn-sm btn-danger me-1" onclick="desactivar(' . $reg->idcategoria . ')" aria-label="Desactivar"><i class="bi bi-x-lg"></i></button>'
                : '<button class="btn btn-sm btn-success me-1" onclick="activar(' . $reg->idcategoria . ')" aria-label="Activar"><i class="bi bi-check-lg"></i></button>';
            $opciones .= '<button class="btn btn-sm btn-outline-danger" onclick="eliminar(' . $reg->idcategoria . ')" aria-label="Eliminar"><i class="bi bi-trash"></i></button>';

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
