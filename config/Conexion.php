<?php
require_once "global.php";

$conexion = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($conexion->connect_errno) {
    die("Error de conexión a MySQL (" . $conexion->connect_errno . "): " . $conexion->connect_error);
}
$conexion->set_charset(DB_ENCODE);

if (!function_exists('ejecutarConsulta')) {
    function ejecutarConsulta($sql)
    {
        global $conexion;
        return $conexion->query($sql);
    }

    function ejecutarConsultaSimpleFila($sql)
    {
        global $conexion;
        $query = $conexion->query($sql);
        if (!$query) {
            return null;
        }
        return $query->fetch_assoc();
    }

    function ejecutarConsulta_retornarID($sql)
    {
        global $conexion;
        $conexion->query($sql);
        return $conexion->insert_id;
    }

    function limpiarCadena($str)
    {
        global $conexion;
        $limpia = mysqli_real_escape_string($conexion, trim((string) $str));
        return htmlspecialchars($limpia, ENT_QUOTES, 'UTF-8');
    }
}
