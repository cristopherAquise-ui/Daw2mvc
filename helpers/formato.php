<?php
/**
 * Funciones para mostrar precios, textos y fechas.
 */

// Convierte un número en un precio con dos decimales.
function precio($valor): string
{
    return number_format((float) $valor, 2, ',', '.') . ' €';
}

// Prepara el texto para mostrarlo de forma segura en HTML.
function texto($valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

// Muestra una raya cuando no hay ningún dato.
function dato($valor): string
{
    return ($valor === null || $valor === '') ? '—' : texto($valor);
}

// Cambia la fecha al formato día/mes/año.
function fecha($valor, string $formato = 'd/m/Y'): string
{
    $marca = strtotime((string) $valor);
    return $marca ? date($formato, $marca) : '—';
}

function fechaHora($valor): string
{
    return fecha($valor, 'd/m/Y H:i');
}

// Busca el nombre del mes en español y añade el año.
function mesAnio($valor): string
{
    $meses = [
        'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
        'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre',
    ];

    $marca = strtotime((string) $valor);
    return $marca ? $meses[(int) date('n', $marca) - 1] . ' de ' . date('Y', $marca) : '';
}

// Pone en mayúscula la primera letra del estado.
function estadoPedido($estado): string
{
    return ucfirst((string) $estado);
}