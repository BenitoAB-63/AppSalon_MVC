<?php 

namespace Model;

class Servicio extends ActiveRecord{
    //Base de datos
    protected static $columnasDB=['id','nombre','precio'];
    protected static $tabla='servicios';

    public $id;
    public $nombre;
    public $precio;

    public function __construct($args=[]){
        $this->id=$args['id'] ?? NULL;
        $this->nombre=$args['nombre'] ?? '';
        $this->precio=$args['precio'] ?? '';
        
    }

    public function validarServicio(){
        if(!$this->nombre){
            self::$alertas['error'][]='Introduzca el nombre del Servicio';
        }elseif(!is_string($this->nombre)){
            self::$alertas['error'][]='Nombre no válido';
        }

        if(!$this->precio){
            self::$alertas['error'][]='Introduzca el precio del Servicio';
        }elseif(!is_numeric($this->precio)){
            self::$alertas['error'][]='Precio no válido';
        }

        return self::$alertas;
    }

}