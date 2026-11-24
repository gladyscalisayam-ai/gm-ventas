<?php
require_once "../modelos/articulo.php";
require_once "../modelos/consultacompras.php";

$articulo = new Articulo();
$consulta = new ConsultaCompras();
$op = $_GET["op"] ?? "dashboard";

if ($op === "ajustar_stock") {
    header("Content-Type: application/json; charset=utf-8");
    $idarticulo = isset($_POST["idarticulo"]) ? (int) $_POST["idarticulo"] : 0;
    $delta = isset($_POST["delta"]) ? (int) $_POST["delta"] : 0;
    $ok = $articulo->actualizarStock($idarticulo, $delta);
    $actual = $articulo->mostrar($idarticulo);
    echo json_encode(["ok" => (bool) $ok, "stock" => (int) ($actual["stock"] ?? 0)]);
    exit;
}

if ($op === "exportar_json") {
    $tablas = ["categoria", "marca", "articulo", "persona", "usuario", "permiso", "ingreso", "detalle_ingreso", "venta", "detalle_venta"];
    $export = ["sistema" => "GM VENTAS", "exportado_en" => date("c"), "datos" => []];
    foreach ($tablas as $t) {
        $res = ejecutarConsulta("SELECT * FROM $t");
        $filas = [];
        while ($row = $res->fetch_assoc()) {
            if ($t === "usuario") {
                unset($row["clave"]);
            }
            $filas[] = $row;
        }
        $export["datos"][$t] = $filas;
    }
    header("Content-Type: application/json; charset=utf-8");
    header("Content-Disposition: attachment; filename=gm_ventas_backup.json");
    echo json_encode($export, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

if ($op === "exportar_sql") {
    $tablas = ["categoria", "marca", "articulo", "persona", "permiso", "usuario", "usuario_permiso", "ingreso", "detalle_ingreso", "venta", "detalle_venta"];
    $out = "-- Respaldo SQL GM VENTAS (" . date("Y-m-d H:i:s") . ")\n";
    $out .= "CREATE DATABASE IF NOT EXISTS gm_ventas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;\nUSE gm_ventas;\n\n";
    global $conexion;
    foreach ($tablas as $t) {
        $res = ejecutarConsulta("SELECT * FROM $t");
        while ($row = $res->fetch_assoc()) {
            $cols = array_keys($row);
            $vals = array_map(function ($v) use ($conexion) {
                return $v === null ? "NULL" : "'" . $conexion->real_escape_string((string) $v) . "'";
            }, array_values($row));
            $out .= "INSERT INTO `$t` (`" . implode("`, `", $cols) . "`) VALUES (" . implode(", ", $vals) . ");\n";
        }
        $out .= "\n";
    }
    header("Content-Type: application/sql; charset=utf-8");
    header("Content-Disposition: attachment; filename=gm_ventas_backup.sql");
    echo $out;
    exit;
}

// Endpoint por defecto para Dashboard, Chart.js y Vue.js 3
$rspta = $articulo->listarActivos();
$articulos = [];
while ($reg = $rspta->fetch_assoc()) {
    $reg["idarticulo"] = (int) $reg["idarticulo"];
    $reg["stock"] = (int) $reg["stock"];
    $reg["precio_venta"] = (float) $reg["precio_venta"];
    $articulos[] = $reg;
}

$catRes = ejecutarConsulta("SELECT c.nombre AS categoria, COUNT(a.idarticulo) AS total FROM categoria c LEFT JOIN articulo a ON a.idcategoria = c.idcategoria AND a.condicion = 1 WHERE c.condicion = 1 GROUP BY c.idcategoria, c.nombre");
$porCategoria = [];
while ($row = $catRes->fetch_assoc()) {
    $porCategoria[] = ["categoria" => $row["categoria"], "total" => (int) $row["total"]];
}

header("Content-Type: application/json; charset=utf-8");
echo json_encode([
    "kpis" => $consulta->resumenKpis(),
    "articulos" => $articulos,
    "categorias_chart" => $porCategoria,
]);
