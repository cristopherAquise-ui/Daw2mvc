<?php
/**
 * Utilidades de base de datos.
 */

/**
 * Vincula parámetros a una consulta preparada de forma dinámica.
 * Necesario porque bind_param() exige referencias y los filtros del
 * panel de administración se construyen en tiempo de ejecución.
 */
function dbBind($stmt, string $types, array $params): void
{
    if ($types === '' || count($params) === 0) {
        return;
    }

    $refs = [];
    foreach ($params as $i => $valor) {
        $refs[$i] = &$params[$i];
    }

    array_unshift($refs, $types);
    call_user_func_array([$stmt, 'bind_param'], $refs);
}
