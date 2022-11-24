<?php 

namespace Controllers;

use MVC\Router;
use Model\Servicio;

class ServicioController{
    public static function index(Router $router){
        session_start();

        //Revisamos que realmente inicion sesion un admin
        isAdmin();
        
        $servicios=Servicio::all();

        //Mostramos alertas al acabar de realizar una accion mediante el crud y redirigir
        if($_SESSION['servicioCreado']===true){
            $alertas=Servicio::setAlerta('exito','El servicio fue creado correctamente');
            $alertas=Servicio::getAlertas();
            $_SESSION['servicioCreado']=false;
            
        }
        if($_SESSION['servicioActualizado']===true){
            $alertas=Servicio::setAlerta('exito','El servicio fue actualizado correctamente');
            $alertas=Servicio::getAlertas();
            $_SESSION['servicioActualizado']=false;
            
        }
        if($_SESSION['servicioEliminado']===true){
            $alertas=Servicio::setAlerta('exito','El servicio fue Eliminado correctamente');
            $alertas=Servicio::getAlertas();
            $_SESSION['servicioEliminado']=false;
            
        }

        
        $router->render('/servicios/index',[
            'nombre'=>$_SESSION['nombre'],
            'servicios'=>$servicios,
            'alertas'=>$alertas
            
        ]);
    }

    public static function crear(Router $router){
        session_start();
        isAdmin();

        $alertas=[];
        $servicio=new Servicio($_POST);

        if($_SERVER['REQUEST_METHOD']==='POST'){
            
            $alertas=$servicio->validarServicio();

            if(empty($alertas)){
                $servicio->guardar();      
                //Si se crea un nuevo servicio creamos una variables session a true
                $_SESSION['servicioCreado']=true;          
                header('Location:/servicios');
                
                
            }
            


        }
        $alertas=Servicio::getAlertas();
        

        $router->render('/servicios/crear',[
            'nombre'=>$_SESSION['nombre'],
            'servicio'=>$servicio,
            'alertas'=>$alertas
        ]);
    }

    public static function actualizar(Router $router){
        session_start();
        isAdmin();
        
        if(!is_numeric($_GET['id'])) return;

        $alertas=[];
        $servicio=Servicio::find($_GET['id']);
        
       
        if($_SERVER['REQUEST_METHOD']==='POST'){

            $servicio->sincronizar($_POST);
          
            $alertas=$servicio->validarServicio();
            
            if(empty($alertas)){
                $servicio->guardar();
                $_SESSION['servicioActualizado']=true;
                header('Location:/servicios');
            }
            
            //debuguear($alertas);
        }

        $alertas=Servicio::getAlertas();
        

        $router->render('/servicios/actualizar',[
            'nombre'=>$_SESSION['nombre'],
            'servicio'=>$servicio,
            'alertas'=>$alertas
        ]);
    }

    public static function eliminar(){
        session_start();
        isAdmin();

        if($_SERVER['REQUEST_METHOD']==='POST'){
            $id=$_POST['id'];
            $servicio=Servicio::find($id);

            if($servicio){
                $servicio->eliminar();
                $_SESSION['servicioEliminado']=true;
                header('Location:/servicios');
            }
        }


    }
    


}