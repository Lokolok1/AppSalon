<?php

namespace Model;

class Usuario extends ActiveRecord {
    // Base de datos
    protected static $tabla = "usuarios";
    protected static $columnasDB = ["id", "nombre", "apellido", "correo", "contraseña", "telefono", "admin", "confirmado", "token"];

    public $id;
    public $nombre;
    public $apellido;
    public $correo;
    public $contraseña;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;

    public function __construct($args = []) {
        $this->id = $args["id"] ?? null;
        $this->nombre = $args["nombre"] ?? "";
        $this->apellido = $args["apellido"] ?? "";
        $this->correo = $args["correo"] ?? "";
        $this->contraseña = $args["contraseña"] ?? "";
        $this->telefono = $args["telefono"] ?? "";
        $this->admin = $args["admin"] ?? "0";
        $this->confirmado = $args["confirmado"] ?? "0";
        $this->token = $args["token"] ?? "";
    }

    // Validación
    public function validarCuenta() {
        if (!$this->nombre) {
            self::$alertas["error"][] = "El nombre es obligatorio";
        } else if (!preg_match("#^[a-z][\da-z_]{2,60}[a-z\d]\$#i", $this->nombre)) {
            self::$alertas["error"][] = "El nombre introducido es inválido";
        }

        if (!$this->apellido) {
            self::$alertas["error"][] = "El apellido es obligatorio";
        } else if (!preg_match("#^[a-z][\da-z_]{2,60}[a-z\d]\$#i", $this->apellido)) {
            self::$alertas["error"][] = "El apellido introducido es inválido";
        }

        if (!$this->telefono) {
            self::$alertas["error"][] = "El telefono es obligatorio";   
        } else if (!preg_match("/^6[0-9]{9}$/", $this->telefono)) {
            self::$alertas["error"][] = "El telefono introducido es inválido";
        }

        if (!$this->correo) {
            self::$alertas["error"][] = "El correo es obligatorio";
        } else if (!preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $this->correo)) {
            self::$alertas["error"][] = "El correo introducido es inválido";
        }

        if (!$this->contraseña) {
            self::$alertas["error"][] = "La contraseña es obligatoria";   
        } else if (!preg_match("/^(?=.*[a-zA-Z0-9])[a-zA-Z0-9]{6,}$/", $this->contraseña)) {
            self::$alertas["error"][] = "La contraseña introducida es inválida";
        }
        
        return self::$alertas;
    }

    public function validarLogin() {
        if (!$this->correo) {
            self::$alertas["error"][] = "El correo es obligatorio";
        }

        if (!$this->contraseña) {
            self::$alertas["error"][] = "La contraseña es obligatoria";
        }

        return self::$alertas;
    }

    public function validarCorreo() {
        if (!$this->correo) {
            self::$alertas["error"][] = "El correo es obligatorio";
        }
        
        return self::$alertas;
    }

    public function validarContraseña() {
        if (!$this->contraseña) {
            self::$alertas["error"][] = "La contraseña es obligatoria";   
        } else if (!preg_match("/^(?=.*[a-zA-Z0-9])[a-zA-Z0-9]{6,}$/", $this->contraseña)) {
            self::$alertas["error"][] = "La contraseña introducida es inválida";
        }
        
        return self::$alertas;
    }

    // Revisar si el usuario ya existe
    public function existeUsuario() {
        $query = "SELECT * FROM " . self::$tabla . " WHERE correo = '" . $this->correo . "' LIMIT 1";
        $resultado = self::$db->query($query);

        if ($resultado->num_rows) {
            self::$alertas["error"][] = "El usuario ya esta registrado";
        }

        return $resultado;
    }

    public function hashPassword() {
        $this->contraseña = password_hash($this->contraseña, PASSWORD_BCRYPT);
    }

    public function crearToken() {
        $this->token = uniqid();
    }

    public function comprobarPasswordAndVerificado($contraseña) {
        $resultado = password_verify($contraseña, $this->contraseña);
        
        if ($resultado === false || $this->confirmado === "0") {
            self::$alertas["error"][] = "Contraseña incorrecta o tu cuenta no esta confirmada";
        } else {
            return true;
        }
    }
}