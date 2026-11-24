<?php
require_once __DIR__ . '/Oferta.php';

class OfertaDescuento extends Oferta
{
    public function calcularDescuento(float $subtotal, array $lineas = []): float
    {
        $porcentaje = (float) $this->valor;
        return round($subtotal * ($porcentaje / 100), 2);
    }

    public function etiqueta(): string
    {
        return '-' . number_format((float) $this->valor, 0, ',', '.') . '%';
    }
}
