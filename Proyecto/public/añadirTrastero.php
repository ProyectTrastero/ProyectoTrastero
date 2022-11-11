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

try {
    $bd = BD::getConexion();
} catch (PDOException $error) {
    echo $blade->run("cnxbderror", compact('error'));
    die;
}
session_start();

if(empty($_SESSION['datosTrastero'])){
    $datosTrastero = array();
    $estanterias = array();
    $cajas = array();
    $datosTrastero['cajas']= $cajas;
    $datosTrastero['estanterias']=$estanterias;
    $_SESSION['datosTrastero']=$datosTrastero;
}else{
    $datosTrastero=$_SESSION['datosTrastero'];
    $estanterias = $datosTrastero['estanterias'];
    $cajas = $datosTrastero['cajas'];
}

if(isset($_POST['añadirEstanteria'])){
    $estanterias[]= array();
    $datosTrastero['estanterias']= $estanterias;
    $_SESSION['datosTrastero']=$datosTrastero;
    echo $blade->run('añadirTrastero', compact('estanterias', 'cajas'));
}else if(isset($_POST['añadirBalda'])){
    $numeroEstanteria = trim(filter_input(INPUT_POST, 'numeroEstanteria', FILTER_SANITIZE_STRING));
    $baldas[]= array();
    $estanterias[intval($numeroEstanteria)][]=$baldas;
    $datosTrastero['estanterias'] = $estanterias;
    $_SESSION['datosTrastero']=$datosTrastero;
    echo $blade->run('añadirTrastero', compact('estanterias', 'cajas'));
 
}else if(isset($_POST['añadirCaja'])){
    echo $blade->run('ubicacionCaja', compact('estanterias'));
}else if(isset($_POST['añadirUbicacion'])){
        if(!filter_has_var(INPUT_POST,'sinAsignar')) {
            $numeroEstanteria=intVal(trim(filter_input(INPUT_POST, 'estanteria', FILTER_SANITIZE_STRING)));
            $numeroBalda= intVal(trim(filter_input(INPUT_POST, 'balda', FILTER_SANITIZE_STRING)));
//            $numeroCaja=count($cajas)+1;
            $nuevaCaja = array();
            $nuevaCaja['numeroEstanteria']=$numeroEstanteria;
            $nuevaCaja['numeroBalda']=$numeroBalda;
//            $nuevaCaja->setNumero($numeroCaja);
//            $nuevaCaja->setIdEstanteria($numeroEstanteria);
//            $nuevaCaja->setIdBalda($numeroBalda);
            $cajas[]= $nuevaCaja;
            
            $datosTrastero['cajas']=$cajas;
            $datosTrastero['estanterias']= $estanterias;
            $_SESSION['datosTrastero']=$datosTrastero;

        }else{
            
            $numeroEstanteria="";
            $numeroBalda="";
            $nuevaCaja = array();
            $nuevaCaja['numeroEstanteria']=$numeroEstanteria;
            $nuevaCaja['numeroBalda']=$numeroBalda;
            $cajas[]= $nuevaCaja;
            
            $datosTrastero['cajas']=$cajas;
            $datosTrastero['estanterias']= $estanterias;
            $_SESSION['datosTrastero']=$datosTrastero;
            
        }
       echo $blade->run('añadirTrastero', compact('estanterias', 'cajas'));
}else  if(isset($_POST['volver'])){
       echo $blade->run('añadirTrastero', compact('estanterias', 'cajas'));
}else if(isset($_POST['volverAcceso'])){
    $_SESSION['nuevoTrastero']=array();
    //Cambiar por la paginad de acceso que no está terminada
    echo $blade->run('añadirTrastero', compact('estanterias', 'cajas'));
    //header("Location: acceso.php");
}else if(isset($_POST['guardar'])){
    $nuevoTrastero=new Trastero();
    //Nombre usuario que está en sesión;
    $idUsuario=7;
    $nombreTrastero=trim(filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING));
    $nuevoTrastero->setIdUsuario($idUsuario);
    $nuevoTrastero->setNombre($nombreTrastero);
    $nuevoTrastero->añadirTrastero($bd);
    $trasteroGuardado=$nuevoTrastero->recuperarTrasteroPorUsuarioyNombre($bd, $idUsuario, $nombreTrastero);
    $prueba= $trasteroGuardado->getId();
    echo $prueba;
    
    
    
    
    
    header ("Location: acceso.php");    
}else if(isset($_POST['eliminarEstanteria'])){
    $numeroEstanteria=trim(filter_input(INPUT_POST, 'numeroEstanteria', FILTER_SANITIZE_STRING));
    unset($estanterias[$numeroEstanteria]);
    foreach($cajas as $clave=>$valor){
        
        if((intVal($valor['numeroEstanteria'])-1)==intVal($numeroEstanteria)){
            unset($cajas[$clave]);
        }
    }
    $datosTrastero['cajas']=$cajas;
    $datosTrastero['estanterias']= $estanterias;
    $_SESSION['datosTrastero']=$datosTrastero;
    
   echo $blade->run('añadirTrastero', compact('estanterias', 'cajas'));
    
}else if(isset($_POST['eliminarBalda'])){
    $numeroEstanteria= trim(filter_input(INPUT_POST, 'numeroEstanteria', FILTER_SANITIZE_STRING));
    $numeroBalda=trim(filter_input(INPUT_POST, 'numeroBalda', FILTER_SANITIZE_STRING));
    unset($estanterias[$numeroEstanteria][$numeroBalda]);
    foreach($cajas as $clave=>$valor){
        if((intVal($valor['numeroBalda'])-1)==(intVal($numeroBalda))){
            unset($cajas[$clave]);
        }
    }
    
    $datosTrastero['cajas']=$cajas;
    $datosTrastero['estanterias']= $estanterias;
    $_SESSION['datosTrastero']=$datosTrastero;
    
    echo $blade->run('añadirTrastero', compact('estanterias', 'cajas'));
    
}else if(isset($_POST['eliminarCaja'])){
    $numeroCaja= trim(filter_input(INPUT_POST, 'numeroCaja', FILTER_SANITIZE_STRING));
    foreach($cajas as $clave=>$valor){
        if($clave==$numeroCaja){
            unset($cajas[$clave]);
        }
    }
    $datosTrastero['cajas']=$cajas;
    $datosTrastero['estanterias']= $estanterias;
    $_SESSION['datosTrastero']=$datosTrastero;
    
    echo $blade->run('añadirTrastero', compact('estanterias', 'cajas'));
}else{
    echo $blade->run('añadirTrastero', compact('estanterias', 'cajas'));
}


