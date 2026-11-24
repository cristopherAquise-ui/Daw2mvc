<?php
require_once __DIR__ . '/Oferta.php';

class OfertaCombo extends Oferta
{
    public function calcularDescuento(float $subtotal, array $lineas = []): float
    {
        $precioCombo = (float) $this->valor;

        if ($precioCombo <= 0 || $subtotal <= $precioCombo) {
            return 0.0;
        }

        return round($subtotal - $precioCombo, 2);
    }

    public function etiqueta(): string
    {
        return 'COMBO';
    }
}
