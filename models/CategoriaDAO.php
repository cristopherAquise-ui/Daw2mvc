<?php
include_once("config/dataBase.php");
include_once("models/Categoria.php");

class CategoriaDAO
{
    // todas las categorias
    public static function getAll()
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare("SELECT * FROM categorias");
        $consulta->execute();
        $resultado = $consulta->get_result();

        $categorias = [];
        while ($categoria = $resultado->fetch_object("Categoria")) {
            $categorias[] = $categoria;
        }

        $consulta->close();
        $conexion->close();

        return $categorias;
    }
    public static function getById($id)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare("SELECT * FROM categorias WHERE id = ?");
        $consulta->bind_param("i", $id);
        $consulta->execute();
        $resultado = $consulta->get_result();
        $categoria = $resultado->fetch_object("Categoria");

        $consulta->close();
        $conexion->close();

        return $categoria;
    }
}
