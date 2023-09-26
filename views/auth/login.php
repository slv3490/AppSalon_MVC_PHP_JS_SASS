<h1 class="nombre-pagina">Login</h1>
<p class="descripcion-pagina">Inicia sesion con tus datos</p>

<?php 
    include_once __DIR__ . "/../templates/alertas.php";
?>

<form class="formulario" method="POST" action="/">
    <div class="campo">
        <label for="email">E-mail</label>
        <input type="email" placeholder="Tu Email" id="email" name="email">
    </div>

    <div class="campo">
        <label for="password">Contraseña</label>
        <input type="password" placeholder="Tu Contraseña" id="password" name="password">
    </div>

    <input class="boton" type="submit" value="Iniciar sesion">
</form>

<div class="acciones">
    <a href="/crear-cuenta">¿No tienes cuenta? Crear una.</a>
    <a href="/olvide">¿Olvidaste tu contraseña?</a>
</div>