<h1 class="nombre-pagina">Crear Servicios</h1>
<p class="descripcion-pagina">Administración de Servicios</p>
<?php include_once __DIR__."/../templates/barra.php"; ?>
<?php include_once __DIR__."/../templates/alertas.php"; ?>
<form action="/servicios/crear" method="POST" class="formulario">
    <?php include_once "formulario.php"; ?>
    <input type="submit" class="boton" value="Guardar Servicio">
</form>