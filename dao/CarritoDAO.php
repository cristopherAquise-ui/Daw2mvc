<?php
require_once __DIR__ . '/../config/DataBase.php';
require_once __DIR__ . '/ProductoDAO.php';
require_once __DIR__ . '/IngredienteDAO.php';

class CarritoDAO
{
    /**
     * Inicia la sesión si todavía no está activa.
     */
    private static function iniciarSesion(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Id del usuario logueado, o null si es un invitado.
     */
    private static function usuarioId(): ?int
    {
        $usuario = $_SESSION['usuario'] ?? null;
        return $usuario ? (int) $usuario['id'] : null;
    }

    /**
     * Devuelve el carrito activo de la sesión actual, creándolo si no existe.
     */
    public static function obtenerOCrearCarrito()
    {
        self::iniciarSesion();

        $sesion_id  = session_id();
        $usuario_id = self::usuarioId();
        $conexion   = DataBase::connect();

        $consulta = $conexion->prepare("SELECT id FROM carrito WHERE sesion_id = ? AND estado = 'activo' LIMIT 1");
        $consulta->bind_param('s', $sesion_id);
        $consulta->execute();
        $resultado = $consulta->get_result();

        if ($fila = $resultado->fetch_assoc()) {
            $carrito_id = (int) $fila['id'];
            $consulta->close();

            // Si el usuario acaba de iniciar sesión, asociamos su carrito.
            if ($usuario_id !== null) {
                $update = $conexion->prepare("UPDATE carrito SET usuario_id = ? WHERE id = ?");
                $update->bind_param('ii', $usuario_id, $carrito_id);
                $update->execute();
                $update->close();
            }

            $conexion->close();
            return $carrito_id;
        }

        $consulta->close();

        $insert = $conexion->prepare("INSERT INTO carrito (usuario_id, sesion_id, estado) VALUES (?, ?, 'activo')");
        $insert->bind_param('is', $usuario_id, $sesion_id);
        $insert->execute();
        $carrito_id = (int) $insert->insert_id;

        $insert->close();
        $conexion->close();
        return $carrito_id;
    }

    /**
     * Asocia el carrito activo de la sesión al usuario que inicia sesión.
     */
    public static function vincularUsuario($usuario_id)
    {
        self::iniciarSesion();
        $sesion_id = session_id();

        $conexion = DataBase::connect();
        $consulta = $conexion->prepare("UPDATE carrito SET usuario_id = ? WHERE sesion_id = ? AND estado = 'activo'");
        $consulta->bind_param('is', $usuario_id, $sesion_id);
        $consulta->execute();
        $consulta->close();
        $conexion->close();
    }

    /**
     * Añade un producto al carrito.
     *
     * @param int   $producto_id
     * @param array $extras_ids   Ingredientes extra añadidos.
     * @param array $quitados_ids Ingredientes base que el cliente quita.
     * @param int   $oferta_id    Oferta aplicada, si procede.
     * @param float $descuento    Descuento absoluto de la línea.
     */
    public static function agregarProducto($producto_id, $extras_ids = [], $quitados_ids = [], $oferta_id = null, $descuento = 0)
    {
        $producto = ProductoDAO::getById($producto_id);
        if (!$producto) {
            return false;
        }

        $extras_ids   = array_values(array_unique(array_map('intval', (array) $extras_ids)));
        $quitados_ids = array_values(array_unique(array_map('intval', (array) $quitados_ids)));

        $base   = IngredienteDAO::getBaseByProducto($producto_id);
        $extras = IngredienteDAO::getExtrasByProducto($producto_id);

        $baseValidos = [];
        foreach ($base as $ingrediente) {
            $baseValidos[(int) $ingrediente->getId()] = $ingrediente;
        }

        $extrasValidos = [];
        foreach ($extras as $ingrediente) {
            $extrasValidos[(int) $ingrediente->getId()] = $ingrediente;
        }

        $extras_seleccionados = [];
        $total_extras = 0.0;
        foreach ($extras_ids as $extra_id) {
            if (isset($extrasValidos[$extra_id])) {
                $extras_seleccionados[] = $extrasValidos[$extra_id];
                $total_extras += (float) $extrasValidos[$extra_id]->getPrecioExtra();
            }
        }

        $quitados_seleccionados = [];
        foreach ($quitados_ids as $quitado_id) {
            if (isset($baseValidos[$quitado_id])) {
                $quitados_seleccionados[] = $baseValidos[$quitado_id];
            }
        }

        $precio_unitario = (float) $producto->getPrecio();
        $cantidad        = 1;
        $descuento       = max(0, (float) $descuento);
        $subtotal        = max(0, (($precio_unitario + $total_extras) * $cantidad) - $descuento);
        $iva             = (float) $producto->getIvaPorcentaje();
        if ($iva <= 0) {
            $iva = 10.0;
        }

        $carrito_id = self::obtenerOCrearCarrito();
        $conexion   = DataBase::connect();

        $stmt = $conexion->prepare(
            "INSERT INTO carrito_detalle
             (carrito_id, producto_id, oferta_id, nombre_producto, precio_unitario, cantidad, descuento, subtotal, iva_porcentaje)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $nombre_producto = $producto->getNombre();
        $oferta_id       = $oferta_id !== null ? (int) $oferta_id : null;

        $stmt->bind_param(
            'iiisdiddd',
            $carrito_id,
            $producto_id,
            $oferta_id,
            $nombre_producto,
            $precio_unitario,
            $cantidad,
            $descuento,
            $subtotal,
            $iva
        );

        $stmt->execute();
        $detalle_id = (int) $stmt->insert_id;
        $stmt->close();

        // Guardamos los ingredientes base: incluidos o quitados.
        $stmtIng = $conexion->prepare(
            "INSERT INTO carrito_detalle_ingrediente
             (carrito_detalle_id, ingrediente_id, nombre_ingrediente, precio_extra, cantidad, accion)
             VALUES (?, ?, ?, 0, 1, ?)"
        );

        foreach ($baseValidos as $ingrediente) {
            $ing_id     = (int) $ingrediente->getId();
            $ing_nombre = $ingrediente->getNombre();
            $accion     = in_array($ing_id, $quitados_ids, true) ? 'quitado' : 'incluido';
            $stmtIng->bind_param('iiss', $detalle_id, $ing_id, $ing_nombre, $accion);
            $stmtIng->execute();
        }

        $stmtIng->close();

        // Guardamos los extras seleccionados.
        if (count($extras_seleccionados) > 0) {
            $stmtExtra = $conexion->prepare(
                "INSERT INTO carrito_detalle_ingrediente
                 (carrito_detalle_id, ingrediente_id, nombre_ingrediente, precio_extra, cantidad, accion)
                 VALUES (?, ?, ?, ?, 1, 'añadido')"
            );

            foreach ($extras_seleccionados as $ingrediente) {
                $ing_id     = (int) $ingrediente->getId();
                $ing_nombre = $ingrediente->getNombre();
                $ing_precio = (float) $ingrediente->getPrecioExtra();
                $stmtExtra->bind_param('iisd', $detalle_id, $ing_id, $ing_nombre, $ing_precio);
                $stmtExtra->execute();
            }

            $stmtExtra->close();
        }

        $conexion->close();
        return true;
    }

    /**
     * Añade una oferta al carrito.
     *
     * Las ofertas de tipo descuento/2x1/combo se convierten en una línea
     * con su propio precio y descuento. Las de tipo código se aplican en
     * el checkout, no se añaden como producto.
     */
    public static function agregarOferta($oferta, $producto_id = null, $cantidad = 1)
    {
        $cantidad = max(1, (int) $cantidad);
        $producto = $producto_id ? ProductoDAO::getById($producto_id) : null;

        $precio_unitario = $producto ? (float) $producto->getPrecio() : 0.0;
        $iva             = $producto ? (float) $producto->getIvaPorcentaje() : 10.0;
        if ($iva <= 0) {
            $iva = 10.0;
        }

        $descuento = 0.0;
        $subtotal  = 0.0;

        switch ($oferta->getTipo()) {
            case '2x1':
                if (!$producto) {
                    return false;
                }
                $descuento = $precio_unitario * intdiv($cantidad, 2);
                $subtotal  = ($precio_unitario * $cantidad) - $descuento;
                break;

            case 'combo':
                $subtotal = (float) $oferta->getValor();
                $cantidad = 1;
                break;

            case 'codigo':
                // Los códigos se canjean en el checkout.
                return false;

            case 'descuento':
            default:
                if ($producto) {
                    $descuento = round($precio_unitario * ((float) $oferta->getValor() / 100), 2);
                    $subtotal  = max(0, ($precio_unitario * $cantidad) - $descuento);
                } else {
                    $subtotal = (float) $oferta->getValor();
                }
                break;
        }

        $carrito_id = self::obtenerOCrearCarrito();
        $conexion   = DataBase::connect();

        $stmt = $conexion->prepare(
            "INSERT INTO carrito_detalle
             (carrito_id, producto_id, oferta_id, nombre_producto, precio_unitario, cantidad, descuento, subtotal, iva_porcentaje)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $nombre_producto = $oferta->getNombre();
        $oferta_id       = (int) $oferta->getId();
        $producto_id     = $producto ? (int) $producto->getId() : null;

        $stmt->bind_param(
            'iiisdiddd',
            $carrito_id,
            $producto_id,
            $oferta_id,
            $nombre_producto,
            $precio_unitario,
            $cantidad,
            $descuento,
            $subtotal,
            $iva
        );

        $stmt->execute();
        $detalle_id = (int) $stmt->insert_id;
        $stmt->close();

        // Si la oferta va ligada a un producto, guardamos sus ingredientes base.
        if ($producto) {
            $base = IngredienteDAO::getBaseByProducto($producto->getId());
            if (count($base) > 0) {
                $stmtIng = $conexion->prepare(
                    "INSERT INTO carrito_detalle_ingrediente
                     (carrito_detalle_id, ingrediente_id, nombre_ingrediente, precio_extra, cantidad, accion)
                     VALUES (?, ?, ?, 0, 1, 'incluido')"
                );

                foreach ($base as $ingrediente) {
                    $ing_id     = (int) $ingrediente->getId();
                    $ing_nombre = $ingrediente->getNombre();
                    $stmtIng->bind_param('iis', $detalle_id, $ing_id, $ing_nombre);
                    $stmtIng->execute();
                }

                $stmtIng->close();
            }
        }

        $conexion->close();
        return true;
    }

    /**
     * Devuelve las líneas del carrito con sus ingredientes y totales.
     */
    public static function obtenerContenido()
    {
        self::iniciarSesion();
        $carrito_id = self::obtenerOCrearCarrito();

        $conexion = DataBase::connect();
        $consulta = $conexion->prepare(
            "SELECT cd.*, p.imagen, p.iva_porcentaje AS producto_iva
             FROM carrito_detalle cd
             LEFT JOIN productos p ON cd.producto_id = p.id
             WHERE cd.carrito_id = ?
             ORDER BY cd.id ASC"
        );

        $consulta->bind_param('i', $carrito_id);
        $consulta->execute();
        $resultado = $consulta->get_result();

        $items = [];

        $stmtIng = $conexion->prepare(
            "SELECT * FROM carrito_detalle_ingrediente WHERE carrito_detalle_id = ? ORDER BY id"
        );

        while ($fila = $resultado->fetch_assoc()) {
            $stmtIng->bind_param('i', $fila['id']);
            $stmtIng->execute();
            $resIng = $stmtIng->get_result();

            $incluidos = [];
            $quitados  = [];
            $extras    = [];
            $extras_unidad = 0.0;

            while ($ing = $resIng->fetch_assoc()) {
                if ($ing['accion'] === 'quitado') {
                    $quitados[] = $ing;
                } elseif ($ing['accion'] === 'añadido') {
                    $extras[] = $ing;
                    $extras_unidad += (float) $ing['precio_extra'];
                } else {
                    $incluidos[] = $ing;
                }
            }

            $cantidad          = (int) $fila['cantidad'];
            $precio_unitario   = (float) $fila['precio_unitario'];
            $descuento         = (float) $fila['descuento'];
            $precio_con_extras = $precio_unitario + $extras_unidad;
            $subtotal_linea    = max(0, ($precio_con_extras * $cantidad) - $descuento);
            $iva               = (float) ($fila['iva_porcentaje'] ?? 0);
            if ($iva <= 0) {
                $iva = (float) ($fila['producto_iva'] ?? 10);
            }
            if ($iva <= 0) {
                $iva = 10.0;
            }

            $base_linea = $subtotal_linea / (1 + ($iva / 100));
            $iva_linea  = $subtotal_linea - $base_linea;

            $fila['incluidos']               = $incluidos;
            $fila['quitados']                = $quitados;
            $fila['extras']                  = $extras;
            $fila['precio_unitario_con_extras'] = $precio_con_extras;
            $fila['subtotal']                = $subtotal_linea;
            $fila['base_linea']              = $base_linea;
            $fila['iva_linea']               = $iva_linea;
            $fila['total_linea']             = $subtotal_linea;
            $fila['iva_porcentaje']          = $iva;

            $items[] = $fila;
        }

        $stmtIng->close();
        $consulta->close();
        $conexion->close();

        return $items;
    }

    public static function cambiarCantidad($detalle_id, $delta)
    {
        self::iniciarSesion();
        $sesion_id = session_id();

        $conexion = DataBase::connect();
        $consulta = $conexion->prepare(
            "SELECT cd.cantidad, cd.precio_unitario, cd.descuento, cd.iva_porcentaje,
                    COALESCE(SUM(CASE WHEN cdi.accion = 'añadido' THEN cdi.precio_extra ELSE 0 END), 0) AS extras_unidad
             FROM carrito_detalle cd
             INNER JOIN carrito c ON cd.carrito_id = c.id
             LEFT JOIN carrito_detalle_ingrediente cdi ON cd.id = cdi.carrito_detalle_id
             WHERE cd.id = ? AND c.sesion_id = ? AND c.estado = 'activo'
             GROUP BY cd.id"
        );

        $consulta->bind_param('is', $detalle_id, $sesion_id);
        $consulta->execute();
        $datos = $consulta->get_result()->fetch_assoc();
        $consulta->close();

        if (!$datos) {
            $conexion->close();
            return false;
        }

        $nueva_cantidad = (int) $datos['cantidad'] + (int) $delta;

        if ($nueva_cantidad <= 0) {
            $conexion->close();
            self::eliminarItem($detalle_id);
            return true;
        }

        $precio_con_extras = (float) $datos['precio_unitario'] + (float) $datos['extras_unidad'];
        $descuento         = (float) $datos['descuento'];
        $subtotal          = max(0, ($precio_con_extras * $nueva_cantidad) - $descuento);

        $update = $conexion->prepare("UPDATE carrito_detalle SET cantidad = ?, subtotal = ? WHERE id = ?");
        $update->bind_param('idi', $nueva_cantidad, $subtotal, $detalle_id);
        $update->execute();
        $update->close();

        $conexion->close();
        return true;
    }

    public static function eliminarItem($detalle_id)
    {
        self::iniciarSesion();
        $sesion_id = session_id();

        $conexion = DataBase::connect();
        $consulta = $conexion->prepare(
            "DELETE cd FROM carrito_detalle cd
             INNER JOIN carrito c ON cd.carrito_id = c.id
             WHERE cd.id = ? AND c.sesion_id = ? AND c.estado = 'activo'"
        );

        $consulta->bind_param('is', $detalle_id, $sesion_id);
        $consulta->execute();
        $filas = $consulta->affected_rows;

        $consulta->close();
        $conexion->close();
        return $filas > 0;
    }

    public static function contarItems()
    {
        self::iniciarSesion();
        $sesion_id = session_id();

        $conexion = DataBase::connect();
        $consulta = $conexion->prepare(
            "SELECT COALESCE(SUM(cd.cantidad), 0) AS total
             FROM carrito_detalle cd
             INNER JOIN carrito c ON cd.carrito_id = c.id
             WHERE c.sesion_id = ? AND c.estado = 'activo'"
        );

        $consulta->bind_param('s', $sesion_id);
        $consulta->execute();
        $fila = $consulta->get_result()->fetch_assoc();

        $consulta->close();
        $conexion->close();

        return (int) ($fila['total'] ?? 0);
    }

    public static function vaciar()
    {
        self::iniciarSesion();
        $sesion_id = session_id();

        $conexion = DataBase::connect();
        $consulta = $conexion->prepare(
            "DELETE cd FROM carrito_detalle cd
             INNER JOIN carrito c ON cd.carrito_id = c.id
             WHERE c.sesion_id = ? AND c.estado = 'activo'"
        );

        $consulta->bind_param('s', $sesion_id);
        $consulta->execute();
        $consulta->close();
        $conexion->close();
    }

    public static function marcarConvertido($carrito_id)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare("UPDATE carrito SET estado = 'convertido' WHERE id = ?");
        $consulta->bind_param('i', $carrito_id);
        $consulta->execute();
        $consulta->close();
        $conexion->close();
    }

    /**
     * Calcula los totales de una lista de líneas.
     */
    public static function totales(array $items): array
    {
        $subtotal = 0.0;
        $base     = 0.0;
        $iva      = 0.0;
        $total    = 0.0;
        $descuento = 0.0;

        foreach ($items as $item) {
            $subtotal  += (float) ($item['precio_unitario_con_extras'] ?? 0) * (int) ($item['cantidad'] ?? 0);
            $descuento += (float) ($item['descuento'] ?? 0);
            $base      += (float) ($item['base_linea'] ?? 0);
            $iva       += (float) ($item['iva_linea'] ?? 0);
            $total     += (float) ($item['total_linea'] ?? 0);
        }

        return [
            'subtotal'       => round($subtotal, 2),
            'descuento'      => round($descuento, 2),
            'base_imponible' => round($base, 2),
            'iva'            => round($iva, 2),
            'total'          => round($total, 2),
        ];
    }
}
