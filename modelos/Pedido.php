<?php

class Pedido
{
    protected $id;
    protected $usuario_id;
    protected $estado;
    protected $fecha_pedido;
    protected $subtotal;
    protected $descuento;
    protected $base_imponible;
    protected $iva;
    protected $total;
    protected $metodo_pago;
    protected $estado_pago;
    protected $direccion_entrega;
    protected $telefono_contacto;
    protected $notas;
    protected $fecha_actualizacion;

    public function getId() { return $this->id; }
    public function getUsuarioId() { return $this->usuario_id; }
    public function getEstado() { return $this->estado; }
    public function getFechaPedido() { return $this->fecha_pedido; }
    public function getSubtotal() { return $this->subtotal; }
    public function getDescuento() { return $this->descuento; }
    public function getBaseImponible() { return $this->base_imponible; }
    public function getIva() { return $this->iva; }
    public function getTotal() { return $this->total; }
    public function getMetodoPago() { return $this->metodo_pago; }
    public function getEstadoPago() { return $this->estado_pago; }
    public function getDireccionEntrega() { return $this->direccion_entrega; }
    public function getTelefonoContacto() { return $this->telefono_contacto; }
    public function getNotas() { return $this->notas; }
    public function getFechaActualizacion() { return $this->fecha_actualizacion; }
}
