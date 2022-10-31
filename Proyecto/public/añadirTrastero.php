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

session_start();

if(empty($_SESSION['nuevoTrastero'])){
    $nuevoTrastero=array();
    $numeroEstanterias=0;
    
    $_SESSION['numeroEstanterias']=$numeroEstanterias;      
    $_SESSION['nuevoTrastero']=$nuevoTrastero;
    
}else{
    $nuevoTrastero=$_SESSION['nuevoTrastero'];
    $numeroEstanterias=$_SESSION['numeroEstanterias'];
}



if(isset($_POST['añadirEstanteria'])){
    $numeroEstanterias++;
    $nuevaEstanteria=array();
    $nuevoTrastero[$numeroEstanterias]=$nuevaEstanteria;
    $_SESSION['nuevoTrastero']=$nuevoTrastero;
    $_SESSION['numeroEstanterias']=$numeroEstanterias;
    echo $blade->run('añadirTrastero', compact('nuevoTrastero'));
}else if(isset($_POST['añadirCaja'])){
    if(isset($_POST['volver'])){
        echo $blade->run('añadirTrastero');
    }else if(isset($_POST['añadirUbicacion'])){
        if(!filter_has_var(INPUT_POST,'sinAsignar')) {
            $_SESSION['ucbalda']= 1;
            $_SESSION['ucestanteria']=2;
        }else{
            $_SESSION['ucbalda']="";
            $_SESSION['ucestanteria']="";
        }
        echo $blade->run('añadirTrastero');
    }else{
        echo $blade->run('ubicacionCaja');
    }
    echo $blade->run('añadirTrastero', compact('nuevoTrastero'));
}else if(isset($_POST['volverAcceso'])){
    $_SESSION['nuevoTrastero']=array();
     echo $blade->run('añadirTrastero');
    //header("Location: acceso.php");
}else if(isset($_POST['guardar'])){
    $nuevoTrastero['nombre']=trim(filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING));
    $_SESSION['nuevoTrastero']= array();
    header ("Location: acceso.php");    
}elseif(isset($_POST['eliminarEstanteria'])){
    echo "probando";
}else{
    echo $blade->run('añadirTrastero');
}


