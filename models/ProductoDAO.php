<?php
include_once("config/dataBase.php");
include_once("models/Producto.php");

class ProductoDAO
{
    // todos los productos activos
    public static function getAll()
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare("SELECT * FROM productos WHERE activo = true");
        $consulta->execute();
        $resultado = $consulta->get_result();

        $productos = [];
        while ($producto = $resultado->fetch_object("Producto")) {
            $productos[] = $producto;
        }

        $consulta->close();
        $conexion->close();
        return $productos;
    }

    // productos de una categoria
    public static function getByCategoria($categoria_id)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare("SELECT * FROM productos WHERE categoria_id = ? AND activo = true");
        $consulta->bind_param("i", $categoria_id);
        $consulta->execute();
        $resultado = $consulta->get_result();

        $productos = [];
        while ($producto = $resultado->fetch_object("Producto")) {
            $productos[] = $producto;
        }

        $consulta->close();
        $conexion->close();
        return $productos;
    }

    // un producto por su id
    public static function getById($id)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare("SELECT * FROM productos WHERE id = ?");
        $consulta->bind_param("i", $id);
        $consulta->execute();
        $resultado = $consulta->get_result();
        $producto = $resultado->fetch_object("Producto");
        
        $consulta->close();
        $conexion->close();
        return $producto;
    }
}
