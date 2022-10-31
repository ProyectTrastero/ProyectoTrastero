<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */
require "../vendor/autoload.php";

use eftec\bladeone\BladeOne;
use Dotenv\Dotenv;

use App\{
    BD,
    Usuario,
    Validacion
};

// Inicializa el acceso a las variables de entorno
$dotenv = Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();



// Inicializa el acceso a las variables de entorno
$views = __DIR__ . '/../vistas';
$cache = __DIR__ . '/../cache';
$blade = new BladeOne($views, $cache, BladeOne::MODE_DEBUG);


if(isset($_POST['añadirEstanteria'])){
    echo $blade->run('añadirTrastero');
}else if(isset($_POST['añadirCaja'])){
    if(isset($_POST['volver'])){
        echo $blade->run('añadirTrastero');
    }else if(isset($_POST['añadirUbicacion'])){
        echo $blade->run('añadirTrastero');
    }else{
        echo $blade->run('ubicacionCaja');
    } 
}else if(isset($_POST['volverAcceso'])){
    header("Location: acceso.php");
}else if(isset($_POST['guardar'])){
    
}else{
    echo $blade->run('añadirTrastero');
}
