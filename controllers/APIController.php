<?php 

namespace Controllers;

use Model\Cita;
use Model\CitaServicio;
use Model\Servicios;

class APIController {
    public static function index() {
        $servicios = Servicios::all();

        echo json_encode($servicios);
    }

    public static function guardar() {
        //Almacena la cita y devuelve el Id
        $cita = new Cita($_POST);
        $resultado = $cita->guardar();

        $id = $resultado["id"];

        //Almacena los servicios con el ID de la cita        
        $idServicios = explode(",", $_POST["servicios"]);

        foreach($idServicios as $idServicio) {
            $args = [
                "citasId" => $id,
                "serviciosId" => $idServicio
            ];
            $citaServicio = new CitaServicio($args);
            $citaServicio->guardar();
        }

        echo json_encode(["resultado" => $resultado]);
    }
    public static function eliminar() {
        $id = $_POST["id"];

        if($_SERVER["REQUEST_METHOD"] === "POST") {
            $cita = Cita::find($id);
            $cita->eliminar();

            header("location: " . $_SERVER["HTTP_REFERER"]);
        }

    }
}