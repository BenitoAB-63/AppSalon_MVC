<?php 

namespace Controllers;

use MVC\Router;
use Model\AdminCita;

class AdminController{
    public static function index(Router $router){
        session_start();

        //Verificamos que realmente inicio sesion un admin
        isAdmin();

        //Obtenemos la fecha, si no se ha seleccionado se muestra la del dia actual
        $fechaS=$_GET['fecha'] ?? date('Y-m-d');

        //Separamos los valores de la fecha
        $fecha=explode('-',$fechaS);
        
        //Depuramos la fecha con checkdate(mes,dia,año)
        if(!checkdate($fecha[1],$fecha[2],$fecha[0])){
            header("Location:/404");
        }

        
        //Consultar la BBDD
        $consulta=" SELECT citas.id, citas.hora, CONCAT(usuarios.nombre,' ',usuarios.apellido) as cliente, usuarios.email, usuarios.telefono,  servicios.nombre as servicio, servicios.precio FROM citas left join citasservicios on citasservicios.citaId=citas.id left join servicios     on servicios.id=citasservicios.servicioId left join usuarios on usuarios.id=citas.usuarioId where fecha='${fechaS}' ";

        $citas=AdminCita::SQL($consulta);
        

        $router->render('admin/index',[
            'nombre'=>$_SESSION['nombre'],
            'citas'=>$citas,
            'fecha'=>$fechaS
        ]);
    }
}