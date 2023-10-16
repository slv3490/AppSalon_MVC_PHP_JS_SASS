<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;
use SendinBlue\Client\Configuration;


class Email {

    public $nombre;
    public $email;
    public $token;

    public function __construct($nombre, $email, $token)
    {
        $this->nombre = $nombre;
        $this->email = $email;
        $this->token = $token;
    }

    public function enviarConfirmacion() {
        //Crear el objeto de Email
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV["EMAIL_HOST"];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV["EMAIL_PORT"];
        $mail->Port = 587;
        $mail->Username = $_ENV["EMAIL_USER"];
        $mail->Password = $_ENV["EMAIL_PASS"];

        $mail->setFrom('slv3490@gmail.com', "Leonel");
        $mail->addAddress($this->email);
        $mail->Subject = 'Confirma tu cuenta';
        //Set HTML
        $mail->isHTML(true);
        $mail->CharSet = "UTF-8";

        $contenido = "<html>" ;
        $contenido .= "<p><strong>Hola " . $this->nombre . " has creado una cuenta en AppSalon, solo debes confirmarla presionando en el siguiente enlace </strong></p>";
        $contenido .= "<p><a href='" . $_ENV["APP_URL"] . "/confirmar-cuenta?token=" . $this->token . "'>Confirmar Cuenta</a></p>";
        $contenido .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        //Enviar el correo
        $mail->send();
    }
    public function enviarInstrucciones() {
        //Crear el objeto de Email
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV["EMAIL_HOST"];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV["EMAIL_PORT"];
        $mail->Username = $_ENV["EMAIL_USER"];
        $mail->Password = $_ENV["EMAIL_PASS"];

        $mail->setFrom('slv3490@gmail.com');
        $mail->addAddress($this->email);
        $mail->Subject = 'Confirma tu cuenta';
        //Set HTML
        $mail->isHTML(true);
        $mail->CharSet = "UTF-8";

        $contenido = "<html>" ;
        $contenido .= "<p><strong>Hola " . $this->nombre . " presiona en el siguiente enlace para reestablecer tu cuenta.</strong></p>";
        $contenido .= "<p><a href='" . $_ENV["APP_URL"] ."/recuperar?token=" . $this->token . "'>Reestablecer Cuenta</a></p>";
        $contenido .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        //Enviar el correo
        $mail->send();        
    }
}