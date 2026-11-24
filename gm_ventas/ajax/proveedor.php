<?php
require_once "../modelos/proveedor.php";

$proveedor = new Proveedor();
$idpersona = isset($_POST["idpersona"]) ? limpiarCadena($_POST["idpersona"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
$tipo_documento = isset($_POST["tipo_documento"]) ? limpiarCadena($_POST["tipo_documento"]) : "";
$num_documento = isset($_POST["num_documento"]) ? limpiarCadena($_POST["num_documento"]) : "";
$direccion = isset($_POST["direccion"]) ? limpiarCadena($_POST["direccion"]) : "";
$telefono = isset($_POST["telefono"]) ? limpiarCadena($_POST["telefono"]) : "";
$email = isset($_POST["email"]) ? limpiarCadena($_POST["email"]) : "";

switch ($_GET["op"] ?? "") {
    case "guardaryeditar":
        if (empty($idpersona)) {
            echo $proveedor->insertar($nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email)
                ? "Proveedor registrado"
                : "No se pudo registrar el proveedor";
        } else {
            echo $proveedor->editar($idpersona, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email)
                ? "Proveedor actualizado"
                : "No se pudo actualizar el proveedor";
        }
        break;

    case "desactivar":
        echo $proveedor->desactivar($idpersona) ? "Proveedor desactivado" : "Error al desactivar";
        break;

    case "activar":
        echo $proveedor->activar($idpersona) ? "Proveedor activado" : "Error al activar";
        break;

    case "eliminar":
        echo $proveedor->eliminar($idpersona) ? "Proveedor eliminado" : "No se pudo eliminar";
        break;

    case "mostrar":
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($proveedor->mostrar($idpersona));
        break;

    case "listar":
        $rspta = $proveedor->listar();
        $data = [];
        while ($reg = $rspta->fetch_object()) {
            $opciones = '<button class="btn btn-sm btn-warning me-1" onclick="mostrar(' . $reg->idpersona . ')" aria-label="Editar"><i class="bi bi-pencil"></i></button>';
            $opciones .= $reg->condicion
                ? '<button class="btn btn-sm btn-danger me-1" onclick="desactivar(' . $reg->idpersona . ')" aria-label="Desactivar"><i class="bi bi-x-lg"></i></button>'
                : '<button class="btn btn-sm btn-success me-1" onclick="activar(' . $reg->idpersona . ')" aria-label="Activar"><i class="bi bi-check-lg"></i></button>';
            $opciones .= '<button class="btn btn-sm btn-outline-danger" onclick="eliminar(' . $reg->idpersona . ')" aria-label="Eliminar"><i class="bi bi-trash"></i></button>';

            $estado = $reg->condicion
                ? '<span class="badge bg-success-subtle text-success">Activo</span>'
                : '<span class="badge bg-danger-subtle text-danger">Inactivo</span>';

            $data[] = [
                "0" => $opciones,
                "1" => $reg->nombre,
                "2" => $reg->tipo_documento . ': ' . $reg->num_documento,
                "3" => $reg->telefono,
                "4" => $reg->email,
                "5" => $estado,
            ];
        }
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode(["sEcho" => 1, "iTotalRecords" => count($data), "iTotalDisplayRecords" => count($data), "aaData" => $data]);
        break;
}
