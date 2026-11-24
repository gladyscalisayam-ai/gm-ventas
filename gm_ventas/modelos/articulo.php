<?php
require_once "../config/Conexion.php";

class Articulo
{
    public function insertar($idcategoria, $idmarca, $codigo, $nombre, $stock, $precio_venta, $descripcion, $imagen)
    {
        $sql = "INSERT INTO articulo (idcategoria, idmarca, codigo, nombre, stock, precio_venta, descripcion, imagen, condicion)
                VALUES ('$idcategoria', '$idmarca', '$codigo', '$nombre', '$stock', '$precio_venta', '$descripcion', '$imagen', 1)";
        return ejecutarConsulta($sql);
    }

    public function editar($idarticulo, $idcategoria, $idmarca, $codigo, $nombre, $stock, $precio_venta, $descripcion, $imagen)
    {
        $sql = "UPDATE articulo
                SET idcategoria='$idcategoria', idmarca='$idmarca', codigo='$codigo', nombre='$nombre',
                    stock='$stock', precio_venta='$precio_venta', descripcion='$descripcion', imagen='$imagen'
                WHERE idarticulo='$idarticulo'";
        return ejecutarConsulta($sql);
    }

    public function desactivar($idarticulo)
    {
        $sql = "UPDATE articulo SET condicion=0 WHERE idarticulo='$idarticulo'";
        return ejecutarConsulta($sql);
    }

    public function activar($idarticulo)
    {
        $sql = "UPDATE articulo SET condicion=1 WHERE idarticulo='$idarticulo'";
        return ejecutarConsulta($sql);
    }

    public function eliminar($idarticulo)
    {
        $sql = "DELETE FROM articulo WHERE idarticulo='$idarticulo'";
        return ejecutarConsulta($sql);
    }

    public function actualizarStock($idarticulo, $delta)
    {
        $sql = "UPDATE articulo SET stock = GREATEST(0, stock + ($delta)) WHERE idarticulo='$idarticulo'";
        return ejecutarConsulta($sql);
    }

    public function mostrar($idarticulo)
    {
        $sql = "SELECT * FROM articulo WHERE idarticulo='$idarticulo'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function listar()
    {
        $sql = "SELECT a.*, c.nombre AS categoria, m.nombre AS marca
                FROM articulo a
                INNER JOIN categoria c ON a.idcategoria = c.idcategoria
                INNER JOIN marca m ON a.idmarca = m.idmarca
                ORDER BY a.idarticulo DESC";
        return ejecutarConsulta($sql);
    }

    public function listarActivos()
    {
        $sql = "SELECT a.*, c.nombre AS categoria, m.nombre AS marca
                FROM articulo a
                INNER JOIN categoria c ON a.idcategoria = c.idcategoria
                INNER JOIN marca m ON a.idmarca = m.idmarca
                WHERE a.condicion=1
                ORDER BY a.nombre ASC";
        return ejecutarConsulta($sql);
    }

    public function listarActivosVenta()
    {
        $sql = "SELECT a.*, c.nombre AS categoria, m.nombre AS marca
                FROM articulo a
                INNER JOIN categoria c ON a.idcategoria = c.idcategoria
                INNER JOIN marca m ON a.idmarca = m.idmarca
                WHERE a.condicion=1 AND a.stock > 0
                ORDER BY a.nombre ASC";
        return ejecutarConsulta($sql);
    }
}
