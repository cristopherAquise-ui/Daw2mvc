<?php
require_once __DIR__ . '/Oferta.php';

class OfertaCodigo extends Oferta
{
    public function calcularDescuento(float $subtotal, array $lineas = []): float
    {
        if ($subtotal < (float) $this->importe_minimo) {
            return 0.0;
        }

        $porcentaje = (float) $this->valor;
        return round($subtotal * ($porcentaje / 100), 2);
    }

    public function etiqueta(): string
    {
        return (string) $this->codigo;
    }
}
