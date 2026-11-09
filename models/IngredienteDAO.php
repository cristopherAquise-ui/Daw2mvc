<?php
include_once("config/dataBase.php");
include_once("models/Ingrediente.php");

class IngredienteDAO
{
    // extras que admite un producto
    public static function getExtrasByProducto($producto_id)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare("SELECT i.* 
                FROM ingredientes i 
                INNER JOIN producto_ingrediente pi ON i.id = pi.ingrediente_id 
                WHERE pi.producto_id = ? AND i.activo = true");

        $consulta->bind_param("i", $producto_id);
        $consulta->execute();
        $resultado = $consulta->get_result();

        $extras = [];
        while ($extra = $resultado->fetch_object("Ingrediente")) {
            $extras[] = $extra;
        }

        $consulta->close();
        $conexion->close();
        
        return $extras;
    }
}
