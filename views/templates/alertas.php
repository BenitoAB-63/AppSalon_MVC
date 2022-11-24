<?php 
    //Dentro de la key 'error' tenemos las alertas asi que realizamos un foreach sobre la clave..
    foreach($alertas as $key => $mensajes){
        //..y otro sobre los mensajes 
        foreach($mensajes as $mensaje){
            ?>
            <!-- $key='error' -->
            <div class="alerta <?php echo $key; ?>">
                <?php echo $mensaje; ?>
            </div>
            
            <?php
        }
    }

?>