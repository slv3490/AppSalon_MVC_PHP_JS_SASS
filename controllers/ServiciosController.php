<?php 

namespace Controllers;

use Model\Servicios;
use MVC\Router;

class ServiciosController{
    public static function index(Router $router) {
        session_start();
        isAdmin();

        $servicios = Servicios::all();
        
        $router->render("servicios/index", [
            "nombre" => $_SESSION["nombre"],
            "servicios" => $servicios
        ]);
    }

    public static function crear(Router $router) {
        session_start();
        isAdmin();

        $servicios = new Servicios;
        $alertas = [];
        
        if($_SERVER["REQUEST_METHOD"] === "POST") {
            $servicios->sincronizar($_POST);
            
            $alertas = $servicios->validar();

            if(empty($alertas)) {
                $servicios->guardar();
                header("location: /servicios");
            }
        }

        $router->render("servicios/crear", [
            "nombre" => $_SESSION["nombre"],
            "servicios" => $servicios,
            "alertas" => $alertas
        ]);
    }

    public static function actualizar(Router $router) {
        session_start();
        isAdmin();

        if(!is_numeric($_GET["id"])) return;
        $servicios = Servicios::find($_GET["id"]);

        $alertas = [];

        if($_SERVER["REQUEST_METHOD"] === "POST") {
            $servicios->sincronizar($_POST);

            $alertas = $servicios->validar();

            if(empty($alertas)) {
                $servicios->guardar();

                header("location: /servicios");
            }            
        }

        $router->render("servicios/actualizar", [
            "nombre" => $_SESSION["nombre"],
            "servicios" => $servicios,
            "alertas" => $alertas
        ]);
    }

    public static function eliminar() {
        session_start();
        isAdmin();

        if($_SERVER["REQUEST_METHOD"] === "POST") {
            $id = $_POST["id"];
            $servicios = Servicios::find($id);
            $servicios->eliminar();
            header("location: /servicios");
        }
    }
}