<?php
require_once "../config/Conexion.php";

class Marca
{
    public function insertar($nombre, $descripcion)
    {
        $sql = "INSERT INTO marca (nombre, descripcion, condicion) VALUES ('$nombre', '$descripcion', 1)";
        return ejecutarConsulta($sql);
    }

    public function editar($idmarca, $nombre, $descripcion)
    {
        $sql = "UPDATE marca SET nombre='$nombre', descripcion='$descripcion' WHERE idmarca='$idmarca'";
        return ejecutarConsulta($sql);
    }

    public function desactivar($idmarca)
    {
        $sql = "UPDATE marca SET condicion=0 WHERE idmarca='$idmarca'";
        return ejecutarConsulta($sql);
    }

    public function activar($idmarca)
    {
        $sql = "UPDATE marca SET condicion=1 WHERE idmarca='$idmarca'";
        return ejecutarConsulta($sql);
    }

    public function eliminar($idmarca)
    {
        $sql = "DELETE FROM marca WHERE idmarca='$idmarca'";
        return ejecutarConsulta($sql);
    }

    public function mostrar($idmarca)
    {
        $sql = "SELECT * FROM marca WHERE idmarca='$idmarca'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function listar()
    {
        $sql = "SELECT * FROM marca ORDER BY idmarca DESC";
        return ejecutarConsulta($sql);
    }

    public function select()
    {
        $sql = "SELECT * FROM marca WHERE condicion=1 ORDER BY nombre ASC";
        return ejecutarConsulta($sql);
    }
}
