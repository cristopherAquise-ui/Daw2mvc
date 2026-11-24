<?php

class PedidoDetalle
{
    protected $id;
    protected $pedido_id;
    protected $producto_id;
    protected $oferta_id;
    protected $nombre_producto;
    protected $precio_unitario;
    protected $cantidad;
    protected $descuento;
    protected $subtotal;
    protected $iva_porcentaje;

    public function getId() { return $this->id; }
    public function getPedidoId() { return $this->pedido_id; }
    public function getProductoId() { return $this->producto_id; }
    public function getOfertaId() { return $this->oferta_id; }
    public function getNombreProducto() { return $this->nombre_producto; }
    public function getPrecioUnitario() { return $this->precio_unitario; }
    public function getCantidad() { return $this->cantidad; }
    public function getDescuento() { return $this->descuento; }
    public function getSubtotal() { return $this->subtotal; }
    public function getIvaPorcentaje() { return $this->iva_porcentaje; }
}
