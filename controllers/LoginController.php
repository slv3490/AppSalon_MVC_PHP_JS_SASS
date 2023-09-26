<?php 

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController {
    public static function login(Router $router) {
        $alertas = [];

        if($_SERVER["REQUEST_METHOD"] === "POST") {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarLogin();

            if(empty($alertas)) {
                //Verificar que el usuario exista por su email
                $usuario = Usuario::where("email", $auth->email);
                if($usuario) {
                    //Verificar que este confirmado
                    if($usuario->comprobarPasswordAndVerificado($auth->password)) {
                        session_start();

                        $_SESSION["id"] = $usuario->id;
                        $_SESSION["nombre"] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION["email"] = $usuario->email;
                        $_SESSION["login"] = true;

                        if($usuario->admin === "1") {
                            $_SESSION["admin"] = $usuario->admin;

                            header("location: /admin");
                        } else {
                            header("location: /citas");
                        }


                    };
                } else {
                    Usuario::setAlerta("error", "Usuario no encontrado");
                }
            }
        }
        $alertas = Usuario::getAlertas();

        $router->render("auth/login", [
            "alertas" => $alertas
        ]);
    }
    public static function logout() {
        session_start();

        $_SESSION = [];

        header("location: /");
    }
    public static function olvide(Router $router) {
        $alertas = [];

        if($_SERVER["REQUEST_METHOD"] === "POST") {
            $auth = new Usuario($_POST);

            $alertas = $auth->validarEmail();

            if(empty($alertas)) {
                //Verificar que el usuario existe
                $usuario = Usuario::where("email", $auth->email);
                if($usuario && $usuario->confirmado === "1") {
                    //Generar un nuevo token
                    $usuario->crearToken();
                    $usuario->guardar();
                    //Enviar el E-mail
                    $mail = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    $mail->enviarInstrucciones();
                    //Alerta de exito
                    Usuario::setAlerta("exito", "Revisa tu E-Mail");

                } else {
                    Usuario::setAlerta("error", "El usuario no ha sido encontrado o no esta confirmado");
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render("auth/olvide-password", [
            "alertas" => $alertas
        ]);
    }
    public static function recuperar(Router $router) {

        $alertas = [];
        $error = false;
        
        $token = s($_GET["token"]);

        $usuario = Usuario::where("token", $token);

        if(empty($usuario)) {
            Usuario::setAlerta("error", "Usuario no encontrado");
            $error = true;
        }
        if($_SERVER["REQUEST_METHOD"] === "POST") {
            //Leer el nuevo password y guardarlo
            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();
        
            if(empty($alertas)) {
                //Guardar el password
                $usuario->password = $password->password;
                $usuario->password = password_hash($usuario->password, PASSWORD_BCRYPT);
                $usuario->token = null;
                $resultado = $usuario->guardar();
                
                if($resultado) {
                    header("location: /");
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render("auth/recuperar-password", [
            "alertas" => $alertas,
            "error" => $error
        ]);
    }
    public static function crear(Router $router) {
        $usuario = new Usuario;
        $alertas = [];

        if($_SERVER["REQUEST_METHOD"] === "POST") {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            if(empty($alertas)) {
                //Verificar que el usuario no este registrado
                $resultado = $usuario->existeUsuario();
                if($resultado->num_rows) {
                    $alertas = Usuario::getAlertas();
                } else {
                    //No esta registrado
                    $usuario->hashPassword();

                    //Generar un token
                    $usuario->crearToken();

                    //Enviar un Email
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    $email->enviarConfirmacion();
                    $resultado = $usuario->guardar();

                    if($resultado) {
                        header("location: /mensaje");
                    }
                }
            }
        }

        $router->render("auth/crear-cuenta", [
            "usuario" => $usuario,
            "alertas" => $alertas
        ]);
    }
    public static function mensaje(Router $router) {
        $router->render("auth/mensaje", []);
    }
    public static function confirmar(Router $router) {
        $alertas = [];

        $token = s($_GET["token"]);

        $usuario = Usuario::where('token', $token);
        if($usuario) {
            $usuario->confirmado = 1;
            $usuario->token = null;
            $usuario->guardar();
            Usuario::setAlerta("exito", "Cuenta Confirmada Correctamente");
        } else {
            Usuario::setAlerta("error", "Token No Valido");
        }

        $alertas = Usuario::getAlertas();

        $router->render("auth/confirmar-cuenta", [
            "alertas" => $alertas
        ]);
    }
}