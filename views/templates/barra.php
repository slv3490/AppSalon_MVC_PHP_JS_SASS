<div class="barra">
    <p>Hola: <?php echo $_SESSION["nombre"] ?? ""; ?></p>
    <a class="boton" href="/logout">Cerrar Sesion</a>
</div>

<?php if(isset($_SESSION["admin"])) : ?>

    <div class="barra-servicios">
        <a class="boton" href="/admin">Ver Citas</a>
        <a class="boton" href="/servicios">Ver Servicios</a>
        <a class="boton" href="/servicios/crear">Nuevo servicios</a>
    </div>

<?php endif; ?>