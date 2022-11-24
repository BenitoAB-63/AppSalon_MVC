<?php 
//Cargamos el autoload de composer
require __DIR__ . '/../vendor/autoload.php';
//Con createImmutable cargamos el archivo .env, como segundo parametro podemos pasarle el nombre del archivo si queremos
$dotenv=Dotenv\Dotenv::createImmutable(__DIR__);
//Si el archivo no existe nos marca error
$dotenv->safeLoad();

require 'funciones.php';
require 'database.php';


// Conectarnos a la base de datos
use Model\ActiveRecord;
ActiveRecord::setDB($db);