<?php

class Producto
{
    protected $id;
    protected $categoria_id;
    protected $nombre;
    protected $descripcion;
    protected $precio;
    protected $imagen;
    protected $activo;

    public function getId() { return $this->id; }
    public function getCategoriaId() { return $this->categoria_id; }
    public function getNombre() { return $this->nombre; }
    public function getDescripcion() { return $this->descripcion; }
    public function getPrecio() { return $this->precio; }
    public function getImagen() { return $this->imagen; }
    public function getActivo() { return $this->activo; }
}