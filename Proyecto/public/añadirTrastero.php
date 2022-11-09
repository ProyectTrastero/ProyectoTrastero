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
    Validacion, 
    Estanteria, 
    Balda,
    Caja,
    Trastero
};
// Inicializa el acceso a las variables de entorno
$dotenv = Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();


// Inicializa el acceso a las variables de entorno
$views = __DIR__ . '/../vistas';
$cache = __DIR__ . '/../cache';
$blade = new BladeOne($views, $cache, BladeOne::MODE_DEBUG);

session_start();

if(empty($_SESSION['datosTrastero'])){
    $datosTrastero = array();
    $estanterias = array();
    $cajas = array();
//    $baldas = array();
//    $cajas = array();
    $datosTrastero['cajas']= $cajas;
    $datosTrastero['estanterias']=$estanterias;
//    $datosTrastero['baldas']=$baldas;
//    $datosTrastero['cajas']=$cajas;
    $_SESSION['datosTrastero']=$datosTrastero;
    
}else{
    $datosTrastero=$_SESSION['datosTrastero'];
    $estanterias = $datosTrastero['estanterias'];
    $cajas = $datosTrastero['cajas'];
    
//    $baldas=$datosTrastero['baldas'];
//    $cajas = $datosTrastero ['cajas'];
   
}



if(isset($_POST['añadirEstanteria'])){
    $estanterias[]= array();
    $datosTrastero['estanterias']= $estanterias;
    $_SESSION['datosTrastero']=$datosTrastero;
    echo $blade->run('añadirTrastero', compact('estanterias'));
}else if(isset($_POST['añadirBalda'])){
    $numeroEstanteria = trim(filter_input(INPUT_POST, 'numeroEstanteria', FILTER_SANITIZE_STRING));
    $baldas[]= array();
    $estanterias[intval($numeroEstanteria)][]=$baldas;
    $datosTrastero['estanterias'] = $estanterias;
    $_SESSION['datosTrastero']=$datosTrastero;
    echo $blade->run('añadirTrastero', compact('estanterias'));
 
}else if(isset($_POST['añadirCaja'])){
    echo $blade->run('ubicacionCaja');
}else if(isset($_POST['añadirUbicacion'])){
        if(!filter_has_var(INPUT_POST,'sinAsignar')) {
//            $numeroBalda = trim(filter_input(INPUT_POST, 'numeroBalda', FILTER_SANITIZE_STRING));
//            $caja= new Caja();
//            $estanterias[intval($numeroEstanteria)]=$baldas;
//            $datosTrastero['estanterias'] = $estanterias;
//            $_SESSION['datosTrastero']=$datosTrastero;
//            
//            $datosTrastero['estanterias'] = $estanterias;
//            $_SESSION['datosTrastero']=$datosTrastero;
        }else{
//              //Modificar cuando esté la clase preparada
//           $cajas[]=array();
//           $datosTrastero['cajas']= $cajas;
//           $_SESSION['datosTrastero']=$datosTrastero;
        }
       echo $blade->run('añadirTrastero', compact('estanterias'));
}else  if(isset($_POST['volver'])){
        echo $blade->run('añadirTrastero', compact('estanterias'));
}else if(isset($_POST['volverAcceso'])){
    $_SESSION['nuevoTrastero']=array();
    //Cambiar por la paginad de acceso que no está terminada
     echo $blade->run('añadirTrastero', compact('estanterias'));
    //header("Location: acceso.php");
}else if(isset($_POST['guardar'])){
    $nuevoTrastero['nombre']=trim(filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING));
    $_SESSION['nuevoTrastero']= array();
    header ("Location: acceso.php");    
}else if(isset($_POST['eliminarEstanteria'])){
    $numeroEstanteria=trim(filter_input(INPUT_POST, 'numeroEstanteria', FILTER_SANITIZE_STRING));
    unset($estanterias[$numeroEstanteria]);
  
    $datosTrastero['estanterias']= $estanterias;
    $_SESSION['datosTrastero']=$datosTrastero;
    
   echo $blade->run('añadirTrastero', compact('estanterias'));
    
}else if(isset($_POST['eliminarBalda'])){
    $numeroEstanteria= trim(filter_input(INPUT_POST, 'numeroEstanteria', FILTER_SANITIZE_STRING));
    $numeroBalda=trim(filter_input(INPUT_POST, 'numeroBalda', FILTER_SANITIZE_STRING));
    unset($estanterias[$numeroEstanteria][$numeroBalda]);
  
    $datosTrastero['estanterias']= $estanterias;
    $_SESSION['datosTrastero']=$datosTrastero;
    
    echo $blade->run('añadirTrastero', compact('estanterias'));
    
}else{
   echo $blade->run('añadirTrastero', compact('estanterias'));
}


