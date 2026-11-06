<?php
include_once("config/dataBase.php");
include_once("models/Ingrediente.php");

class IngredienteDAO
{
    /**
     * Obtiene todos los extras compatibles con un producto concreto.
     * @param int $producto_id El ID del producto de la carta.
     * @return array Array de objetos Ingrediente.
     */
    public static function getExtrasByProducto($producto_id)
    {
        $con = DataBase::connect();
        $sql = "SELECT i.* 
                FROM ingredientes i 
                INNER JOIN producto_ingrediente pi ON i.id = pi.ingrediente_id 
                WHERE pi.producto_id = ? AND i.activo = true";
                
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $producto_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $extras = [];
        while ($extra = $result->fetch_object("Ingrediente")) {
            $extras[] = $extra;
        }

        $stmt->close();
        $con->close();
        
        return $extras;
    }
}