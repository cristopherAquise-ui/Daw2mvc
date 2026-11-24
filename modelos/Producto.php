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
    protected $destacado;
    protected $orden;
    protected $iva_porcentaje;
    protected $fecha_creacion;
    protected $fecha_actualizacion;

    public function getId() { return $this->id; }
    public function getCategoriaId() { return $this->categoria_id; }
    public function getNombre() { return $this->nombre; }
    public function getDescripcion() { return $this->descripcion; }
    public function getPrecio() { return $this->precio; }
    public function getImagen() { return $this->imagen; }
    public function getActivo() { return $this->activo; }
    public function getDestacado() { return $this->destacado; }
    public function getOrden() { return $this->orden; }
    public function getIvaPorcentaje() { return $this->iva_porcentaje; }
    public function getFechaCreacion() { return $this->fecha_creacion; }
    public function getFechaActualizacion() { return $this->fecha_actualizacion; }
}
