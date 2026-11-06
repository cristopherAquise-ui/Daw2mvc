<?php

include_once("models/Producto.php");

class Entrantes extends Producto
{
    public function __construct($nombre = null, $talla = null, $precio = null)
    {
        parent::__construct($nombre, $talla, $precio);
    }
}