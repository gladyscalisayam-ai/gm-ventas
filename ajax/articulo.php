<?php
require_once "../modelos/articulo.php";
require_once "../modelos/Categoria.php";
require_once "../modelos/Marca.php";

$articulo = new Articulo();

$idarticulo = isset($_POST["idarticulo"]) ? limpiarCadena($_POST["idarticulo"]) : "";
$idcategoria = isset($_POST["idcategoria"]) ? limpiarCadena($_POST["idcategoria"]) : "";
$idmarca = isset($_POST["idmarca"]) ? limpiarCadena($_POST["idmarca"]) : "";
$codigo = isset($_POST["codigo"]) ? limpiarCadena($_POST["codigo"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
$stock = isset($_POST["stock"]) ? (int) $_POST["stock"] : 0;
$precio_venta = isset($_POST["precio_venta"]) ? (float) $_POST["precio_venta"] : 0;
$descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";
$imagen = isset($_POST["imagenactual"]) ? limpiarCadena($_POST["imagenactual"]) : "";

switch ($_GET["op"] ?? "") {
    case "guardaryeditar":
        if (!empty($_FILES["imagen"]["tmp_name"])) {
            $ext = pathinfo($_FILES["imagen"]["name"], PATHINFO_EXTENSION);
            $imagen = round(microtime(true)) . "." . $ext;
            move_uploaded_file($_FILES["imagen"]["tmp_name"], "../files/articulos/" . $imagen);
        }

        if (empty($idarticulo)) {
            $rspta = $articulo->insertar($idcategoria, $idmarca, $codigo, $nombre, $stock, $precio_venta, $descripcion, $imagen);
            echo $rspta ? "Producto registrado correctamente" : "No se pudo registrar el producto";
        } else {
            $rspta = $articulo->editar($idarticulo, $idcategoria, $idmarca, $codigo, $nombre, $stock, $precio_venta, $descripcion, $imagen);
            echo $rspta ? "Producto actualizado correctamente" : "No se pudo actualizar el producto";
        }
        break;

    case "desactivar":
        echo $articulo->desactivar($idarticulo) ? "Producto desactivado" : "No se pudo desactivar";
        break;

    case "activar":
        echo $articulo->activar($idarticulo) ? "Producto activado" : "No se pudo activar";
        break;

    case "eliminar":
        echo $articulo->eliminar($idarticulo) ? "Producto eliminado" : "No se pudo eliminar";
        break;

    case "mostrar":
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($articulo->mostrar($idarticulo));
        break;

    case "selectCategoria":
        $cat = new Categoria();
        $rspta = $cat->select();
        while ($reg = $rspta->fetch_object()) {
            echo '<option value="' . $reg->idcategoria . '">' . $reg->nombre . '</option>';
        }
        break;

    case "selectMarca":
        $mar = new Marca();
        $rspta = $mar->select();
        while ($reg = $rspta->fetch_object()) {
            echo '<option value="' . $reg->idmarca . '">' . $reg->nombre . '</option>';
        }
        break;

    case "listar":
        $rspta = $articulo->listar();
        $data = [];
        while ($reg = $rspta->fetch_object()) {
            $opciones = '<button class="btn btn-sm btn-warning me-1" onclick="mostrar(' . $reg->idarticulo . ')" aria-label="Editar"><i class="bi bi-pencil"></i></button>';
            $opciones .= $reg->condicion
                ? '<button class="btn btn-sm btn-danger me-1" onclick="desactivar(' . $reg->idarticulo . ')" aria-label="Desactivar"><i class="bi bi-x-lg"></i></button>'
                : '<button class="btn btn-sm btn-success me-1" onclick="activar(' . $reg->idarticulo . ')" aria-label="Activar"><i class="bi bi-check-lg"></i></button>';
            $opciones .= '<button class="btn btn-sm btn-outline-danger" onclick="eliminar(' . $reg->idarticulo . ')" aria-label="Eliminar"><i class="bi bi-trash"></i></button>';

            $estado = $reg->condicion
                ? '<span class="badge bg-success-subtle text-success">Activado</span>'
                : '<span class="badge bg-danger-subtle text-danger">Desactivado</span>';

            $data[] = [
                "0" => $opciones,
                "1" => '<strong>' . $reg->nombre . '</strong>',
                "2" => $reg->categoria,
                "3" => $reg->marca,
                "4" => '<code>' . $reg->codigo . '</code>',
                "5" => $reg->stock,
                "6" => 'S/ ' . number_format((float) $reg->precio_venta, 2),
                "7" => $estado,
            ];
        }
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode(["sEcho" => 1, "iTotalRecords" => count($data), "iTotalDisplayRecords" => count($data), "aaData" => $data]);
        break;
}
