<h1 class="nombre-pagina">Recuperar contraseña</h1>
<p class="descripcion-pagina">Escribe tu nueva contraseña.</p>

<?php 
    include_once __DIR__."/../templates/alertas.php";
?>

<?php if($error) return; ?>

<form method="POST" class="formulario">

    <div class="campo">
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" placeholder="Nueva Contraseña">

    </div>

    <input type="submit" value="Guardar nueva contraseña" class="boton">



</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia Sesión</a>
    <a href="/crear-cuenta">¿No tienes una cuenta? Crea una</a>
</div>