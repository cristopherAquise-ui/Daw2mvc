<?php
require_once __DIR__ . '/../config/DataBase.php';
require_once __DIR__ . '/../helpers/db.php';
require_once __DIR__ . '/../modelos/Pedido.php';
require_once __DIR__ . '/../modelos/PedidoDetalle.php';
require_once __DIR__ . '/CarritoDAO.php';
require_once __DIR__ . '/OfertaDAO.php';

class PedidoDAO
{
    public static function getByUsuario($usuario_id)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare("SELECT * FROM pedidos WHERE usuario_id = ? ORDER BY fecha_pedido DESC, id DESC");
        $consulta->bind_param('i', $usuario_id);
        $consulta->execute();
        $resultado = $consulta->get_result();

        $pedidos = [];
        while ($pedido = $resultado->fetch_object('Pedido')) {
            $pedidos[] = $pedido;
        }

        $consulta->close();
        $conexion->close();
        return $pedidos;
    }

    public static function getById($id)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare("SELECT * FROM pedidos WHERE id = ? LIMIT 1");
        $consulta->bind_param('i', $id);
        $consulta->execute();
        $resultado = $consulta->get_result();
        $pedido = $resultado->fetch_object('Pedido');

        $consulta->close();
        $conexion->close();
        return $pedido ?: null;
    }

    public static function getUltimoByUsuario($usuario_id)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare(
            "SELECT * FROM pedidos WHERE usuario_id = ? ORDER BY fecha_pedido DESC, id DESC LIMIT 1"
        );
        $consulta->bind_param('i', $usuario_id);
        $consulta->execute();
        $resultado = $consulta->get_result();
        $pedido = $resultado->fetch_object('Pedido');

        $consulta->close();
        $conexion->close();
        return $pedido ?: null;
    }

    public static function getResumenUsuario($usuario_id)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare(
            "SELECT COUNT(*) AS num, COALESCE(SUM(total), 0) AS gastado FROM pedidos WHERE usuario_id = ?"
        );
        $consulta->bind_param('i', $usuario_id);
        $consulta->execute();
        $resumen = $consulta->get_result()->fetch_assoc();
        $consulta->close();

        $consulta = $conexion->prepare(
            "SELECT estado FROM pedidos WHERE usuario_id = ? ORDER BY fecha_pedido DESC, id DESC LIMIT 1"
        );
        $consulta->bind_param('i', $usuario_id);
        $consulta->execute();
        $ultimo = $consulta->get_result()->fetch_assoc();
        $consulta->close();

        $conexion->close();

        return [
            'num'     => (int) ($resumen['num'] ?? 0),
            'gastado' => (float) ($resumen['gastado'] ?? 0),
            'ultimo'  => $ultimo['estado'] ?? null,
        ];
    }

    public static function insert(array $datos)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare(
            "INSERT INTO pedidos
             (usuario_id, estado, subtotal, descuento, base_imponible, iva, total, metodo_pago, estado_pago,
              direccion_entrega, telefono_contacto, notas)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $usuario_id       = (int) ($datos['usuario_id'] ?? 0);
        $estado           = (string) ($datos['estado'] ?? 'pendiente');
        $subtotal         = (float) ($datos['subtotal'] ?? 0);
        $descuento        = (float) ($datos['descuento'] ?? 0);
        $base_imponible   = (float) ($datos['base_imponible'] ?? 0);
        $iva              = (float) ($datos['iva'] ?? 0);
        $total            = (float) ($datos['total'] ?? 0);
        $metodo_pago      = (string) ($datos['metodo_pago'] ?? 'simulado');
        $estado_pago      = (string) ($datos['estado_pago'] ?? 'pendiente');
        $direccion        = (string) ($datos['direccion_entrega'] ?? '');
        $telefono         = (string) ($datos['telefono_contacto'] ?? '');
        $notas            = (string) ($datos['notas'] ?? '');

        $consulta->bind_param(
            'isdddddsssss',
            $usuario_id,
            $estado,
            $subtotal,
            $descuento,
            $base_imponible,
            $iva,
            $total,
            $metodo_pago,
            $estado_pago,
            $direccion,
            $telefono,
            $notas
        );

        $consulta->execute();
        $id = $consulta->insert_id;

        $consulta->close();
        $conexion->close();
        return $id;
    }

    public static function updateEstado($id, $estado, $estado_pago)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare("UPDATE pedidos SET estado = ?, estado_pago = ? WHERE id = ?");
        $consulta->bind_param('ssi', $estado, $estado_pago, $id);
        $consulta->execute();
        $filas = $consulta->affected_rows;

        $consulta->close();
        $conexion->close();
        return $filas >= 0;
    }

    public static function updateTotales($id, $subtotal, $descuento, $base_imponible, $iva, $total)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare(
            "UPDATE pedidos SET subtotal = ?, descuento = ?, base_imponible = ?, iva = ?, total = ? WHERE id = ?"
        );

        $subtotal       = (float) $subtotal;
        $descuento      = (float) $descuento;
        $base_imponible = (float) $base_imponible;
        $iva            = (float) $iva;
        $total          = (float) $total;
        $id             = (int) $id;

        $consulta->bind_param('dddddi', $subtotal, $descuento, $base_imponible, $iva, $total, $id);
        $consulta->execute();
        $filas = $consulta->affected_rows;

        $consulta->close();
        $conexion->close();
        return $filas >= 0;
    }

    public static function getAll(array $filtros = [])
    {
        $where  = [];
        $types  = '';
        $params = [];

        if (isset($filtros['id']) && $filtros['id'] !== '') {
            $where[] = 'id = ?';
            $types .= 'i';
            $params[] = (int) $filtros['id'];
        }

        if (isset($filtros['usuario_id']) && $filtros['usuario_id'] !== '') {
            $where[] = 'usuario_id = ?';
            $types .= 'i';
            $params[] = (int) $filtros['usuario_id'];
        }

        if (!empty($filtros['estado'])) {
            $where[] = 'estado = ?';
            $types .= 's';
            $params[] = $filtros['estado'];
        }

        if (!empty($filtros['estado_pago'])) {
            $where[] = 'estado_pago = ?';
            $types .= 's';
            $params[] = $filtros['estado_pago'];
        }

        if (!empty($filtros['fecha_desde'])) {
            $where[] = 'fecha_pedido >= ?';
            $types .= 's';
            $params[] = $filtros['fecha_desde'] . ' 00:00:00';
        }

        if (!empty($filtros['fecha_hasta'])) {
            $where[] = 'fecha_pedido <= ?';
            $types .= 's';
            $params[] = $filtros['fecha_hasta'] . ' 23:59:59';
        }

        $sql = 'SELECT * FROM pedidos';
        if (count($where) > 0) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        $permitidos = ['id', 'usuario_id', 'estado', 'fecha_pedido', 'total', 'estado_pago'];
        $order = in_array($filtros['order'] ?? '', $permitidos, true) ? $filtros['order'] : 'fecha_pedido';
        $dir   = strtoupper($filtros['dir'] ?? 'DESC') === 'ASC' ? 'ASC' : 'DESC';
        $sql  .= " ORDER BY $order $dir";

        $conexion = DataBase::connect();
        $consulta = $conexion->prepare($sql);
        dbBind($consulta, $types, $params);
        $consulta->execute();
        $resultado = $consulta->get_result();

        $pedidos = [];
        while ($pedido = $resultado->fetch_object('Pedido')) {
            $pedidos[] = $pedido;
        }

        $consulta->close();
        $conexion->close();
        return $pedidos;
    }

    public static function getDetalle($pedido_id)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare("SELECT * FROM pedido_detalle WHERE pedido_id = ? ORDER BY id");
        $consulta->bind_param('i', $pedido_id);
        $consulta->execute();
        $resultado = $consulta->get_result();

        $lineas = [];
        while ($linea = $resultado->fetch_object('PedidoDetalle')) {
            $lineas[] = $linea;
        }

        $consulta->close();
        $conexion->close();
        return $lineas;
    }

    public static function getDetalleById($id)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare("SELECT * FROM pedido_detalle WHERE id = ? LIMIT 1");
        $consulta->bind_param('i', $id);
        $consulta->execute();
        $resultado = $consulta->get_result();
        $linea = $resultado->fetch_object('PedidoDetalle');

        $consulta->close();
        $conexion->close();
        return $linea ?: null;
    }

    /**
     * Devuelve las líneas de un pedido junto con sus ingredientes.
     */
    public static function getDetalleCompleto($pedido_id)
    {
        $lineas = self::getDetalle($pedido_id);
        $resultado = [];

        foreach ($lineas as $linea) {
            $resultado[] = [
                'linea' => $linea,
                'mods'  => self::getIngredientesDetalle($linea->getId()),
            ];
        }

        return $resultado;
    }

    public static function insertDetalle(array $datos)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare(
            "INSERT INTO pedido_detalle
             (pedido_id, producto_id, oferta_id, nombre_producto, precio_unitario, cantidad, descuento, subtotal, iva_porcentaje)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $pedido_id     = (int) ($datos['pedido_id'] ?? 0);
        $producto_id   = !empty($datos['producto_id']) ? (int) $datos['producto_id'] : null;
        $oferta_id     = !empty($datos['oferta_id']) ? (int) $datos['oferta_id'] : null;
        $nombre        = (string) ($datos['nombre_producto'] ?? '');
        $precio        = (float) ($datos['precio_unitario'] ?? 0);
        $cantidad      = (int) ($datos['cantidad'] ?? 1);
        $descuento     = (float) ($datos['descuento'] ?? 0);
        $subtotal      = (float) ($datos['subtotal'] ?? 0);
        $iva           = (float) ($datos['iva_porcentaje'] ?? 10);

        $consulta->bind_param(
            'iiisdiddd',
            $pedido_id,
            $producto_id,
            $oferta_id,
            $nombre,
            $precio,
            $cantidad,
            $descuento,
            $subtotal,
            $iva
        );

        $consulta->execute();
        $id = $consulta->insert_id;

        $consulta->close();
        $conexion->close();
        return $id;
    }

    public static function updateDetalle($id, $cantidad, $precio_unitario, $descuento, $subtotal, $iva_porcentaje)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare(
            "UPDATE pedido_detalle
             SET cantidad = ?, precio_unitario = ?, descuento = ?, subtotal = ?, iva_porcentaje = ?
             WHERE id = ?"
        );

        $cantidad      = (int) $cantidad;
        $precio        = (float) $precio_unitario;
        $descuento     = (float) $descuento;
        $subtotal      = (float) $subtotal;
        $iva           = (float) $iva_porcentaje;
        $id            = (int) $id;

        $consulta->bind_param('iddddi', $cantidad, $precio, $descuento, $subtotal, $iva, $id);
        $consulta->execute();
        $filas = $consulta->affected_rows;

        $consulta->close();
        $conexion->close();
        return $filas >= 0;
    }

    public static function deleteDetalle($id)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare("DELETE FROM pedido_detalle WHERE id = ?");
        $consulta->bind_param('i', $id);
        $consulta->execute();
        $filas = $consulta->affected_rows;

        $consulta->close();
        $conexion->close();
        return $filas > 0;
    }

    public static function getIngredientesDetalle($pedido_detalle_id)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare(
            "SELECT * FROM pedido_detalle_ingrediente WHERE pedido_detalle_id = ? ORDER BY id"
        );
        $consulta->bind_param('i', $pedido_detalle_id);
        $consulta->execute();
        $resultado = $consulta->get_result();

        $ingredientes = [];
        while ($fila = $resultado->fetch_assoc()) {
            $ingredientes[] = $fila;
        }

        $consulta->close();
        $conexion->close();
        return $ingredientes;
    }

    public static function insertIngredienteDetalle(array $datos)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare(
            "INSERT INTO pedido_detalle_ingrediente
             (pedido_detalle_id, ingrediente_id, nombre_ingrediente, precio_extra, cantidad, accion)
             VALUES (?, ?, ?, ?, ?, ?)"
        );

        $detalle_id   = (int) ($datos['pedido_detalle_id'] ?? 0);
        $ingrediente  = !empty($datos['ingrediente_id']) ? (int) $datos['ingrediente_id'] : null;
        $nombre       = (string) ($datos['nombre_ingrediente'] ?? '');
        $precio       = (float) ($datos['precio_extra'] ?? 0);
        $cantidad     = (float) ($datos['cantidad'] ?? 1);
        $accion       = (string) ($datos['accion'] ?? 'incluido');

        $consulta->bind_param('iisdds', $detalle_id, $ingrediente, $nombre, $precio, $cantidad, $accion);
        $consulta->execute();
        $id = $consulta->insert_id;

        $consulta->close();
        $conexion->close();
        return $id;
    }

    public static function deleteIngredientesDetalle($pedido_detalle_id)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare("DELETE FROM pedido_detalle_ingrediente WHERE pedido_detalle_id = ?");
        $consulta->bind_param('i', $pedido_detalle_id);
        $consulta->execute();
        $consulta->close();
        $conexion->close();
    }

    public static function getAllJson(array $filtros = [])
    {
        return array_map([self::class, 'toArray'], self::getAll($filtros));
    }

    public static function getByIdJson($id)
    {
        $pedido = self::getById($id);
        return $pedido ? self::toArray($pedido) : null;
    }

    public static function toArray(Pedido $pedido): array
    {
        return [
            'id'               => (int) $pedido->getId(),
            'usuario_id'       => (int) $pedido->getUsuarioId(),
            'estado'           => $pedido->getEstado(),
            'fecha_pedido'     => $pedido->getFechaPedido(),
            'subtotal'         => (float) $pedido->getSubtotal(),
            'descuento'        => (float) $pedido->getDescuento(),
            'base_imponible'   => (float) $pedido->getBaseImponible(),
            'iva'              => (float) $pedido->getIva(),
            'total'            => (float) $pedido->getTotal(),
            'metodo_pago'      => $pedido->getMetodoPago(),
            'estado_pago'      => $pedido->getEstadoPago(),
            'direccion_entrega'=> $pedido->getDireccionEntrega(),
            'telefono_contacto'=> $pedido->getTelefonoContacto(),
            'notas'            => $pedido->getNotas(),
        ];
    }

    /**
     * Convierte el carrito activo en un pedido.
     *
     * Todo se hace en una transacción: si falla una línea, no se crea
     * un pedido a medias.
     */
    public static function confirmar($usuario_id, array $datos): int
    {
        $items = CarritoDAO::obtenerContenido();

        if (count($items) === 0) {
            throw new Exception('El carrito está vacío.');
        }

        $carrito_id = CarritoDAO::obtenerOCrearCarrito();
        $totales    = CarritoDAO::totales($items);

        $codigo          = trim((string) ($datos['codigo'] ?? ''));
        $descuentoCodigo = 0.0;

        if ($codigo !== '') {
            $resultadoCodigo = Oferta::descuentoCodigo($codigo, $totales['total']);

            if (!$resultadoCodigo['valido']) {
                throw new Exception($resultadoCodigo['mensaje']);
            }

            $descuentoCodigo = (float) $resultadoCodigo['descuento'];
        }

        $total  = max(0, $totales['total'] - $descuentoCodigo);
        $factor = $totales['total'] > 0 ? ($total / $totales['total']) : 1;

        $baseImponible = 0.0;
        $ivaTotal      = 0.0;

        foreach ($items as $item) {
            $baseImponible += (float) $item['base_linea'] * $factor;
            $ivaTotal      += (float) $item['iva_linea'] * $factor;
        }

        $subtotal  = $totales['subtotal'];
        $descuento = $totales['descuento'] + $descuentoCodigo;

        $metodoPago = (string) ($datos['metodo_pago'] ?? 'simulado');
        $metodosValidos = ['simulado', 'tarjeta', 'efectivo', 'paypal', 'transferencia'];
        if (!in_array($metodoPago, $metodosValidos, true)) {
            $metodoPago = 'simulado';
        }

        $estadoPago = in_array($metodoPago, ['efectivo', 'transferencia'], true) ? 'pendiente' : 'pagado';

        $conexion = DataBase::connect();
        $conexion->begin_transaction();

        try {
            $stmt = $conexion->prepare(
                "INSERT INTO pedidos
                 (usuario_id, estado, subtotal, descuento, base_imponible, iva, total, metodo_pago, estado_pago,
                  direccion_entrega, telefono_contacto, notas)
                 VALUES (?, 'pendiente', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );

            $direccion = (string) ($datos['direccion_entrega'] ?? '');
            $telefono  = (string) ($datos['telefono_contacto'] ?? '');
            $notas     = (string) ($datos['notas'] ?? '');

            $stmt->bind_param(
                'idddddsssss',
                $usuario_id,
                $subtotal,
                $descuento,
                $baseImponible,
                $ivaTotal,
                $total,
                $metodoPago,
                $estadoPago,
                $direccion,
                $telefono,
                $notas
            );

            $stmt->execute();
            $pedido_id = (int) $conexion->insert_id;
            $stmt->close();

            $stmtLinea = $conexion->prepare(
                "INSERT INTO pedido_detalle
                 (pedido_id, producto_id, oferta_id, nombre_producto, precio_unitario, cantidad, descuento, subtotal, iva_porcentaje)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );

            $stmtIng = $conexion->prepare(
                "INSERT INTO pedido_detalle_ingrediente
                 (pedido_detalle_id, ingrediente_id, nombre_ingrediente, precio_extra, cantidad, accion)
                 VALUES (?, ?, ?, ?, ?, ?)"
            );

            foreach ($items as $item) {
                $producto_id = !empty($item['producto_id']) ? (int) $item['producto_id'] : null;
                $oferta_id   = !empty($item['oferta_id']) ? (int) $item['oferta_id'] : null;
                $nombre      = (string) $item['nombre_producto'];
                $precio      = (float) $item['precio_unitario_con_extras'];
                $cantidad    = (int) $item['cantidad'];
                $descLinea   = (float) $item['descuento'] * $factor;
                $subLinea    = (float) $item['total_linea'] * $factor;
                $ivaLinea    = (float) $item['iva_porcentaje'];

                $stmtLinea->bind_param(
                    'iiisdiddd',
                    $pedido_id,
                    $producto_id,
                    $oferta_id,
                    $nombre,
                    $precio,
                    $cantidad,
                    $descLinea,
                    $subLinea,
                    $ivaLinea
                );

                $stmtLinea->execute();
                $detalle_id = (int) $conexion->insert_id;

                $mods = array_merge(
                    array_map(function ($ing) { return [$ing, 'incluido']; }, $item['incluidos'] ?? []),
                    array_map(function ($ing) { return [$ing, 'quitado']; }, $item['quitados'] ?? []),
                    array_map(function ($ing) { return [$ing, 'añadido']; }, $item['extras'] ?? [])
                );

                foreach ($mods as $mod) {
                    $ing    = $mod[0];
                    $accion = $mod[1];
                    $ingId  = !empty($ing['ingrediente_id']) ? (int) $ing['ingrediente_id'] : null;
                    $ingNom = (string) $ing['nombre_ingrediente'];
                    $ingPre = (float) $ing['precio_extra'];
                    $ingCan = (float) ($ing['cantidad'] ?? 1);

                    $stmtIng->bind_param('iisdds', $detalle_id, $ingId, $ingNom, $ingPre, $ingCan, $accion);
                    $stmtIng->execute();
                }
            }

            $stmtLinea->close();
            $stmtIng->close();

            $conexion->commit();
        } catch (Throwable $e) {
            $conexion->rollback();
            $conexion->close();
            throw $e;
        }

        $conexion->close();
        CarritoDAO::marcarConvertido($carrito_id);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['ultimo_pedido_id'] = $pedido_id;

        return $pedido_id;
    }

    /**
     * Recalcula los totales de un pedido a partir de sus líneas.
     */
    public static function recalcular($pedido_id): array
    {
        $lineas = self::getDetalle($pedido_id);

        $subtotal  = 0.0;
        $descuento = 0.0;
        $base      = 0.0;
        $iva       = 0.0;
        $total     = 0.0;

        foreach ($lineas as $linea) {
            $precio    = (float) $linea->getPrecioUnitario();
            $cantidad  = (int) $linea->getCantidad();
            $descLinea = (float) $linea->getDescuento();
            $ivaLinea  = (float) $linea->getIvaPorcentaje();

            $totalLinea = max(0, ($precio * $cantidad) - $descLinea);
            $baseLinea  = $totalLinea / (1 + ($ivaLinea / 100));

            self::updateDetalle(
                $linea->getId(),
                $cantidad,
                $precio,
                $descLinea,
                $totalLinea,
                $ivaLinea
            );

            $subtotal  += $precio * $cantidad;
            $descuento += $descLinea;
            $total     += $totalLinea;
            $base      += $baseLinea;
            $iva       += $totalLinea - $baseLinea;
        }

        $totales = [
            'subtotal'       => round($subtotal, 2),
            'descuento'      => round($descuento, 2),
            'base_imponible' => round($base, 2),
            'iva'            => round($iva, 2),
            'total'          => round($total, 2),
        ];

        self::updateTotales(
            $pedido_id,
            $totales['subtotal'],
            $totales['descuento'],
            $totales['base_imponible'],
            $totales['iva'],
            $totales['total']
        );

        return $totales;
    }
}
