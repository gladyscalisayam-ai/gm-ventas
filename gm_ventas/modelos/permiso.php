<?php
require_once "../config/Conexion.php";

class Permiso
{
    public function listar()
    {
        return ejecutarConsulta("SELECT * FROM permiso ORDER BY idpermiso ASC");
    }
}
