<?php
require_once "../config/Conexion.php";

class Cliente
{
    public function insertar($nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email)
    {
        $sql = "INSERT INTO persona (tipo_persona, nombre, tipo_documento, num_documento, direccion, telefono, email, condicion)
                VALUES ('Cliente', '$nombre', '$tipo_documento', '$num_documento', '$direccion', '$telefono', '$email', 1)";
        return ejecutarConsulta($sql);
    }

    public function editar($idpersona, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email)
    {
        $sql = "UPDATE persona
                SET nombre='$nombre', tipo_documento='$tipo_documento', num_documento='$num_documento',
                    direccion='$direccion', telefono='$telefono', email='$email'
                WHERE idpersona='$idpersona' AND tipo_persona='Cliente'";
        return ejecutarConsulta($sql);
    }

    public function desactivar($idpersona)
    {
        $sql = "UPDATE persona SET condicion=0 WHERE idpersona='$idpersona'";
        return ejecutarConsulta($sql);
    }

    public function activar($idpersona)
    {
        $sql = "UPDATE persona SET condicion=1 WHERE idpersona='$idpersona'";
        return ejecutarConsulta($sql);
    }

    public function eliminar($idpersona)
    {
        $sql = "DELETE FROM persona WHERE idpersona='$idpersona' AND tipo_persona='Cliente'";
        return ejecutarConsulta($sql);
    }

    public function mostrar($idpersona)
    {
        $sql = "SELECT * FROM persona WHERE idpersona='$idpersona'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function listar()
    {
        $sql = "SELECT * FROM persona WHERE tipo_persona='Cliente' ORDER BY idpersona DESC";
        return ejecutarConsulta($sql);
    }

    public function select()
    {
        $sql = "SELECT * FROM persona WHERE tipo_persona='Cliente' AND condicion=1 ORDER BY nombre ASC";
        return ejecutarConsulta($sql);
    }
}
