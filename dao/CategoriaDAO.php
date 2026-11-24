<?php
require_once __DIR__ . '/../config/DataBase.php';
require_once __DIR__ . '/../helpers/db.php';
require_once __DIR__ . '/../modelos/Categoria.php';

class CategoriaDAO
{
    public static function getAll()
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare("SELECT * FROM categorias ORDER BY orden, nombre");
        $consulta->execute();
        $resultado = $consulta->get_result();

        $categorias = [];
        while ($categoria = $resultado->fetch_object('Categoria')) {
            $categorias[] = $categoria;
        }

        $consulta->close();
        $conexion->close();
        return $categorias;
    }

    public static function getActivas()
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare("SELECT * FROM categorias WHERE activa = 1 ORDER BY orden, nombre");
        $consulta->execute();
        $resultado = $consulta->get_result();

        $categorias = [];
        while ($categoria = $resultado->fetch_object('Categoria')) {
            $categorias[] = $categoria;
        }

        $consulta->close();
        $conexion->close();
        return $categorias;
    }

    public static function getById($id)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare("SELECT * FROM categorias WHERE id = ?");
        $consulta->bind_param('i', $id);
        $consulta->execute();
        $resultado = $consulta->get_result();
        $categoria = $resultado->fetch_object('Categoria');

        $consulta->close();
        $conexion->close();
        return $categoria ?: null;
    }

    public static function insert(array $datos)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare(
            "INSERT INTO categorias (nombre, descripcion, imagen, activa, orden) VALUES (?, ?, ?, ?, ?)"
        );

        $nombre      = (string) ($datos['nombre'] ?? '');
        $descripcion = (string) ($datos['descripcion'] ?? '');
        $imagen      = (string) ($datos['imagen'] ?? '');
        $activa      = (int) ($datos['activa'] ?? 1);
        $orden       = (int) ($datos['orden'] ?? 0);

        $consulta->bind_param('sssii', $nombre, $descripcion, $imagen, $activa, $orden);
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
            "UPDATE categorias SET nombre = ?, descripcion = ?, imagen = ?, activa = ?, orden = ? WHERE id = ?"
        );

        $nombre      = (string) ($datos['nombre'] ?? '');
        $descripcion = (string) ($datos['descripcion'] ?? '');
        $imagen      = (string) ($datos['imagen'] ?? '');
        $activa      = (int) ($datos['activa'] ?? 1);
        $orden       = (int) ($datos['orden'] ?? 0);
        $id          = (int) $id;

        $consulta->bind_param('sssiii', $nombre, $descripcion, $imagen, $activa, $orden, $id);
        $consulta->execute();
        $filas = $consulta->affected_rows;

        $consulta->close();
        $conexion->close();
        return $filas >= 0;
    }

    // Baja lógica: la categoría deja de mostrarse pero no se rompen los productos.
    public static function delete($id)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare("UPDATE categorias SET activa = 0 WHERE id = ?");
        $consulta->bind_param('i', $id);
        $consulta->execute();
        $filas = $consulta->affected_rows;

        $consulta->close();
        $conexion->close();
        return $filas > 0;
    }

    public static function getAllJson()
    {
        return array_map([self::class, 'toArray'], self::getAll());
    }

    public static function toArray(Categoria $categoria): array
    {
        return [
            'id'                 => (int) $categoria->getId(),
            'nombre'             => $categoria->getNombre(),
            'descripcion'        => $categoria->getDescripcion(),
            'imagen'             => $categoria->getImagen(),
            'activa'             => (int) $categoria->getActiva(),
            'orden'              => (int) $categoria->getOrden(),
            'fecha_creacion'     => $categoria->getFechaCreacion(),
            'fecha_actualizacion'=> $categoria->getFechaActualizacion(),
        ];
    }
}
