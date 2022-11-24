<?php 

namespace Controllers;

use Model\AdminCita;
use Model\Cita;
use Model\CitaServicio;
use Model\Servicio;

class APIController{
    public static function index(){
        //Obtenemos todos los servicios. ALL()->ActiveRecord
        $servicios=Servicio::all();
        echo json_encode($servicios);
    }

    public static function guardar(){

        //Almacena la cita y devuelve el ID
        $cita=new Cita($_POST);
        $resultado=$cita->guardar();//Insertamos en la bbdd
        $id=$resultado['id'];

        //Almacena los servicios con el id de la cita
        $idServicios=explode(',',$_POST['servicios']);
        foreach($idServicios as $idServicio){
            $args=[
                'citaId'=>$id,
                'servicioId'=>$idServicio
            ];
            $citaServicio=new CitaServicio($args);
            $citaServicio->guardar();
        }

       // Retornamos respuesta
        $respuesta=[
            'servicios'=>$idServicios,
            'resultado'=>$resultado,
            
        ];

       echo json_encode($respuesta);
    }

    public static function eliminar(){
        if($_SERVER['REQUEST_METHOD']==='POST'){
            $id=$_POST['id'];
            
            $cita=Cita::find($id);
            $cita->eliminar();
            
            //Con referer nos devuelve a donde estabamos previamente
            header('Location:'.$_SERVER['HTTP_REFERER']);
        }
    }
}