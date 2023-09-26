<?php

namespace Controllers;

use MVC\Router;

class CitasController {
    public static function index(Router $router) {
        session_start();

        autenticado();

        $router->render("citas/citas", [
            "nombre" => $_SESSION["nombre"],
            "id" => $_SESSION["id"]
        ]);
    }
}