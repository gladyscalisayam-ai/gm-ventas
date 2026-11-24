<?php
require_once "../config/Conexion.php";

class Usuario
{
    public function insertar($nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email, $cargo, $login, $clavehash, $imagen, $permisos)
    {
        $sql = "INSERT INTO usuario (nombre, tipo_documento, num_documento, direccion, telefono, email, cargo, login, clave, imagen, condicion)
                VALUES ('$nombre', '$tipo_documento', '$num_documento', '$direccion', '$telefono', '$email', '$cargo', '$login', '$clavehash', '$imagen', 1)";
        $idusuario = ejecutarConsulta_retornarID($sql);
        if ($idusuario && is_array($permisos)) {
            foreach ($permisos as $idpermiso) {
                $pid = (int) $idpermiso;
                ejecutarConsulta("INSERT INTO usuario_permiso (idusuario, idpermiso) VALUES ('$idusuario', '$pid')");
            }
        }
        return $idusuario > 0;
    }

    public function editar($idusuario, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email, $cargo, $login, $clavehash, $imagen, $permisos)
    {
        $setClave = $clavehash !== '' ? ", clave='$clavehash'" : "";
        $sql = "UPDATE usuario
                SET nombre='$nombre', tipo_documento='$tipo_documento', num_documento='$num_documento',
                    direccion='$direccion', telefono='$telefono', email='$email', cargo='$cargo',
                    login='$login', imagen='$imagen' $setClave
                WHERE idusuario='$idusuario'";
        $rs = ejecutarConsulta($sql);

        ejecutarConsulta("DELETE FROM usuario_permiso WHERE idusuario='$idusuario'");
        if (is_array($permisos)) {
            foreach ($permisos as $idpermiso) {
                $pid = (int) $idpermiso;
                ejecutarConsulta("INSERT INTO usuario_permiso (idusuario, idpermiso) VALUES ('$idusuario', '$pid')");
            }
        }
        return $rs;
    }

    public function desactivar($idusuario)
    {
        return ejecutarConsulta("UPDATE usuario SET condicion=0 WHERE idusuario='$idusuario'");
    }

    public function activar($idusuario)
    {
        return ejecutarConsulta("UPDATE usuario SET condicion=1 WHERE idusuario='$idusuario'");
    }

    public function eliminar($idusuario)
    {
        return ejecutarConsulta("DELETE FROM usuario WHERE idusuario='$idusuario'");
    }

    public function mostrar($idusuario)
    {
        return ejecutarConsultaSimpleFila("SELECT * FROM usuario WHERE idusuario='$idusuario'");
    }

    public function listar()
    {
        return ejecutarConsulta("SELECT * FROM usuario ORDER BY idusuario DESC");
    }

    public function listarmarcados($idusuario)
    {
        return ejecutarConsulta("SELECT * FROM usuario_permiso WHERE idusuario='$idusuario'");
    }

    public function verificar($login, $clavehash)
    {
        $sql = "SELECT idusuario, nombre, tipo_documento, num_documento, direccion, telefono, email, cargo, imagen, login
                FROM usuario
                WHERE login='$login' AND clave='$clavehash' AND condicion=1";
        return ejecutarConsulta($sql);
    }
}
