<?php 

namespace Controllers;

use MVC\Router;
use Classes\Email;
use Model\Usuario;

class LoginController{
    public static function login(Router $router){
        $alertas=[];
        //Si queremos autocompletar el formulario añadimos el auth aquí y en el render. Luego en el form con un value agregamos las credenciales. De todas formas no es muy seguro asi que no lo hacemos.

        if($_SERVER['REQUEST_METHOD']==='POST'){
            $auth=new Usuario($_POST);
            $alertas=$auth->validarLogin();

            if(empty($alertas)){
                //Comprobar que el usuario existe
                $usuario=Usuario::where('email',$auth->email);
                
                if($usuario){
                    //Si el usuario existe verificamos la contraseña

                    $verificarPwd=$usuario->comprobarPassword($auth->password);

                    if($verificarPwd){
                        //Si las credenciales son correctas iniciamos la sesion
                        session_start();
                        $_SESSION['id']=$usuario->id;
                        $_SESSION['nombre']=$usuario->nombre." ".$usuario->apellido;
                        $_SESSION['email']=$usuario->email;
                        $_SESSION['login']=true;

                        //Redireccionamiento
                        if($usuario->admin === '1'){
                            //Si el usuario es un admin añadimos nueva variable de session 
                            $_SESSION['admin']=$usuario->admin ?? NULL;
                            header('Location:/admin');
                        }else{
                            header('Location:/cita');
                        }

                    }

                }else{

                    Usuario::setAlerta('error','El usuario no existe');
                    
                }
            }
            
            $alertas=Usuario::getAlertas();
           
        }

        $router->render('auth/login',[
            'alertas'=>$alertas
        ]);
    }

    public static function logout(Router $router){
        session_start();
        //Limpiamos la session
        $_SESSION=[];

        header("Location:/");

    }
    
    public static function olvide(Router $router){
        $alertas=[];
        if($_SERVER['REQUEST_METHOD']==='POST'){
            $auth=new Usuario($_POST);
            $alertas=$auth->validarEmail();

            if(empty($alertas)){
                //Buscamos el usuario en la bbdd
                $usuario=Usuario::where('email',$auth->email);

                //Si existe y esta confirmado generamos un token nuevo para dicho usuario
                if($usuario && $usuario->confirmado==='1'){
                    $usuario->crearToken();
                    //Despues de generar el token se actualiza la tabla
                    $usuario->guardar();

                    //Enviamos el email
                    Usuario::setAlerta('exito','Revisa tu email');

                    $email=new Email($usuario->email,$usuario->nombre,$usuario->token);
                    $email->enviarInstrucciones();

                }else{
                    Usuario::setAlerta('error','El usuario no existe o no esta confirmado');
                }
            }
            $alertas=Usuario::getAlertas();
        }
        
        $router->render('auth/olvide',[
            'alertas'=>$alertas
        ]);
    }

    public static function recuperar(Router $router){
        $alertas=[];
        $error=false;

        $token=s($_GET['token']);

        //Buscamos el usuario por su token

        $usuario=Usuario::where('token',$token);

        if(empty($usuario)){
            Usuario::setAlerta('error','Token no válido');
            $error=true;
        }

        if($_SERVER['REQUEST_METHOD']==='POST'){
            $auth=new Usuario($_POST);
            $alertas=$auth->validarPassword();

            if(empty($alertas)){
                $nuevoPassword=$auth->password;
                $usuario->password=$nuevoPassword;
                $usuario->hashPassword();
                $usuario->token=NULL;
                $resultado=$usuario->guardar();

                if($resultado){
                    header('Location: /');
                }

            }
            
        }

        $alertas=Usuario::getAlertas();
        $router->render('auth/recuperar',[
            'alertas'=>$alertas,
            'error'=>$error
        ]);
    }

    public static function crear(Router $router){
        //Inicializamos el usuario fuera del post para poder sanitizar las entradas de datos(crear-cuenta.php)
        $usuario=new Usuario;
        $alertas=[];
        if($_SERVER['REQUEST_METHOD']==='POST'){
            //Una vez se ejecute el post sincronizamos con los datos enviados
          
            $usuario->sincronizar($_POST);
            
            $alertas=$usuario->validarNuevaCuenta();
            
           // debuguear($usuario);
            
            //Si ha rellenado los campos correctamente
            if(empty($alertas)){
                //verificamos que el usuario no esta registrado
                $resultado=$usuario->existeUsuario();
                
                //Si esta registrado llamamos a las alertas para actualizar su impresion por pantalla
                if($resultado->num_rows){
                    $alertas= Usuario::getAlertas();

                    //si no esta registrado lo almacenamos en la bbdd
                }else{
                    //Hasheamos el password
                    $usuario->hashPassword();
                    //Generamos un token unico
                    $usuario->crearToken();
                    //Enviamos email de verificacion
                    $email=new Email($usuario->email,$usuario->nombre,$usuario->token);

                    $email->enviarConfirmacion();

                    //Crear el usuario
                    $resultado=$usuario->guardar();
                    
                    if($resultado){
                        header("Location: /mensaje");
                    }
                }

            }

       
         }
    $router->render('auth/crear-cuenta',[
        'usuario'=>$usuario,
        'alertas'=>$alertas
    ]);
    }

    public static function mensaje(Router $router){
        $router->render('/auth/mensaje');
    }

    public static function confirmar(Router $router){
        $alertas=[];
        $token=s($_GET['token']);
        $usuario=Usuario::where('token',$token);

        if(empty($usuario)){
            //Si no hay token entonces 
            Usuario::setAlerta('error','Token no válido');
        }else{
            //Si sí entonces actualizamos el usuario
            $usuario->confirmado=1;
            $usuario->token=NULL;
            $usuario->guardar();
            //Establecemos alerta de exito
            Usuario::setAlerta('exito','Cuenta comprobada correctamente');
        }

        //Mostramos alertas
        $alertas=Usuario::getAlertas();

        //Renderizado
        $router->render('/auth/confirmar-cuenta',[
            'alertas'=>$alertas
        ]);

    }

}