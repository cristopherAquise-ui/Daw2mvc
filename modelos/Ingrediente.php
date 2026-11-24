<?php

class Ingrediente
{
    protected $id;
    protected $nombre;
    protected $precio_extra;
    protected $activo;
    protected $fecha_creacion;

    // Campos que llegan de la relación producto_ingrediente.
    protected $tipo;
    protected $incluido_por_defecto;
    protected $obligatorio;
    protected $cantidad;

    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }
    public function getPrecioExtra() { return $this->precio_extra; }
    public function getActivo() { return $this->activo; }
    public function getFechaCreacion() { return $this->fecha_creacion; }
    public function getTipo() { return $this->tipo; }
    public function getIncluidoPorDefecto() { return $this->incluido_por_defecto; }
    public function getObligatorio() { return $this->obligatorio; }
    public function getCantidad() { return $this->cantidad; }
}
