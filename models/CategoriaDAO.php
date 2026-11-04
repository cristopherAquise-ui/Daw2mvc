<?php
include_once("config/dataBase.php");
include_once("models/Categoria.php");

class CategoriaDAO
{
    public static function getAll()
    {
        $con = DataBase::connect();
        $stmt = $con->prepare("SELECT * FROM categorias");
        $stmt->execute();
        $result = $stmt->get_result();

        $categorias = [];
        while ($categoria = $result->fetch_object("Categoria")) {
            $categorias[] = $categoria;
        }

        $stmt->close();
        $con->close();

        return $categorias;
    }
}