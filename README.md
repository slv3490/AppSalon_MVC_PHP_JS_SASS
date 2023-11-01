# Sobre el proyecto AppSalón 💈
Proyecto en el cual se pueden encargar citas mediante la aplicación teniendo que previamente haberse registrado medíante un formulario, donde solamente el administrador podrá ver los datos del cliente con su respectivo nombre, fecha de la cita, horario, el monto total a cobrar y poder administrar la creacion, actualizacion y eliminacion de las citas.
## Funcionalidades
- Validación para todas las funcionalidades relacionadas al back-end
- Login
- Creacion de usuarios
- Recuperar password mediante el email
- C.R.U.D para las citas
- Filtro de citas por fechas

## Instalacíon
#### Dependencias
Para instalar las dependencias se debera escribir los siguientes comandos

`npm install`

`composer install`

#### Base de datos
Este proyecto cuenta con una base de datos por lo que se debera cambiar las variables de entorno de la misma que se encuentran en la carpeta **includes/database.php**. 

Las **tablas** y **columnas** se encontraran en la carpeta de modelos.


###### AdminCita
    protected static $tabla = 'citasservicios';
    protected static $columnasDB = ["id", "hora", "cliente", "email", "telefono", "servicio", "precio"];
###### Citas
    protected static $tabla = "citas";
    protected static $columnasDB = ["id", "fecha", "hora", "usuarioId"];
###### CitaServicio
    protected static $tabla = "citasservicios";
    protected static $columnasDB = ['id', 'citasId', 'serviciosId'];
###### Servicios
    protected static $tabla = "servicios";
    protected static $columnasDB = ["id", "nombre", "precio"];
###### Usuario
    protected static $tabla = "usuarios";
    protected static $columnasDB = ["id", "nombre", "apellido", "email", "password", "telefono", "admin", "confirmado", "token"];

#### Email
Este proyecto cuenta con envíos de emails por medio de **PHPMailer** por lo que se deberá cambíar las variables de entorno dentro de la carpeta **classes/Email.php** para que la creación y recuperación de la cuenta de usuarios funcionen correctamente.

## Estructura

#### Api para consultar, guardar y eliminar las citas

````
<?php 

namespace Controllers;

use Model\Cita;
use Model\CitaServicio;
use Model\Servicios;

class APIController {
    public static function index() {
        $servicios = Servicios::all();  //Obtener todos los servicios

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
````

## 🛠 Skills
**Html, Css, Javascript, Php y MySQL**

## 🔗 Links
[![portfolio](https://img.shields.io/badge/my_portfolio-000?style=for-the-badge&logo=ko-fi&logoColor=white)](https://github.com/slv3490/Portfolio) (En Proceso)

[![linkedin](https://img.shields.io/badge/linkedin-0A66C2?style=for-the-badge&logo=linkedin&logoColor=white)](https://www.linkedin.com/in/leonel-silvera-5a9a75286/)

[![gmail](https://img.shields.io/badge/gmail-EA4335?style=for-the-badge&logo=gmail&logoColor=white)](https://mail.google.com/mail/u/0/?tab=rm&ogbl#search/leonelsilvera9%40gmail.com)

## Conclusión
Lo considero un proyecto simple pero desafiante del cual aprendí y expandí:: mis conocimientos enormemente tanto en **Php** como en **JavaScript**.
