<?php

class Ingrediente
{
    protected $id;
    protected $nombre;
    protected $precio_extra;
    protected $activo;

    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }
    public function getPrecioExtra() { return $this->precio_extra; }
    public function getActivo() { return $this->activo; }
}