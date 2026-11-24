<?php
require_once __DIR__ . '/../config/DataBase.php';
require_once __DIR__ . '/../helpers/db.php';
require_once __DIR__ . '/../modelos/Usuario.php';

class UsuarioDAO
{
    public static function getByEmail($email)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare("SELECT * FROM usuarios WHERE email = ? LIMIT 1");
        $consulta->bind_param('s', $email);
        $consulta->execute();
        $resultado = $consulta->get_result();
        $usuario = $resultado->fetch_object('Usuario');

        $consulta->close();
        $conexion->close();
        return $usuario ?: null;
    }

    public static function getById($id)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare("SELECT * FROM usuarios WHERE id = ? LIMIT 1");
        $consulta->bind_param('i', $id);
        $consulta->execute();
        $resultado = $consulta->get_result();
        $usuario = $resultado->fetch_object('Usuario');

        $consulta->close();
        $conexion->close();
        return $usuario ?: null;
    }

    public static function emailExiste($email, $excluirId = null)
    {
        $conexion = DataBase::connect();

        if ($excluirId === null) {
            $consulta = $conexion->prepare("SELECT id FROM usuarios WHERE email = ? LIMIT 1");
            $consulta->bind_param('s', $email);
        } else {
            $consulta = $conexion->prepare("SELECT id FROM usuarios WHERE email = ? AND id <> ? LIMIT 1");
            $consulta->bind_param('si', $email, $excluirId);
        }

        $consulta->execute();
        $resultado = $consulta->get_result();
        $existe = $resultado->num_rows > 0;

        $consulta->close();
        $conexion->close();
        return $existe;
    }

    public static function insert(array $datos)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare(
            "INSERT INTO usuarios
             (nombre, apellidos, email, password, telefono, direccion, ciudad, codigo_postal, fecha_nacimiento, rol, activo)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $nombre           = (string) ($datos['nombre'] ?? '');
        $apellidos        = (string) ($datos['apellidos'] ?? '');
        $email            = (string) ($datos['email'] ?? '');
        $password         = password_hash((string) ($datos['password'] ?? ''), PASSWORD_DEFAULT);
        $telefono         = (string) ($datos['telefono'] ?? '');
        $direccion        = (string) ($datos['direccion'] ?? '');
        $ciudad           = (string) ($datos['ciudad'] ?? '');
        $codigo_postal    = (string) ($datos['codigo_postal'] ?? '');
        $fecha_nacimiento = !empty($datos['fecha_nacimiento']) ? $datos['fecha_nacimiento'] : null;
        $rol              = (string) ($datos['rol'] ?? 'usuario');
        $activo           = (int) ($datos['activo'] ?? 1);

        $consulta->bind_param(
            'ssssssssssi',
            $nombre,
            $apellidos,
            $email,
            $password,
            $telefono,
            $direccion,
            $ciudad,
            $codigo_postal,
            $fecha_nacimiento,
            $rol,
            $activo
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
            "UPDATE usuarios
             SET nombre = ?, apellidos = ?, email = ?, telefono = ?, direccion = ?, ciudad = ?,
                 codigo_postal = ?, fecha_nacimiento = ?, rol = ?, activo = ?
             WHERE id = ?"
        );

        $nombre           = (string) ($datos['nombre'] ?? '');
        $apellidos        = (string) ($datos['apellidos'] ?? '');
        $email            = (string) ($datos['email'] ?? '');
        $telefono         = (string) ($datos['telefono'] ?? '');
        $direccion        = (string) ($datos['direccion'] ?? '');
        $ciudad           = (string) ($datos['ciudad'] ?? '');
        $codigo_postal    = (string) ($datos['codigo_postal'] ?? '');
        $fecha_nacimiento = !empty($datos['fecha_nacimiento']) ? $datos['fecha_nacimiento'] : null;
        $rol              = (string) ($datos['rol'] ?? 'usuario');
        $activo           = (int) ($datos['activo'] ?? 1);
        $id               = (int) $id;

        $consulta->bind_param(
            'sssssssssii',
            $nombre,
            $apellidos,
            $email,
            $telefono,
            $direccion,
            $ciudad,
            $codigo_postal,
            $fecha_nacimiento,
            $rol,
            $activo,
            $id
        );

        $consulta->execute();
        $filas = $consulta->affected_rows;

        $consulta->close();
        $conexion->close();
        return $filas >= 0;
    }

    public static function updatePassword($id, $password)
    {
        $hash = password_hash((string) $password, PASSWORD_DEFAULT);

        $conexion = DataBase::connect();
        $consulta = $conexion->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
        $consulta->bind_param('si', $hash, $id);
        $consulta->execute();
        $filas = $consulta->affected_rows;

        $consulta->close();
        $conexion->close();
        return $filas > 0;
    }

    public static function registrarAcceso($id)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare("UPDATE usuarios SET ultimo_acceso = NOW() WHERE id = ?");
        $consulta->bind_param('i', $id);
        $consulta->execute();
        $consulta->close();
        $conexion->close();
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

        if (!empty($filtros['nombre'])) {
            $where[] = '(nombre LIKE ? OR apellidos LIKE ? OR email LIKE ?)';
            $types .= 'sss';
            $like = '%' . $filtros['nombre'] . '%';
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }

        if (!empty($filtros['email'])) {
            $where[] = 'email LIKE ?';
            $types .= 's';
            $params[] = '%' . $filtros['email'] . '%';
        }

        if (!empty($filtros['rol'])) {
            $where[] = 'rol = ?';
            $types .= 's';
            $params[] = $filtros['rol'];
        }

        if (isset($filtros['activo']) && $filtros['activo'] !== '') {
            $where[] = 'activo = ?';
            $types .= 'i';
            $params[] = (int) $filtros['activo'];
        }

        $sql = 'SELECT * FROM usuarios';
        if (count($where) > 0) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        $permitidos = ['id', 'nombre', 'email', 'rol', 'activo', 'fecha_registro', 'ultimo_acceso'];
        $order = in_array($filtros['order'] ?? '', $permitidos, true) ? $filtros['order'] : 'id';
        $dir   = strtoupper($filtros['dir'] ?? 'ASC') === 'DESC' ? 'DESC' : 'ASC';
        $sql  .= " ORDER BY $order $dir";

        $conexion = DataBase::connect();
        $consulta = $conexion->prepare($sql);
        dbBind($consulta, $types, $params);
        $consulta->execute();
        $resultado = $consulta->get_result();

        $usuarios = [];
        while ($usuario = $resultado->fetch_object('Usuario')) {
            $usuarios[] = $usuario;
        }

        $consulta->close();
        $conexion->close();
        return $usuarios;
    }

    public static function setActivo($id, $activo)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare("UPDATE usuarios SET activo = ? WHERE id = ?");
        $activo = (int) $activo;
        $consulta->bind_param('ii', $activo, $id);
        $consulta->execute();
        $filas = $consulta->affected_rows;

        $consulta->close();
        $conexion->close();
        return $filas > 0;
    }

    public static function setRol($id, $rol)
    {
        $conexion = DataBase::connect();
        $consulta = $conexion->prepare("UPDATE usuarios SET rol = ? WHERE id = ?");
        $consulta->bind_param('si', $rol, $id);
        $consulta->execute();
        $filas = $consulta->affected_rows;

        $consulta->close();
        $conexion->close();
        return $filas > 0;
    }

    public static function getAllJson(array $filtros = [])
    {
        return array_map([self::class, 'toArray'], self::getAll($filtros));
    }

    public static function toArray(Usuario $usuario): array
    {
        return [
            'id'              => (int) $usuario->getId(),
            'nombre'          => $usuario->getNombre(),
            'apellidos'       => $usuario->getApellidos(),
            'email'           => $usuario->getEmail(),
            'telefono'        => $usuario->getTelefono(),
            'direccion'       => $usuario->getDireccion(),
            'ciudad'          => $usuario->getCiudad(),
            'codigo_postal'   => $usuario->getCodigoPostal(),
            'rol'             => $usuario->getRol(),
            'activo'          => (int) $usuario->getActivo(),
            'ultimo_acceso'   => $usuario->getUltimoAcceso(),
            'fecha_registro'  => $usuario->getFechaRegistro(),
        ];
    }
}
