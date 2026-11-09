<?php

class DataBase
{
    private const DB_HOST = '127.0.0.1';
    private const DB_USER = 'root';
    private const DB_PASS = 'admin123'; // clave de Docker
    private const DB_NAME = 'daw2mvc'; // base de datos
    private const DB_PORT = 3307; // puerto de Docker

    public static function connect(
        $host = self::DB_HOST,
        $usuario = self::DB_USER,
        $clave = self::DB_PASS,
        $base = self::DB_NAME,
        $puerto = self::DB_PORT
    ) {
        $conexion = new mysqli($host, $usuario, $clave, $base, $puerto);
        if ($conexion->connect_error) {
            die("ERROR!!: No te puedes conectar: " . $conexion->connect_error);
        }
        $conexion->set_charset("utf8mb4");
        return $conexion;
    }
}
