<h1 class="nombre-pagina">Olvide mi password</h1>
<p class="descripcion-pagina">Escribe tu E-Mail para recuperar tu contraseña</p>

<?php 
    include_once __DIR__ . "/../templates/alertas.php";
?>

<form class="formulario login" method="POST" action="/olvide">
    <div class="campo">
        <label for="email">E-mail</label>
        <input type="email" placeholder="Tu Email" id="email" name="email">
    </div>

    <input class="boton" type="submit" value="Recuperar Cuenta">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia Sesion.</a>
    <a href="/crear-cuenta">¿No tienes cuenta? Crear una.</a>
</div>

<?php 
   $script = "<script src='build/js/ancho.js'></script>"
?>