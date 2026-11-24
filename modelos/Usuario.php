<?php

class Usuario
{
    protected $id;
    protected $nombre;
    protected $apellidos;
    protected $email;
    protected $password;
    protected $telefono;
    protected $direccion;
    protected $ciudad;
    protected $codigo_postal;
    protected $fecha_nacimiento;
    protected $rol = 'usuario';
    protected $activo = 1;
    protected $email_verificado = 0;
    protected $ultimo_acceso;
    protected $fecha_registro;

    public function __construct(
        $id = null,
        $nombre = null,
        $apellidos = null,
        $email = null,
        $password = null,
        $telefono = null,
        $direccion = null,
        $ciudad = null,
        $codigo_postal = null,
        $fecha_nacimiento = null,
        $rol = null,
        $activo = null,
        $email_verificado = null,
        $ultimo_acceso = null,
        $fecha_registro = null
    ) {
        if ($id !== null) $this->id = $id;
        if ($nombre !== null) $this->nombre = $nombre;
        if ($apellidos !== null) $this->apellidos = $apellidos;
        if ($email !== null) $this->email = $email;
        if ($password !== null) $this->password = $password;
        if ($telefono !== null) $this->telefono = $telefono;
        if ($direccion !== null) $this->direccion = $direccion;
        if ($ciudad !== null) $this->ciudad = $ciudad;
        if ($codigo_postal !== null) $this->codigo_postal = $codigo_postal;
        if ($fecha_nacimiento !== null) $this->fecha_nacimiento = $fecha_nacimiento;
        if ($rol !== null) $this->rol = $rol;
        if ($activo !== null) $this->activo = $activo;
        if ($email_verificado !== null) $this->email_verificado = $email_verificado;
        if ($ultimo_acceso !== null) $this->ultimo_acceso = $ultimo_acceso;
        if ($fecha_registro !== null) $this->fecha_registro = $fecha_registro;
    }

    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }
    public function getApellidos() { return $this->apellidos; }
    public function getEmail() { return $this->email; }
    public function getPassword() { return $this->password; }
    public function getTelefono() { return $this->telefono; }
    public function getDireccion() { return $this->direccion; }
    public function getCiudad() { return $this->ciudad; }
    public function getCodigoPostal() { return $this->codigo_postal; }
    public function getFechaNacimiento() { return $this->fecha_nacimiento; }
    public function getRol() { return $this->rol; }
    public function getActivo() { return $this->activo; }
    public function getEmailVerificado() { return $this->email_verificado; }
    public function getUltimoAcceso() { return $this->ultimo_acceso; }
    public function getFechaRegistro() { return $this->fecha_registro; }

    public function getNombreCompleto(): string
    {
        return trim(($this->nombre ?? '') . ' ' . ($this->apellidos ?? ''));
    }

    public function esAdmin(): bool
    {
        return $this->rol === 'admin';
    }

    public function estaActivo(): bool
    {
        return (int) $this->activo === 1;
    }
}
