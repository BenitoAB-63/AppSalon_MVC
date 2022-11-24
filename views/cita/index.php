<h1 class="nombre-pagina">Crear nueva cita</h1>
<?php 
    include_once __DIR__."/../templates/barra.php";
?>

<div id="app">
    <nav class="tabs">
        <!-- Con data- podemos definir atributos personalizados para las etiquetas de html//dataset en JS -->
        <button class="actual" type="button" data-paso="1">Servicios</button>
        <button type="button" data-paso="2">Información Cita</button>
        <button type="button" data-paso="3">Resumen</button>
    </nav>

    <div id="paso-1" class="seccion">
        <h2>Servicios</h2>
        <p class="text-center">Elige tus servicios</p>
        <div id="servicios" class="listado-servicios"></div>
    </div>

    <div id="paso-2" class="seccion">
        <h2>Tus datos y cita</h2>
        <p class="text-center">Coloca tus datos y la fecha de tu cita</p>

        <form class="formulario">
            <div class="campo">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" placeholder="Tu nombre" value="<?php echo $nombre; ?>" disabled>
            </div>
            
            <div class="campo">
                <label for="fecha">Fecha</label>
                <input type="date" id="fecha" name="fecha" min="<?php echo date('Y-m-d',strtotime('+1 day'));//Minimo un dia despues del dia actual ?>">
            </div>

            <div class="campo">
                <label for="hora">Hora</label>
                <input type="time" id="hora" name="hora" >
            </div>
            <input type="hidden" id="id" value="<?php echo $id; ?>">
        </form>
    </div>

    <div id="paso-3" class="seccion resumen">
        
   
    </div>

    <div class="paginacion">
        <button id="anterior" class="boton">
            &laquo; Anterior
        </button>

        <button id="siguiente" class="boton">
             Siguiente &raquo;
        </button>
    </div>
</div>

<?php 
//Agregamos JS cuando estemos en esta pagina->layout.php
$script="

    <script src='//cdn.jsdelivr.net/npm/sweetalert2@11'></script>

    <script src='build/js/app.js'></script>

";
?>