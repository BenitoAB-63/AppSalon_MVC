<h1 class="nombre-pagina">Panel de Administración</h1>
<?php 
    include_once __DIR__."/../templates/barra.php";
?>

<h2>Buscar Citas</h2>
<div class="busqueda">
    <form action="" class="formulario">
        <div class="campo">
            <label for="fecha">Fecha</label>
            <input type="date" id="fecha" name="fecha" value="<?php echo $fecha; ?>">
        </div>
    </form>
</div>

<?php 
    if(count($citas)===0){
        echo "<h2>No hay citas en esta fecha</h2>";
    }
?>
<div class="citas-admin">
    <ul class="citas">
        <?php 
            $idCita=NULL;
            foreach($citas as $key => $cita): 
                if($idCita !== $cita->id):  
                    //Contador para el precio de todos los servicios elegidos en cada cita
                    $total=0;

            ?>
                
                <li>
                    <p>ID: <span><?php echo $cita->id; ?></span></p>

                    <p>Hora: <span><?php echo $cita->hora; ?></span></p>

                    <p>Cliente: <span><?php echo $cita->cliente; ?></span></p>

                    <p>Email: <span><?php echo $cita->email; ?></span></p>

                    <p>Teléfono: <span><?php echo $cita->telefono; ?></span></p>
                </li>
                
                <h3>Servicios</h3>
            <?php 
                //Igualamos aquí para que no nos vuelva a mostrar la misma cita. Esto pasa porque nuestra consulta nos saca los servicios de cada cita y entonces nos imprime el mismo id por cada servicio elegido para dicha cita.
                $idCita=$cita->id;
                
            endif;
            //Vamos sumando los precios de cada servicio
            $total+=$cita->precio;
            ?>
            
            <p class="servicio"><?php echo $cita->servicio." ".$cita->precio." €"; ?></p>

            <?php 
                    //Con esUltimo comprobamos en que momento han pasado todos los servicios de la cita con el id actual, en el momento que hayamos llegado al ultimo servicio mostramos el total a pagar.
                    $actual=$cita->id;
                    $proximo=$citas[$key+1]->id ?? 0;
                    if(esUltimo($actual,$proximo)){?>

                        <p class="total">Total: <span><?php echo $total." €"; ?></span></p>

                        <form action="/api/eliminar" method="POST">
                            <input type="hidden" name="id" value="<?php echo $cita->id; ?>">
                            <input type="submit" class="boton-eliminar" value="Eliminar Cita">
                        </form>

                    <?php }
          

        endforeach; 
        ?>

    </ul>
</div>

<?php 
    $script="<script src='build/js/buscador.js'></script>";

?>