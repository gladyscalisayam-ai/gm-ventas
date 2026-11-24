<?php
require_once "../modelos/permiso.php";

$permiso = new Permiso();

if (($_GET["op"] ?? "") === "listar") {
    $rspta = $permiso->listar();
    $data = [];
    while ($reg = $rspta->fetch_object()) {
        $data[] = [
            "0" => '#' . $reg->idpermiso,
            "1" => $reg->nombre,
        ];
    }
    header("Content-Type: application/json; charset=utf-8");
    echo json_encode(["sEcho" => 1, "iTotalRecords" => count($data), "iTotalDisplayRecords" => count($data), "aaData" => $data]);
}
