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
    $almacenEstanterias=array();
    $almacenBaldas = array();
    $almacenCajas = array();
    
    $datosTrastero['almacenEstanterias']=$almacenEstanterias;
    $datosTrastero['almacenBaldas']=$almacenBaldas;
    $datosTrastero['almacenCajas']=$almacenCajas;
    $datosTrastero['estanterias']=$estanterias;
    $_SESSION['datosTrastero']=$datosTrastero;
}else{
    $datosTrastero=$_SESSION['datosTrastero'];
    $estanterias = $datosTrastero['estanterias'];
    $almacenEstanterias = $datosTrastero['almacenEstanterias'];
    $almacenBaldas =$datosTrastero['almacenBaldas'];
    $almacenCajas =$datosTrastero['almacenCajas'];
    
}

if(isset($_POST['añadirEstanteria'])){
    $estanterias[]= array();
    $nuevaEstanteria = new Estanteria();
    $nombreEstanteria = intVal(array_key_last($estanterias));
    $nuevaEstanteria->setNombre("Estanteria " .($nombreEstanteria+1));
    $almacenEstanterias[] = $nuevaEstanteria;
    $datosTrastero['estanterias']= $estanterias;
    $datosTrastero['almacenEstanterias']=$almacenEstanterias;
    $_SESSION['datosTrastero']=$datosTrastero;
    echo $blade->run('añadirTrastero', compact('estanterias','almacenEstanterias', 'almacenBaldas', 'almacenCajas'));
}else if(isset($_POST['añadirBalda'])){
    $numeroEstanteria = trim(filter_input(INPUT_POST, 'numeroEstanteria', FILTER_SANITIZE_STRING));
    $baldas[]= array();
    $nuevaBalda = new Balda();
    $estanterias[intval($numeroEstanteria)][]=$baldas;
    $baldasRecuperadas = $estanterias[intval($numeroEstanteria)];
    $nombreBalda = intVal(array_key_last($baldasRecuperadas));
    $nuevaBalda->setNombre("Balda " .($nombreBalda+1));
    $nuevaBalda->setIdEstanteria(intval($numeroEstanteria)+1);
    $almacenBaldas[] = $nuevaBalda;
    $datosTrastero['almacenBaldas']=$almacenBaldas;
    $datosTrastero['estanterias'] = $estanterias;
    $_SESSION['datosTrastero']=$datosTrastero;
    echo $blade->run('añadirTrastero', compact('estanterias', 'almacenEstanterias', 'almacenBaldas', 'almacenCajas'));
 
}else if(isset($_POST['añadirCaja'])){
    echo $blade->run('ubicacionCaja', compact('estanterias'));
}else if(isset($_POST['añadirUbicacion'])){
        if(!filter_has_var(INPUT_POST,'sinAsignar')) {
            $numeroEstanteria = intVal(trim(filter_input(INPUT_POST, 'estanteria', FILTER_SANITIZE_STRING)));
            $numeroBalda = intVal(trim(filter_input(INPUT_POST, 'balda', FILTER_SANITIZE_STRING)));
            $nuevaCaja = new Caja();
            $nuevaCaja->setIdEstanteria(intVal($numeroEstanteria));
            $nuevaCaja->setIdBalda(intVal($numeroBalda));
            if(empty($almacenCajas)){
                $nombreCaja = 1;
                $nuevaCaja->setNombre("Caja ".($nombreCaja));
            }else{
                $nombreCaja = intVal(array_key_last($almacenCajas));
                $nuevaCaja->setNombre("Caja " .($nombreCaja+2));
            }
            
            $almacenCajas[]=$nuevaCaja;
            $datosTrastero['almacenCajas']=$almacenCajas;
            $datosTrastero['estanterias']= $estanterias;
            $_SESSION['datosTrastero']=$datosTrastero;

        }else{
            $numeroEstanteria="";
            $numeroBalda="";
            $nuevaCaja = new Caja();
            $nuevaCaja->setIdEstanteria($numeroEstanteria);
            $nuevaCaja->setIdBalda($numeroBalda);
             if(empty($almacenCajas)){
                $nombreCaja = 1;
                $nuevaCaja->setNombre("Caja ".($nombreCaja));
            }else{
                $nombreCaja = intVal(array_key_last($almacenCajas));
                $nuevaCaja->setNombre("Caja " .($nombreCaja+2));
            }
            $almacenCajas[]= $nuevaCaja;
            
            $datosTrastero['almacenCajas']=$almacenCajas;
            $datosTrastero['estanterias']= $estanterias;
            $_SESSION['datosTrastero']=$datosTrastero;
        }
        echo $blade->run('añadirTrastero', compact('estanterias','almacenEstanterias', 'almacenBaldas', 'almacenCajas'));
}else if(isset($_POST['volver'])){
    echo $blade->run('añadirTrastero', compact('estanterias','almacenEstanterias', 'almacenBaldas', 'almacenCajas'));
}else if(isset($_POST['volverAcceso'])){
    header("Location: acceso.php");
}else if(isset($_POST['guardar'])){
    $trasteroGuardado = false;
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
    
    if($trasteroGuardado){
        header ("Location: acceso.php");
    }else{
       echo $blade->run('añadirTrastero', compact('estanterias','almacenEstanterias', 'almacenBaldas', 'almacenCajas')); 
    }
     
}else if(isset($_POST['eliminarEstanteria'])){
    $numeroEstanteria=trim(filter_input(INPUT_POST, 'numeroEstanteria', FILTER_SANITIZE_STRING));
    unset($estanterias[$numeroEstanteria]);
    
    foreach($almacenEstanterias as $clave=>$valor){
        $nombreEstanteria = "Estanteria ".(intVal($numeroEstanteria)+1);
        if($valor->getNombre()==$nombreEstanteria){
            unset($almacenEstanterias[$clave]);
        }
    }
    
    foreach($almacenBaldas as $clave=>$valor){
        $idEstanteria = intVal($numeroEstanteria)+1;
        if($valor->getIdEstanteria()==$idEstanteria){
            unset($almacenBaldas[$clave]);
        }
    }
    
    foreach($almacenCajas as $clave=>$valor){
        if((intVal($valor->getIdEstanteria())-1)==intVal($numeroEstanteria)){
            unset($almacenCajas[$clave]);
        }
    }
    
    $datosTrastero['almacenEstanterias']=$almacenEstanterias;
    $datosTrastero['almacenBaldas']=$almacenBaldas;
    $datosTrastero['almacenCajas']=$almacenCajas;
    $datosTrastero['estanterias']= $estanterias;
    $_SESSION['datosTrastero']=$datosTrastero;
    
    echo $blade->run('añadirTrastero', compact('estanterias','almacenEstanterias', 'almacenBaldas', 'almacenCajas'));
    
}else if(isset($_POST['eliminarBalda'])){
    $numeroEstanteria= trim(filter_input(INPUT_POST, 'numeroEstanteria', FILTER_SANITIZE_STRING));
    $numeroBalda=trim(filter_input(INPUT_POST, 'numeroBalda', FILTER_SANITIZE_STRING));
    unset($estanterias[$numeroEstanteria][$numeroBalda]);
    
    foreach($almacenBaldas as $clave=>$valor){
        $idEstanteria = intVal($numeroEstanteria)+1;
        $nombreBalda = "Balda ".(intVal($numeroBalda)+1);
        if(($valor->getIdEstanteria()==$idEstanteria)&&($valor->getNombre()==$nombreBalda)){
            unset($almacenBaldas[$clave]);
        }
    }
    
    foreach($almacenCajas as $clave=>$valor){
        if((intVal($valor->getIdBalda())-1)==(intVal($numeroBalda))){
            unset($almacenCajas[$clave]);
        }
    }
    
    $datosTrastero['almacenEstanterias']=$almacenEstanterias;
    $datosTrastero['almacenBaldas']=$almacenBaldas;
    $datosTrastero['almacenCajas']=$almacenCajas;
    $datosTrastero['estanterias']= $estanterias;
    $_SESSION['datosTrastero']=$datosTrastero;
    
    echo $blade->run('añadirTrastero', compact('estanterias','almacenEstanterias', 'almacenBaldas', 'almacenCajas'));
    
}else if(isset($_POST['eliminarCaja'])){
    $nombreCaja= trim(filter_input(INPUT_POST, 'nombreCaja', FILTER_SANITIZE_STRING));
    foreach($almacenCajas as $clave=>$valor){
        if($valor->getNombre()==$nombreCaja){
            unset($almacenCajas[$clave]);
        }
    }
    
    $datosTrastero['almacenEstanterias']=$almacenEstanterias;
    $datosTrastero['almacenBaldas']=$almacenBaldas;
    $datosTrastero['almacenCajas']=$almacenCajas;
    $datosTrastero['estanterias']= $estanterias;
    $_SESSION['datosTrastero']=$datosTrastero;
    
    echo $blade->run('añadirTrastero', compact('estanterias','almacenEstanterias', 'almacenBaldas', 'almacenCajas'));
}else{
    echo $blade->run('añadirTrastero', compact('estanterias','almacenEstanterias', 'almacenBaldas', 'almacenCajas'));
}


