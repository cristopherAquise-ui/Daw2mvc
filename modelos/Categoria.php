<?php

class Categoria
{
    protected $id;
    protected $nombre;
    protected $descripcion;
    protected $imagen;
    protected $activa;
    protected $orden;
    protected $fecha_creacion;
    protected $fecha_actualizacion;

    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }
    public function getDescripcion() { return $this->descripcion; }
    public function getImagen() { return $this->imagen; }
    public function getActiva() { return $this->activa; }
    public function getOrden() { return $this->orden; }
    public function getFechaCreacion() { return $this->fecha_creacion; }
    public function getFechaActualizacion() { return $this->fecha_actualizacion; }
}
