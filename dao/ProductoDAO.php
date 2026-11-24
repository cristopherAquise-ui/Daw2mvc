<?php
require_once __DIR__ . '/../config/DataBase.php';
require_once __DIR__ . '/../helpers/db.php';
require_once __DIR__ . '/../modelos/Producto.php';

class ProductoDAO
{
    // Todos los productos activos.
    public static function getAll()
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare("SELECT * FROM productos WHERE activo = 1 ORDER BY categoria_id, orden, nombre");
        $consulta->execute();
        $resultado = $consulta->get_result();

        $productos = [];
        while ($producto = $resultado->fetch_object('Producto')) {
            $productos[] = $producto;
        }

        $consulta->close();
        $conexion->close();
        return $productos;
    }

    // Productos activos de una categoría.
    public static function getByCategoria($categoria_id)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare("SELECT * FROM productos WHERE categoria_id = ? AND activo = 1 ORDER BY orden, nombre");
        $consulta->bind_param('i', $categoria_id);
        $consulta->execute();
        $resultado = $consulta->get_result();

        $productos = [];
        while ($producto = $resultado->fetch_object('Producto')) {
            $productos[] = $producto;
        }

        $consulta->close();
        $conexion->close();
        return $productos;
    }

    // Productos destacados para la portada.
    public static function getDestacados($limite = 2)
    {
        $limite = max(1, (int) $limite);
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare("SELECT * FROM productos WHERE activo = 1 AND destacado = 1 ORDER BY orden, id LIMIT $limite");
        $consulta->execute();
        $resultado = $consulta->get_result();

        $productos = [];
        while ($producto = $resultado->fetch_object('Producto')) {
            $productos[] = $producto;
        }

        $consulta->close();
        $conexion->close();
        return $productos;
    }

    // Un producto por su id.
    public static function getById($id)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare("SELECT * FROM productos WHERE id = ?");
        $consulta->bind_param('i', $id);
        $consulta->execute();
        $resultado = $consulta->get_result();
        $producto = $resultado->fetch_object('Producto');

        $consulta->close();
        $conexion->close();
        return $producto ?: null;
    }

    /**
     * Listado para el panel de administración.
     * Admite filtros por id, categoría, nombre, precio y estado.
     */
    public static function getAllAdmin(array $filtros = [])
    {
        $where  = [];
        $types  = '';
        $params = [];

        if (isset($filtros['id']) && $filtros['id'] !== '') {
            $where[] = 'id = ?';
            $types .= 'i';
            $params[] = (int) $filtros['id'];
        }

        if (isset($filtros['categoria_id']) && $filtros['categoria_id'] !== '') {
            $where[] = 'categoria_id = ?';
            $types .= 'i';
            $params[] = (int) $filtros['categoria_id'];
        }

        if (!empty($filtros['nombre'])) {
            $where[] = 'nombre LIKE ?';
            $types .= 's';
            $params[] = '%' . $filtros['nombre'] . '%';
        }

        if (isset($filtros['precio_min']) && $filtros['precio_min'] !== '') {
            $where[] = 'precio >= ?';
            $types .= 'd';
            $params[] = (float) $filtros['precio_min'];
        }

        if (isset($filtros['precio_max']) && $filtros['precio_max'] !== '') {
            $where[] = 'precio <= ?';
            $types .= 'd';
            $params[] = (float) $filtros['precio_max'];
        }

        if (isset($filtros['activo']) && $filtros['activo'] !== '') {
            $where[] = 'activo = ?';
            $types .= 'i';
            $params[] = (int) $filtros['activo'];
        }

        $sql = 'SELECT * FROM productos';
        if (count($where) > 0) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        $permitidos = ['id', 'nombre', 'precio', 'categoria_id', 'activo', 'orden', 'fecha_creacion'];
        $order = in_array($filtros['order'] ?? '', $permitidos, true) ? $filtros['order'] : 'id';
        $dir   = strtoupper($filtros['dir'] ?? 'ASC') === 'DESC' ? 'DESC' : 'ASC';
        $sql  .= " ORDER BY $order $dir";

        if (isset($filtros['limit']) && (int) $filtros['limit'] > 0) {
            $sql .= ' LIMIT ' . (int) $filtros['limit'];
        }

        $conexion = DataBase::connect();
        $consulta = $conexion->prepare($sql);
        dbBind($consulta, $types, $params);
        $consulta->execute();
        $resultado = $consulta->get_result();

        $productos = [];
        while ($producto = $resultado->fetch_object('Producto')) {
            $productos[] = $producto;
        }

        $consulta->close();
        $conexion->close();
        return $productos;
    }

    public static function insert(array $datos)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare(
            "INSERT INTO productos (categoria_id, nombre, descripcion, precio, imagen, activo, destacado, orden, iva_porcentaje)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $categoria_id = (int) ($datos['categoria_id'] ?? 0);
        $nombre       = (string) ($datos['nombre'] ?? '');
        $descripcion  = (string) ($datos['descripcion'] ?? '');
        $precio       = (float) ($datos['precio'] ?? 0);
        $imagen       = (string) ($datos['imagen'] ?? '');
        $activo       = (int) ($datos['activo'] ?? 1);
        $destacado    = (int) ($datos['destacado'] ?? 0);
        $orden        = (int) ($datos['orden'] ?? 0);
        $iva          = (float) ($datos['iva_porcentaje'] ?? 10);

        $consulta->bind_param('issdsiiid', $categoria_id, $nombre, $descripcion, $precio, $imagen, $activo, $destacado, $orden, $iva);
        $consulta->execute();
        $id = $consulta->insert_id;

        $consulta->close();
        $conexion->close();
        return $id;
    }

    public static function update($id, array $datos)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare(
            "UPDATE productos
             SET categoria_id = ?, nombre = ?, descripcion = ?, precio = ?, imagen = ?, activo = ?, destacado = ?, orden = ?, iva_porcentaje = ?
             WHERE id = ?"
        );

        $categoria_id = (int) ($datos['categoria_id'] ?? 0);
        $nombre       = (string) ($datos['nombre'] ?? '');
        $descripcion  = (string) ($datos['descripcion'] ?? '');
        $precio       = (float) ($datos['precio'] ?? 0);
        $imagen       = (string) ($datos['imagen'] ?? '');
        $activo       = (int) ($datos['activo'] ?? 1);
        $destacado    = (int) ($datos['destacado'] ?? 0);
        $orden        = (int) ($datos['orden'] ?? 0);
        $iva          = (float) ($datos['iva_porcentaje'] ?? 10);
        $id           = (int) $id;

        $consulta->bind_param('issdsiiidi', $categoria_id, $nombre, $descripcion, $precio, $imagen, $activo, $destacado, $orden, $iva, $id);
        $consulta->execute();
        $filas = $consulta->affected_rows;

        $consulta->close();
        $conexion->close();
        return $filas >= 0;
    }

    // Baja lógica: no se borra el producto para no romper pedidos históricos.
    public static function delete($id)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare("UPDATE productos SET activo = 0 WHERE id = ?");
        $consulta->bind_param('i', $id);
        $consulta->execute();
        $filas = $consulta->affected_rows;

        $consulta->close();
        $conexion->close();
        return $filas > 0;
    }

    public static function getAllJson(array $filtros = [])
    {
        $productos = self::getAllAdmin($filtros);
        return array_map([self::class, 'toArray'], $productos);
    }

    public static function toArray(Producto $producto): array
    {
        return [
            'id'                 => (int) $producto->getId(),
            'categoria_id'       => (int) $producto->getCategoriaId(),
            'nombre'             => $producto->getNombre(),
            'descripcion'        => $producto->getDescripcion(),
            'precio'             => (float) $producto->getPrecio(),
            'imagen'             => $producto->getImagen(),
            'activo'             => (int) $producto->getActivo(),
            'destacado'          => (int) $producto->getDestacado(),
            'orden'              => (int) $producto->getOrden(),
            'iva_porcentaje'     => (float) $producto->getIvaPorcentaje(),
            'fecha_creacion'     => $producto->getFechaCreacion(),
            'fecha_actualizacion'=> $producto->getFechaActualizacion(),
        ];
    }
}
