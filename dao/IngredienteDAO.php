<?php
require_once __DIR__ . '/../config/DataBase.php';
require_once __DIR__ . '/../helpers/db.php';
require_once __DIR__ . '/../modelos/Ingrediente.php';

class IngredienteDAO
{
    // Ingredientes base que el cliente puede quitar.
    public static function getBaseByProducto($producto_id)
    {
        return self::getPorTipo($producto_id, 'base');
    }

    // Extras opcionales que el cliente puede añadir.
    public static function getExtrasByProducto($producto_id)
    {
        return self::getPorTipo($producto_id, 'extra');
    }

    private static function getPorTipo($producto_id, string $tipo)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare(
            "SELECT i.*, pi.tipo, pi.incluido_por_defecto, pi.obligatorio, pi.cantidad
             FROM ingredientes i
             INNER JOIN producto_ingrediente pi ON i.id = pi.ingrediente_id
             WHERE pi.producto_id = ? AND pi.tipo = ? AND i.activo = 1
             ORDER BY i.nombre"
        );

        $consulta->bind_param('is', $producto_id, $tipo);
        $consulta->execute();
        $resultado = $consulta->get_result();

        $ingredientes = [];
        while ($ingrediente = $resultado->fetch_object('Ingrediente')) {
            $ingredientes[] = $ingrediente;
        }

        $consulta->close();
        $conexion->close();
        return $ingredientes;
    }

    public static function getAll()
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare("SELECT * FROM ingredientes WHERE activo = 1 ORDER BY nombre");
        $consulta->execute();
        $resultado = $consulta->get_result();

        $ingredientes = [];
        while ($ingrediente = $resultado->fetch_object('Ingrediente')) {
            $ingredientes[] = $ingrediente;
        }

        $consulta->close();
        $conexion->close();
        return $ingredientes;
    }

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

        if (!empty($filtros['nombre'])) {
            $where[] = 'nombre LIKE ?';
            $types .= 's';
            $params[] = '%' . $filtros['nombre'] . '%';
        }

        if (isset($filtros['activo']) && $filtros['activo'] !== '') {
            $where[] = 'activo = ?';
            $types .= 'i';
            $params[] = (int) $filtros['activo'];
        }

        $sql = 'SELECT * FROM ingredientes';
        if (count($where) > 0) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        $permitidos = ['id', 'nombre', 'precio_extra', 'activo'];
        $order = in_array($filtros['order'] ?? '', $permitidos, true) ? $filtros['order'] : 'nombre';
        $dir   = strtoupper($filtros['dir'] ?? 'ASC') === 'DESC' ? 'DESC' : 'ASC';
        $sql  .= " ORDER BY $order $dir";

        $conexion = DataBase::connect();
        $consulta = $conexion->prepare($sql);
        dbBind($consulta, $types, $params);
        $consulta->execute();
        $resultado = $consulta->get_result();

        $ingredientes = [];
        while ($ingrediente = $resultado->fetch_object('Ingrediente')) {
            $ingredientes[] = $ingrediente;
        }

        $consulta->close();
        $conexion->close();
        return $ingredientes;
    }

    public static function getById($id)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare("SELECT * FROM ingredientes WHERE id = ?");
        $consulta->bind_param('i', $id);
        $consulta->execute();
        $resultado = $consulta->get_result();
        $ingrediente = $resultado->fetch_object('Ingrediente');

        $consulta->close();
        $conexion->close();
        return $ingrediente ?: null;
    }

    public static function insert(array $datos)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare("INSERT INTO ingredientes (nombre, precio_extra, activo) VALUES (?, ?, ?)");

        $nombre = (string) ($datos['nombre'] ?? '');
        $precio = (float) ($datos['precio_extra'] ?? 0);
        $activo = (int) ($datos['activo'] ?? 1);

        $consulta->bind_param('sdi', $nombre, $precio, $activo);
        $consulta->execute();
        $id = $consulta->insert_id;

        $consulta->close();
        $conexion->close();
        return $id;
    }

    public static function update($id, array $datos)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare("UPDATE ingredientes SET nombre = ?, precio_extra = ?, activo = ? WHERE id = ?");

        $nombre = (string) ($datos['nombre'] ?? '');
        $precio = (float) ($datos['precio_extra'] ?? 0);
        $activo = (int) ($datos['activo'] ?? 1);
        $id     = (int) $id;

        $consulta->bind_param('sdii', $nombre, $precio, $activo, $id);
        $consulta->execute();
        $filas = $consulta->affected_rows;

        $consulta->close();
        $conexion->close();
        return $filas >= 0;
    }

    public static function delete($id)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare("UPDATE ingredientes SET activo = 0 WHERE id = ?");
        $consulta->bind_param('i', $id);
        $consulta->execute();
        $filas = $consulta->affected_rows;

        $consulta->close();
        $conexion->close();
        return $filas > 0;
    }

    public static function asignarAProducto($producto_id, $ingrediente_id, string $tipo = 'extra', int $incluido = 0, int $obligatorio = 0, float $cantidad = 1)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare(
            "INSERT INTO producto_ingrediente (producto_id, ingrediente_id, tipo, incluido_por_defecto, obligatorio, cantidad)
             VALUES (?, ?, ?, ?, ?, ?)
             ON DUPLICATE KEY UPDATE tipo = VALUES(tipo), incluido_por_defecto = VALUES(incluido_por_defecto),
             obligatorio = VALUES(obligatorio), cantidad = VALUES(cantidad)"
        );

        $consulta->bind_param('iisiid', $producto_id, $ingrediente_id, $tipo, $incluido, $obligatorio, $cantidad);
        $consulta->execute();
        $consulta->close();
        $conexion->close();
        return true;
    }

    public static function quitarDeProducto($producto_id, $ingrediente_id)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare("DELETE FROM producto_ingrediente WHERE producto_id = ? AND ingrediente_id = ?");
        $consulta->bind_param('ii', $producto_id, $ingrediente_id);
        $consulta->execute();
        $filas = $consulta->affected_rows;

        $consulta->close();
        $conexion->close();
        return $filas > 0;
    }

    public static function getAllJson(array $filtros = [])
    {
        return array_map([self::class, 'toArray'], self::getAllAdmin($filtros));
    }

    public static function toArray(Ingrediente $ingrediente): array
    {
        return [
            'id'           => (int) $ingrediente->getId(),
            'nombre'       => $ingrediente->getNombre(),
            'precio_extra' => (float) $ingrediente->getPrecioExtra(),
            'activo'       => (int) $ingrediente->getActivo(),
        ];
    }
}
