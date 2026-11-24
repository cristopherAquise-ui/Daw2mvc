<?php
require_once __DIR__ . '/../config/DataBase.php';
require_once __DIR__ . '/../helpers/db.php';
require_once __DIR__ . '/../modelos/Oferta.php';
require_once __DIR__ . '/../modelos/OfertaDescuento.php';
require_once __DIR__ . '/../modelos/OfertaDosPorUno.php';
require_once __DIR__ . '/../modelos/OfertaCombo.php';
require_once __DIR__ . '/../modelos/OfertaCodigo.php';
require_once __DIR__ . '/../modelos/Producto.php';

class OfertaDAO
{
    /**
     * Ofertas activas y vigentes.
     */
    public static function getActivas()
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare(
            "SELECT * FROM ofertas
             WHERE activa = 1
               AND fecha_inicio <= NOW()
               AND (fecha_fin IS NULL OR fecha_fin >= NOW())
             ORDER BY id"
        );
        $consulta->execute();
        $resultado = $consulta->get_result();

        $ofertas = [];
        while ($fila = $resultado->fetch_assoc()) {
            $ofertas[] = self::crearDesdeFila($fila);
        }

        $consulta->close();
        $conexion->close();
        return $ofertas;
    }

    /**
     * Ofertas que afectan a un producto concreto (por producto o por categoría).
     */
    public static function getActivasPorProducto($producto_id)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare(
            "SELECT DISTINCT o.*
             FROM ofertas o
             LEFT JOIN oferta_producto op ON o.id = op.oferta_id
             INNER JOIN productos p ON p.id = ?
             WHERE o.activa = 1
               AND o.fecha_inicio <= NOW()
               AND (o.fecha_fin IS NULL OR o.fecha_fin >= NOW())
               AND (
                    (o.ambito = 'producto' AND op.producto_id = p.id)
                    OR (o.ambito = 'categoria' AND o.categoria_id = p.categoria_id)
               )
             ORDER BY o.id"
        );

        $consulta->bind_param('i', $producto_id);
        $consulta->execute();
        $resultado = $consulta->get_result();

        $ofertas = [];
        while ($fila = $resultado->fetch_assoc()) {
            $ofertas[] = self::crearDesdeFila($fila);
        }

        $consulta->close();
        $conexion->close();
        return $ofertas;
    }

    public static function getActivasPorCategoria($categoria_id)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare(
            "SELECT * FROM ofertas
             WHERE activa = 1
               AND ambito = 'categoria'
               AND categoria_id = ?
               AND fecha_inicio <= NOW()
               AND (fecha_fin IS NULL OR fecha_fin >= NOW())
             ORDER BY id"
        );

        $consulta->bind_param('i', $categoria_id);
        $consulta->execute();
        $resultado = $consulta->get_result();

        $ofertas = [];
        while ($fila = $resultado->fetch_assoc()) {
            $ofertas[] = self::crearDesdeFila($fila);
        }

        $consulta->close();
        $conexion->close();
        return $ofertas;
    }

    public static function getByCodigo($codigo)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare(
            "SELECT * FROM ofertas
             WHERE codigo = ?
               AND activa = 1
               AND fecha_inicio <= NOW()
               AND (fecha_fin IS NULL OR fecha_fin >= NOW())
             LIMIT 1"
        );

        $consulta->bind_param('s', $codigo);
        $consulta->execute();
        $resultado = $consulta->get_result();
        $fila = $resultado->fetch_assoc();

        $consulta->close();
        $conexion->close();

        return $fila ? self::crearDesdeFila($fila) : null;
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

        if (!empty($filtros['tipo'])) {
            $where[] = 'tipo = ?';
            $types .= 's';
            $params[] = $filtros['tipo'];
        }

        if (!empty($filtros['ambito'])) {
            $where[] = 'ambito = ?';
            $types .= 's';
            $params[] = $filtros['ambito'];
        }

        if (isset($filtros['activa']) && $filtros['activa'] !== '') {
            $where[] = 'activa = ?';
            $types .= 'i';
            $params[] = (int) $filtros['activa'];
        }

        if (!empty($filtros['nombre'])) {
            $where[] = 'nombre LIKE ?';
            $types .= 's';
            $params[] = '%' . $filtros['nombre'] . '%';
        }

        if (!empty($filtros['codigo'])) {
            $where[] = 'codigo LIKE ?';
            $types .= 's';
            $params[] = '%' . $filtros['codigo'] . '%';
        }

        $sql = 'SELECT * FROM ofertas';
        if (count($where) > 0) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        $permitidos = ['id', 'nombre', 'tipo', 'ambito', 'valor', 'activa', 'fecha_inicio', 'fecha_fin'];
        $order = in_array($filtros['order'] ?? '', $permitidos, true) ? $filtros['order'] : 'id';
        $dir   = strtoupper($filtros['dir'] ?? 'ASC') === 'DESC' ? 'DESC' : 'ASC';
        $sql  .= " ORDER BY $order $dir";

        $conexion = DataBase::connect();
        $consulta = $conexion->prepare($sql);
        dbBind($consulta, $types, $params);
        $consulta->execute();
        $resultado = $consulta->get_result();

        $ofertas = [];
        while ($fila = $resultado->fetch_assoc()) {
            $ofertas[] = self::crearDesdeFila($fila);
        }

        $consulta->close();
        $conexion->close();
        return $ofertas;
    }

    public static function getById($id)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare("SELECT * FROM ofertas WHERE id = ? LIMIT 1");
        $consulta->bind_param('i', $id);
        $consulta->execute();
        $resultado = $consulta->get_result();
        $fila = $resultado->fetch_assoc();

        $consulta->close();
        $conexion->close();

        return $fila ? self::crearDesdeFila($fila) : null;
    }

    public static function insert(array $datos)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare(
            "INSERT INTO ofertas
             (nombre, descripcion, tipo, ambito, categoria_id, valor, codigo, cantidad_minima, importe_minimo, fecha_inicio, fecha_fin, activa)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $nombre          = (string) ($datos['nombre'] ?? '');
        $descripcion     = (string) ($datos['descripcion'] ?? '');
        $tipo            = (string) ($datos['tipo'] ?? 'descuento');
        $ambito          = (string) ($datos['ambito'] ?? 'producto');
        $categoria_id    = !empty($datos['categoria_id']) ? (int) $datos['categoria_id'] : null;
        $valor           = isset($datos['valor']) && $datos['valor'] !== '' ? (float) $datos['valor'] : null;
        $codigo          = !empty($datos['codigo']) ? (string) $datos['codigo'] : null;
        $cantidad_minima = (int) ($datos['cantidad_minima'] ?? 1);
        $importe_minimo  = (float) ($datos['importe_minimo'] ?? 0);
        $fecha_inicio    = (string) ($datos['fecha_inicio'] ?? date('Y-m-d H:i:s'));
        $fecha_fin       = !empty($datos['fecha_fin']) ? (string) $datos['fecha_fin'] : null;
        $activa          = (int) ($datos['activa'] ?? 1);

        $consulta->bind_param(
            'ssssidsidssi',
            $nombre,
            $descripcion,
            $tipo,
            $ambito,
            $categoria_id,
            $valor,
            $codigo,
            $cantidad_minima,
            $importe_minimo,
            $fecha_inicio,
            $fecha_fin,
            $activa
        );

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
            "UPDATE ofertas
             SET nombre = ?, descripcion = ?, tipo = ?, ambito = ?, categoria_id = ?, valor = ?, codigo = ?,
                 cantidad_minima = ?, importe_minimo = ?, fecha_inicio = ?, fecha_fin = ?, activa = ?
             WHERE id = ?"
        );

        $nombre          = (string) ($datos['nombre'] ?? '');
        $descripcion     = (string) ($datos['descripcion'] ?? '');
        $tipo            = (string) ($datos['tipo'] ?? 'descuento');
        $ambito          = (string) ($datos['ambito'] ?? 'producto');
        $categoria_id    = !empty($datos['categoria_id']) ? (int) $datos['categoria_id'] : null;
        $valor           = isset($datos['valor']) && $datos['valor'] !== '' ? (float) $datos['valor'] : null;
        $codigo          = !empty($datos['codigo']) ? (string) $datos['codigo'] : null;
        $cantidad_minima = (int) ($datos['cantidad_minima'] ?? 1);
        $importe_minimo  = (float) ($datos['importe_minimo'] ?? 0);
        $fecha_inicio    = (string) ($datos['fecha_inicio'] ?? date('Y-m-d H:i:s'));
        $fecha_fin       = !empty($datos['fecha_fin']) ? (string) $datos['fecha_fin'] : null;
        $activa          = (int) ($datos['activa'] ?? 1);
        $id              = (int) $id;

        $consulta->bind_param(
            'ssssidsidssii',
            $nombre,
            $descripcion,
            $tipo,
            $ambito,
            $categoria_id,
            $valor,
            $codigo,
            $cantidad_minima,
            $importe_minimo,
            $fecha_inicio,
            $fecha_fin,
            $activa,
            $id
        );

        $consulta->execute();
        $filas = $consulta->affected_rows;

        $consulta->close();
        $conexion->close();
        return $filas >= 0;
    }

    public static function delete($id)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare("UPDATE ofertas SET activa = 0 WHERE id = ?");
        $consulta->bind_param('i', $id);
        $consulta->execute();
        $filas = $consulta->affected_rows;

        $consulta->close();
        $conexion->close();
        return $filas > 0;
    }

    public static function getProductos($oferta_id)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare(
            "SELECT p.* FROM productos p
             INNER JOIN oferta_producto op ON p.id = op.producto_id
             WHERE op.oferta_id = ?
             ORDER BY p.nombre"
        );
        $consulta->bind_param('i', $oferta_id);
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

    public static function asignarProducto($oferta_id, $producto_id)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare(
            "INSERT IGNORE INTO oferta_producto (oferta_id, producto_id) VALUES (?, ?)"
        );
        $consulta->bind_param('ii', $oferta_id, $producto_id);
        $consulta->execute();
        $filas = $consulta->affected_rows;

        $consulta->close();
        $conexion->close();
        return $filas > 0;
    }

    public static function quitarProducto($oferta_id, $producto_id)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare("DELETE FROM oferta_producto WHERE oferta_id = ? AND producto_id = ?");
        $consulta->bind_param('ii', $oferta_id, $producto_id);
        $consulta->execute();
        $filas = $consulta->affected_rows;

        $consulta->close();
        $conexion->close();
        return $filas > 0;
    }

    /**
     * Fábrica: convierte una fila de la BD en la subclase de Oferta adecuada.
     */
    public static function crearDesdeFila(array $fila): Oferta
    {
        switch ($fila['tipo'] ?? '') {
            case '2x1':
                $oferta = new OfertaDosPorUno();
                break;
            case 'combo':
                $oferta = new OfertaCombo();
                break;
            case 'codigo':
                $oferta = new OfertaCodigo();
                break;
            case 'descuento':
            default:
                $oferta = new OfertaDescuento();
                break;
        }

        $oferta->hydrate($fila);
        return $oferta;
    }

    public static function getAllJson(array $filtros = [])
    {
        return array_map([self::class, 'toArray'], self::getAll($filtros));
    }

    public static function toArray(Oferta $oferta): array
    {
        return [
            'id'              => (int) $oferta->getId(),
            'nombre'          => $oferta->getNombre(),
            'descripcion'     => $oferta->getDescripcion(),
            'tipo'            => $oferta->getTipo(),
            'ambito'          => $oferta->getAmbito(),
            'categoria_id'    => $oferta->getCategoriaId() !== null ? (int) $oferta->getCategoriaId() : null,
            'valor'           => $oferta->getValor() !== null ? (float) $oferta->getValor() : null,
            'codigo'          => $oferta->getCodigo(),
            'cantidad_minima' => (int) $oferta->getCantidadMinima(),
            'importe_minimo'  => (float) $oferta->getImporteMinimo(),
            'fecha_inicio'    => $oferta->getFechaInicio(),
            'fecha_fin'       => $oferta->getFechaFin(),
            'activa'          => (int) $oferta->getActiva(),
        ];
    }
}
