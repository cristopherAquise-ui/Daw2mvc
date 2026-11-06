<?php
include_once("config/dataBase.php");
include_once("models/Producto.php");

class ProductoDAO
{
    // Obtener todos los productos activos
    public static function getAll()
    {
        $con = DataBase::connect();
        $stmt = $con->prepare("SELECT * FROM productos WHERE activo = true");
        $stmt->execute();
        $result = $stmt->get_result();

        $productos = [];
        while ($producto = $result->fetch_object("Producto")) {
            $productos[] = $producto;
        }

        $stmt->close();
        $con->close();
        return $productos;
    }

    // Obtener productos filtrados por id de categoría
    public static function getByCategoria($categoria_id)
    {
        $con = DataBase::connect();
        $stmt = $con->prepare("SELECT * FROM productos WHERE categoria_id = ? AND activo = true");
        $stmt->bind_param("i", $categoria_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $productos = [];
        while ($producto = $result->fetch_object("Producto")) {
            $productos[] = $producto;
        }

        $stmt->close();
        $con->close();
        return $productos;
    }
    // Obtener un producto por su ID
    public static function getById($id)
    {
        $con = DataBase::connect();
        $stmt = $con->prepare("SELECT * FROM productos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $producto = $result->fetch_object("Producto");
        
        $stmt->close();
        $con->close();
        return $producto;
    }
}