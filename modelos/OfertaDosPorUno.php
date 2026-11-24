<?php
require_once __DIR__ . '/Oferta.php';

class OfertaDosPorUno extends Oferta
{
    public function calcularDescuento(float $subtotal, array $lineas = []): float
    {
        $descuento = 0.0;

        foreach ($lineas as $linea) {
            $cantidad = (int) ($linea['cantidad'] ?? 0);
            $precio   = (float) ($linea['precio_unitario'] ?? 0);

            if ($cantidad >= 2 && $precio > 0) {
                $descuento += $precio * intdiv($cantidad, 2);
            }
        }

        return round($descuento, 2);
    }

    public function etiqueta(): string
    {
        return '2x1';
    }
}
