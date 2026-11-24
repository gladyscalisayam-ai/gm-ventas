<?php
if (strlen(session_id()) < 1) {
    session_start();
}
require_once "../modelos/usuario.php";
require_once "../modelos/permiso.php";

$usuario = new Usuario();

$idusuario = isset($_POST["idusuario"]) ? limpiarCadena($_POST["idusuario"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
$tipo_documento = isset($_POST["tipo_documento"]) ? limpiarCadena($_POST["tipo_documento"]) : "";
$num_documento = isset($_POST["num_documento"]) ? limpiarCadena($_POST["num_documento"]) : "";
$direccion = isset($_POST["direccion"]) ? limpiarCadena($_POST["direccion"]) : "";
$telefono = isset($_POST["telefono"]) ? limpiarCadena($_POST["telefono"]) : "";
$email = isset($_POST["email"]) ? limpiarCadena($_POST["email"]) : "";
$cargo = isset($_POST["cargo"]) ? limpiarCadena($_POST["cargo"]) : "";
$login = isset($_POST["login"]) ? limpiarCadena($_POST["login"]) : "";
$clave = isset($_POST["clave"]) ? trim((string) $_POST["clave"]) : "";
$imagen = isset($_POST["imagenactual"]) ? limpiarCadena($_POST["imagenactual"]) : "";

switch ($_GET["op"] ?? "") {
    case "guardaryeditar":
        if (!empty($_FILES["imagen"]["tmp_name"])) {
            $ext = pathinfo($_FILES["imagen"]["name"], PATHINFO_EXTENSION);
            $imagen = round(microtime(true)) . "." . $ext;
            move_uploaded_file($_FILES["imagen"]["tmp_name"], "../files/usuarios/" . $imagen);
        }
        $clavehash = $clave !== "" ? hash("SHA256", $clave) : "";
        $permisos = $_POST["permiso"] ?? [];

        if (empty($idusuario)) {
            echo $usuario->insertar($nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email, $cargo, $login, $clavehash, $imagen, $permisos)
                ? "Usuario registrado"
                : "No se pudo registrar el usuario";
        } else {
            echo $usuario->editar($idusuario, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email, $cargo, $login, $clavehash, $imagen, $permisos)
                ? "Usuario actualizado"
                : "No se pudo actualizar el usuario";
        }
        break;

    case "desactivar":
        echo $usuario->desactivar($idusuario) ? "Usuario desactivado" : "Error";
        break;

    case "activar":
        echo $usuario->activar($idusuario) ? "Usuario activado" : "Error";
        break;

    case "eliminar":
        echo $usuario->eliminar($idusuario) ? "Usuario eliminado" : "No se pudo eliminar";
        break;

    case "mostrar":
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($usuario->mostrar($idusuario));
        break;

    case "permisos":
        $permiso = new Permiso();
        $rspta = $permiso->listar();
        $marcados = $usuario->listarmarcados($idusuario);
        $valores = [];
        while ($per = $marcados->fetch_object()) {
            $valores[] = (int) $per->idpermiso;
        }
        while ($reg = $rspta->fetch_object()) {
            $sw = in_array((int) $reg->idpermiso, $valores) ? "checked" : "";
            echo '<div class="form-check"><input class="form-check-input" type="checkbox" ' . $sw . ' name="permiso[]" value="' . $reg->idpermiso . '" id="perm_' . $reg->idpermiso . '"><label class="form-check-label" for="perm_' . $reg->idpermiso . '">' . $reg->nombre . '</label></div>';
        }
        break;

    case "listar":
        $rspta = $usuario->listar();
        $data = [];
        while ($reg = $rspta->fetch_object()) {
            $opciones = '<button class="btn btn-sm btn-warning me-1" onclick="mostrar(' . $reg->idusuario . ')" aria-label="Editar"><i class="bi bi-pencil"></i></button>';
            $opciones .= $reg->condicion
                ? '<button class="btn btn-sm btn-danger me-1" onclick="desactivar(' . $reg->idusuario . ')" aria-label="Desactivar"><i class="bi bi-x-lg"></i></button>'
                : '<button class="btn btn-sm btn-success me-1" onclick="activar(' . $reg->idusuario . ')" aria-label="Activar"><i class="bi bi-check-lg"></i></button>';
            $opciones .= '<button class="btn btn-sm btn-outline-danger" onclick="eliminar(' . $reg->idusuario . ')" aria-label="Eliminar"><i class="bi bi-trash"></i></button>';

            $estado = $reg->condicion
                ? '<span class="badge bg-success-subtle text-success">Activado</span>'
                : '<span class="badge bg-danger-subtle text-danger">Desactivado</span>';

            $data[] = [
                "0" => $opciones,
                "1" => $reg->nombre,
                "2" => $reg->tipo_documento . ': ' . $reg->num_documento,
                "3" => $reg->cargo,
                "4" => '<code>' . $reg->login . '</code>',
                "5" => $reg->email,
                "6" => $estado,
            ];
        }
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode(["sEcho" => 1, "iTotalRecords" => count($data), "iTotalDisplayRecords" => count($data), "aaData" => $data]);
        break;

    case "verificar":
        $logina = limpiarCadena($_POST["logina"] ?? "");
        $clavea = trim((string) ($_POST["clavea"] ?? ""));
        $clavehash = hash("SHA256", $clavea);

        $rspta = $usuario->verificar($logina, $clavehash);
        $fetch = $rspta->fetch_object();
        header("Content-Type: application/json; charset=utf-8");

        if ($fetch) {
            $_SESSION["idusuario"] = (int) $fetch->idusuario;
            $_SESSION["nombre"] = $fetch->nombre;
            $_SESSION["cargo"] = $fetch->cargo;
            $_SESSION["login"] = $fetch->login;
            $_SESSION["imagen"] = $fetch->imagen;

            $marcados = $usuario->listarmarcados($fetch->idusuario);
            $valores = [];
            while ($per = $marcados->fetch_object()) {
                $valores[] = (int) $per->idpermiso;
            }
            $_SESSION["escritorio"] = in_array(1, $valores) ? 1 : 0;
            $_SESSION["almacen"] = in_array(2, $valores) ? 1 : 0;
            $_SESSION["compras"] = in_array(3, $valores) ? 1 : 0;
            $_SESSION["ventas"] = in_array(4, $valores) ? 1 : 0;
            $_SESSION["acceso"] = in_array(5, $valores) ? 1 : 0;
            $_SESSION["consultacompras"] = in_array(6, $valores) ? 1 : 0;
            $_SESSION["consultaventas"] = in_array(7, $valores) ? 1 : 0;

            echo json_encode($fetch);
        } else {
            echo json_encode(null);
        }
        break;

    case "salir":
        session_unset();
        session_destroy();
        header("Location: ../vistas/login.html");
        break;
}
