<?php

namespace Model;


class Usuario extends ActiveRecord{
    //Base de datos
    protected static $tabla='usuarios';
    protected static $columnasDB=['id','nombre','apellido','email','telefono','password','admin','confirmado','token'];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $telefono;
    public $password;
    public $admin;
    public $confirmado;
    public $token;

    public function __construct($args=[]){
        $this->id=$args['id'] ?? NULL;
        $this->nombre=$args['nombre'] ?? '';
        $this->apellido=$args['apellido'] ?? '';
        $this->email=$args['email'] ?? '';
        $this->telefono=$args['telefono'] ?? '';
        $this->password=$args['password'] ?? '';
        $this->admin=$args['admin'] ?? '0';
        $this->confirmado=$args['confirmado'] ?? '0';
        $this->token=$args['token'] ?? NULL;
        
    }

    //Mensajes de validacion para la creacion de una cuenta
    public function validarNuevaCuenta(){
        if(!$this->nombre){
            self::$alertas['error'][]='El nombre es obligatorio';
        }

        if(!$this->apellido){
            self::$alertas['error'][]='El apellido es obligatorio';
        }

        if(!$this->email){
            self::$alertas['error'][]='El email es obligatorio';
        }

        if(!$this->telefono){
            self::$alertas['error'][]='El telefono es obligatorio';
        }

        if(!$this->password){
            self::$alertas['error'][]='La contraseña es obligatorio';
        }
        if(strlen($this->password)<6){
            self::$alertas['error'][]='La contraseña debe tener al menos 6 caracteres';
        }

        return self::$alertas;
    }

    //Funcion para comprobar la contraseña
    public function comprobarPassword($password){
        
        $verificacion=password_verify($password,$this->password);
        
        if(!$verificacion ){
            self::$alertas['error'][]="Contraseña incorrecta";
        }elseif(!$this->confirmado){
            self::$alertas['error'][]="El usuario no esta confirmado. Mire su mail.";
        }else{
            return true;
        }


    }

    //Validar el Login
    public function validarLogin(){
        if(!$this->email){
            self::$alertas['error'][]="El email es obligatorio";
        }

        if(!$this->password){
            self::$alertas['error'][]="La contraseña es obligatoria";
        }
        return self::$alertas;
    }

    //validar el email cuando se olvida la contraseña
    public function validarEmail(){
        if(!$this->email){
            self::$alertas['error'][]="El email es obligatorio";
        }
        return self::$alertas;
    }

    //Validar la contraseña cuando se cambie
    public function validarPassword(){
        if(!$this->password){
            self::$alertas['error'][]="La contraseña es obligatoria";
        }
        if(strlen($this->password)<6){
            self::$alertas['error'][]="La contraseña debe tener al menos 6 caracteres";
        }

        return self::$alertas;
    }

    //Revisamos si previamente existe el usuario que va a crearse
    public function existeUsuario(){
        $query="SELECT * FROM ".self::$tabla." WHERE email= '".$this->email."' LIMIT 1";

        $resultado=self::$db->query($query);
        
        if($resultado->num_rows){
            self::$alertas['error'][]='El usuario ya esta registrado';
        }

        return $resultado;
        

    }

    public function hashPassword(){
        $this->password=password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function crearToken(){
        $this->token=uniqid();
    }

 

}