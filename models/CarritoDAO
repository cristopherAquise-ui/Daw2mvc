<?php
include_once("config/dataBase.php");
include_once("models/ProductoDAO.php");
include_once("models/IngredienteDAO.php");

class CarritoDAO
{
    private static function iniciarSesion()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function obtenerOCrearCarrito()
    {
        self::iniciarSesion();
        $sesion_id = session_id();
        $usuario_id = isset($_SESSION['usuario']) ? $_SESSION['usuario']->getId() : null;

        $conexion = DataBase::connect();
        $consulta = $conexion->prepare("SELECT id FROM carrito WHERE sesion_id = ? AND estado = 'activo' LIMIT 1");
        $consulta->bind_param("s", $sesion_id);
        $consulta->execute();
        $resultado = $consulta->get_result();

        if ($fila = $resultado->fetch_assoc()) {
            $carrito_id = (int)$fila['id'];
            $consulta->close();
            $conexion->close();
            return $carrito_id;
        }
        $consulta->close();

        $insert = $conexion->prepare("INSERT INTO carrito (usuario_id, sesion_id, estado) VALUES (?, ?, 'activo')");
        $insert->bind_param("is", $usuario_id, $sesion_id);
        $insert->execute();
        $carrito_id = $insert->insert_id;

        $insert->close();
        $conexion->close();
        return $carrito_id;
    }

    // añade extras
    public static function agregarProducto($producto_id, $extras_ids = [])
    {
        $producto = ProductoDAO::getById($producto_id);
        if (!$producto) {
            return false;
        }

        $carrito_id = self::obtenerOCrearCarrito();
        $precio_unitario = (float)$producto->getPrecio();
        $total_extras = 0.0;
        $extras_validos = [];

        foreach ($extras_ids as $extra_id) {
            $ingrediente = IngredienteDAO::getById((int)$extra_id);
            if ($ingrediente) {
                $total_extras += (float)$ingrediente->getPrecioExtra();
                $extras_validos[] = $ingrediente;
            }
        }

        $cantidad = 1;
        $subtotal = ($precio_unitario + $total_extras) * $cantidad;
        $nombre_producto = $producto->getNombre();

        $conexion = DataBase::connect();
        $stmt = $conexion->prepare("
            INSERT INTO carrito_detalle (carrito_id, producto_id, nombre_producto, precio_unitario, cantidad, subtotal)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("iisdid", $carrito_id, $producto_id, $nombre_producto, $precio_unitario, $cantidad, $subtotal);
        $stmt->execute();
        $detalle_id = $stmt->insert_id;
        $stmt->close();

        if (!empty($extras_validos)) {
            $stmtExtra = $conexion->prepare("
                INSERT INTO carrito_detalle_ingrediente (carrito_detalle_id, ingrediente_id, nombre_ingrediente, precio_extra, cantidad, accion)
                VALUES (?, ?, ?, ?, 1, 'añadido')
            ");
            foreach ($extras_validos as $extra) {
                $ing_id = $extra->getId();
                $ing_nombre = $extra->getNombre();
                $ing_precio = (float)$extra->getPrecioExtra();
                $stmtExtra->bind_param("iisd", $detalle_id, $ing_id, $ing_nombre, $ing_precio);
                $stmtExtra->execute();
            }
            $stmtExtra->close();
        }

        $conexion->close();
        return true;
    }

    public static function obtenerContenido()
    {
        self::iniciarSesion();
        $sesion_id = session_id();
        $conexion = DataBase::connect();

        $consulta = $conexion->prepare("
            SELECT cd.*, p.imagen
            FROM carrito_detalle cd
            INNER JOIN carrito c ON cd.carrito_id = c.id
            LEFT JOIN productos p ON cd.producto_id = p.id
            WHERE c.sesion_id = ? AND c.estado = 'activo'
            ORDER BY cd.id ASC
        ");
        $consulta->bind_param("s", $sesion_id);
        $consulta->execute();
        $resultado = $consulta->get_result();

        $items = [];
        $stmtExtras = $conexion->prepare("SELECT * FROM carrito_detalle_ingrediente WHERE carrito_detalle_id = ?");

        while ($row = $resultado->fetch_assoc()) {
            $stmtExtras->bind_param("i", $row['id']);
            $stmtExtras->execute();
            $resExtras = $stmtExtras->get_result();

            $row['extras'] = [];
            while ($extra = $resExtras->fetch_assoc()) {
                $row['extras'][] = $extra;
            }
            $items[] = $row;
        }

        $stmtExtras->close();
        $consulta->close();
        $conexion->close();

        return $items;
    }

    public static function cambiarCantidad($detalle_id, $delta)
    {
        $conexion = DataBase::connect();

        $consulta = $conexion->prepare("
            SELECT cd.cantidad, cd.precio_unitario, COALESCE(SUM(cdi.precio_extra), 0) AS extras_unidad
            FROM carrito_detalle cd
            LEFT JOIN carrito_detalle_ingrediente cdi ON cd.id = cdi.carrito_detalle_id
            WHERE cd.id = ?
            GROUP BY cd.id
        ");
        $consulta->bind_param("i", $detalle_id);
        $consulta->execute();
        $datos = $consulta->get_result()->fetch_assoc();
        $consulta->close();

        if ($datos) {
            $nueva_cantidad = (int)$datos['cantidad'] + (int)$delta;
            if ($nueva_cantidad <= 0) {
                $conexion->close();
                self::eliminarItem($detalle_id);
                return;
            }

            $nuevo_subtotal = ((float)$datos['precio_unitario'] + (float)$datos['extras_unidad']) * $nueva_cantidad;
            $update = $conexion->prepare("UPDATE carrito_detalle SET cantidad = ?, subtotal = ? WHERE id = ?");
            $update->bind_param("idi", $nueva_cantidad, $nuevo_subtotal, $detalle_id);
            $update->execute();
            $update->close();
        }

        $conexion->close();
    }
    public static function eliminarItem($detalle_id)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare("DELETE FROM carrito_detalle WHERE id = ?");
        $consulta->bind_param("i", $detalle_id);
        $consulta->execute();
        $consulta->close();
        $conexion->close();
    }
    
    public static function contarItems()
    {
        self::iniciarSesion();
        $sesion_id = session_id();
        $conexion = DataBase::connect();

        $consulta = $conexion->prepare("
            SELECT COALESCE(SUM(cd.cantidad), 0) AS total
            FROM carrito_detalle cd
            INNER JOIN carrito c ON cd.carrito_id = c.id
            WHERE c.sesion_id = ? AND c.estado = 'activo'
        ");
        $consulta->bind_param("s", $sesion_id);
        $consulta->execute();
        $fila = $consulta->get_result()->fetch_assoc();

        $consulta->close();
        $conexion->close();

        return (int)($fila['total'] ?? 0);
    }
}