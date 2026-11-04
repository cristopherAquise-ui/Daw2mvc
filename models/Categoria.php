<?php
class Categoria
{
    protected $id;
    protected $nombre;
    protected $descripcion;
    protected $activa;

    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }
    public function getDescripcion() { return $this->descripcion; }
    public function getActiva() { return $this->activa; }
}