<?php 

namespace Model;

class Usuario extends ActiveRecord {
    protected static $tabla = "usuarios";
    protected static $columnasDB = ["id", "nombre", "apellido", "email", "password", "telefono", "admin", "confirmado", "token"];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;

    public function __construct($args = [])
    {
        $this->id = $args["id"] ?? null;
        $this->nombre = $args["nombre"] ?? "";
        $this->apellido = $args["apellido"] ?? "";
        $this->email = $args["email"] ?? "";
        $this->password = $args["password"] ?? "";
        $this->telefono = $args["telefono"] ?? "";
        $this->admin = $args["admin"] ?? "0";
        $this->confirmado = $args["confirmado"] ?? "0";
        $this->token = $args["token"] ?? "";
    }
    //Mensaje de validacion

    public function validarNuevaCuenta() {
        if(!$this->nombre) {
            self::$alertas["error"][] = "El nombre del cliente es obligatorio";
        }
        if(!$this->apellido) {
            self::$alertas["error"][] = "El apellido del cliente es obligatorio";
        }
        if(!$this->email) {
            self::$alertas["error"][] = "El email del cliente es obligatorio";
        }
        if(!$this->password) {
            self::$alertas["error"][] = "El password del cliente es obligatorio";
        } else if (strlen($this->password) < 6) {
            self::$alertas["error"][] = "El password debe tener almenos 6 caracteres de longitud";
        }
        return self::$alertas;
    }

    public function validarLogin() {
        if(!$this->email) {
            self::$alertas["error"][] = "El email del cliente es obligatorio";
        }
        if(!$this->password) {
            self::$alertas["error"][] = "El password del cliente es obligatorio";
        }
        return self::$alertas;
    }
    public function validarEmail() {
        if(!$this->email) {
            self::$alertas["error"][] = "El email del cliente es obligatorio";
        }
        
        return self::$alertas;
    }
    
    public function validarPassword() {
        if(!$this->password) {
            self::$alertas["error"][] = "El password es obligatorio";
        } else if(strlen($this->password) < 6) {
            self::$alertas["error"][] = "El password debe tener almenos 6 caracteres o mas";
        }
        
        return self::$alertas;
    }

    public function existeUsuario() {
        $query = "SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1;";

        $resultado = self::$db->query($query);

        if($resultado->num_rows) {
            self::$alertas["error"][] = "El usuario ya existe";
        }
        return $resultado;
    }
    public function hashPassword() {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function crearToken() {
        $this->token = uniqid();
    }

    public function comprobarPasswordAndVerificado($password) {
        $resultado = password_verify($password, $this->password);

        if(!$resultado || !$this->confirmado) {
            self::$alertas["error"][] = "El password es incorrecto o la cuenta no ha sido confirmada";
        } else {
            return true;
        }
    }
}