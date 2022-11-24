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
    Trasteros
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
    $mensaje="";
    $indiceNombre = 1;
    $trasteroGuardado = false;
    
    $usuario = $_SESSION['usuario'];
    $idUsuario = $usuario->getId();
    $nombreValido=false;
    for($i=0; $i<$indiceNombre;$i++){
        $nombreTrastero= "Trastero " .$indiceNombre;
        if(!Trasteros::existeNombre($bd, $nombreTrastero, $idUsuario)){
            $nuevoTrastero=new Trasteros();  
            $nuevoTrastero->setIdUsuario($idUsuario);
            $nuevoTrastero->setNombre($nombreTrastero);
            $nuevoTrastero->guardarTrastero($bd);
            $nuevoTrastero = $nuevoTrastero->recuperarTrasteroPorNombre($bd, $nombreTrastero);
     }else{
        $indiceNombre++; 
     }
    }
    $datosTrastero['guardado'] = $trasteroGuardado; 
    $datosTrastero['trastero'] = $nuevoTrastero;
    $datosTrastero['almacenEstanterias']=$almacenEstanterias;
    $datosTrastero['almacenBaldas']=$almacenBaldas;
    $datosTrastero['almacenCajas']=$almacenCajas;
    $datosTrastero['estanterias']=$estanterias;
    $datosTrastero['tipo'] = "guardar";
    $_SESSION['datosTrastero']=$datosTrastero;
}else{
    $usuario = $_SESSION['usuario'];
    $idUsuario = $usuario->getId();
    $datosTrastero=$_SESSION['datosTrastero'];
    $estanterias = $datosTrastero['estanterias'];
    $almacenEstanterias = $datosTrastero['almacenEstanterias'];
    $almacenBaldas =$datosTrastero['almacenBaldas'];
    $almacenCajas =$datosTrastero['almacenCajas'];
    $nuevoTrastero =$datosTrastero['trastero'];
    $trasteroGuardado = $datosTrastero['guardado'];
    $mensaje = "";
}

if(isset($_POST['añadirEstanteria'])){
    $estanterias[]= array();
    $nuevaEstanteria = new Estanteria();
    $nombreEstanteria = intVal(array_key_last($estanterias));
    $nuevaEstanteria->setNombre("Estanteria " .($nombreEstanteria+1));
    $nuevaEstanteria->setIdTrastero($nuevoTrastero->getId());
    $nuevaEstanteria->añadirEstanteria($bd);
    $idEstanteria= Estanteria::obtenerIdPorNombre($bd, $nuevaEstanteria->getNombre(), $datosTrastero['trastero']->getId());
    $nuevaEstanteria->setId($idEstanteria);
    $almacenEstanterias[] = $nuevaEstanteria;
    $datosTrastero['estanterias']= $estanterias;
    $datosTrastero['almacenEstanterias']=$almacenEstanterias;
    $_SESSION['datosTrastero']=$datosTrastero;
    echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje'));
}else if(isset($_POST['añadirBalda'])){
    $numeroEstanteria = trim(filter_input(INPUT_POST, 'numeroEstanteria', FILTER_SANITIZE_STRING));
    $nombreEstanteria = trim(filter_input(INPUT_POST, 'nombreEstanteria', FILTER_SANITIZE_STRING));
    $baldas[]= array();
    $nuevaBalda = new Balda();
    $estanterias[intval($numeroEstanteria)][]=$baldas;
    $baldasRecuperadas = $estanterias[intval($numeroEstanteria)];
    $nombreBalda = intVal(array_key_last($baldasRecuperadas));
    $nuevaBalda->setNombre("Balda " .($nombreBalda+1));
    $idEstanteria = Estanteria::obtenerIdPorNombre($bd, $nombreEstanteria, $nuevoTrastero->getId());
    $nuevaBalda->setIdEstanteria($idEstanteria);
    $nuevaBalda->añadirBalda($bd);
    $idBalda= Balda::obtenerIdPorNombre($bd, $nuevaBalda->getNombre(), $idEstanteria);
    $nuevaBalda->setId($idBalda);
    $almacenBaldas[] = $nuevaBalda;
    $datosTrastero['almacenBaldas']=$almacenBaldas;
    $datosTrastero['estanterias'] = $estanterias;
    $_SESSION['datosTrastero']=$datosTrastero;
        echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje'));
 
}else if(isset($_POST['añadirCaja'])){
    echo $blade->run('ubicacionCaja', compact('datosTrastero'));
}else if(isset($_POST['añadirUbicacion'])){
        if(!filter_has_var(INPUT_POST,'sinAsignar')) {
            $nombreEstanteria = trim(filter_input(INPUT_POST, 'estanteria', FILTER_SANITIZE_STRING));
            $nombreBalda = trim(filter_input(INPUT_POST, 'balda', FILTER_SANITIZE_STRING));
            $nuevaCaja = new Caja();
            $idEstanteria;
            $idBaldaNueva;
            foreach ($almacenEstanterias as $clave=>$valor){
                if($valor->getNombre()==$nombreEstanteria){
                    $idEstanteria=$valor->getId();
                }
            }
            foreach ($almacenBaldas as $clave=>$valor){
                if($valor->getNombre()== $nombreBalda&&$valor->getIdEstanteria()==$idEstanteria){
                    $idBaldaNueva=$valor->getId();
                }
            }
            
            
            $nuevaCaja->setIdEstanteria($idEstanteria);
            $nuevaCaja->setIdBalda($idBaldaNueva);
            $nuevaCaja->setIdTrastero($nuevoTrastero->getId());
            $nombreCaja;
            if(empty($almacenCajas)){
                $nombreCaja = 1;
                $nuevaCaja->setNombre("Caja ".($nombreCaja));
            }else{
                $nombreCaja = intVal(array_key_last($almacenCajas));
                $nuevaCaja->setNombre("Caja " .($nombreCaja+2));
            }
            $nuevaCaja->añadirCaja($bd);
            $idCaja=$nuevaCaja->obtenerIdPorNombre($bd, $nuevaCaja->getNombre(), $nuevoTrastero->getId());
            $nuevaCaja->setId($idCaja);
            $almacenCajas[]=$nuevaCaja;
            $datosTrastero['almacenCajas']=$almacenCajas;
            $datosTrastero['estanterias']= $estanterias;
            $_SESSION['datosTrastero']=$datosTrastero;

        }else{
//           
            $nuevaCaja = new Caja();
            $nuevaCaja->setIdTrastero($nuevoTrastero->getId());
             if(empty($almacenCajas)){
                $nombreCaja = 1;
                $nuevaCaja->setNombre("Caja ".($nombreCaja));
            }else{
                $nombreCaja = intVal(array_key_last($almacenCajas));
                $nuevaCaja->setNombre("Caja " .($nombreCaja+2));
            }
            $nuevaCaja->añadirCaja($bd);
            $idCaja=$nuevaCaja->obtenerIdPorNombre($bd, $nuevaCaja->getNombre(), $nuevoTrastero->getId());
            $nuevaCaja->setId($idCaja);
            $almacenCajas[]= $nuevaCaja;
            $datosTrastero['almacenCajas']=$almacenCajas;
            $datosTrastero['estanterias']= $estanterias;
            $_SESSION['datosTrastero']=$datosTrastero;
        }
        echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje'));
}else if(isset($_POST['volver'])){
    echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje'));
}else if(isset($_POST['volverAcceso'])){
    if(!$trasteroGuardado){
        $nuevoTrastero->eliminar($bd);
        foreach($almacenEstanterias as $clave=>$valor){
            $valor->eliminar($bd);
        } 

        foreach($almacenBaldas as $clave=>$valor){
            $valor->eliminar($bd);
        } 

        foreach($almacenCajas as $clave=>$valor){
            $valor->eliminar($bd);
        } 
    }
    
    $_SESSION['datosTrastero'] = array();
//    $_SESSION['almacenEstanterias'] = array();
//    $_SESSION['almacenBaldas'] = array();
//    $_SESSION['almacenCajas'] = array();
//    $_SESSION['estanterias'] = array();
//    
    header("Location: acceso.php");
    
}else if(isset($_POST['guardar'])){
    $nombreTrastero = trim(filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING));
    
    if($nombreTrastero!=""){
        if(!Trasteros::existeNombre($bd, $nombreTrastero, $idUsuario)){
            $nuevoTrastero->actualizarNombre($bd, $nombreTrastero);
            $trasteroGuardado = true;
            $datosTrastero['guardado']=$trasteroGuardado;
            $_SESSION['datosTrastero'] = $datosTrastero;
//            $_SESSION['datosTrastero'] = array();
//            $_SESSION['almacenBaldas'] = array() ;
//            $_SESSION['almacenCajas'] = array();
//            $_SESSION['estanterias'] = array();
//            $_SESSION['guardado'] = $trasteroGuardado;
//            
            $mensaje = "Su trastero se ha creado correctamente. Pulse volver para volver a la página principal";
            echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje'));
//            header ("Location: acceso.php");    
        }else{
            $mensaje = "Ya existe un trastero para este usuario con este nombre.";
            echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje'));
        }
    }else{
        $mensaje = "El nombre del trastero no puede estar vacío.";
        echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje'));
    } 
     
}else if(isset($_POST['eliminarEstanteria'])){
    $numeroEstanteria=trim(filter_input(INPUT_POST, 'numeroEstanteria', FILTER_SANITIZE_STRING));
    $idEstanteria=trim(filter_input(INPUT_POST, 'idEstanteria', FILTER_SANITIZE_STRING));
    
    unset($estanterias[$numeroEstanteria]);
    
    foreach($almacenEstanterias as $clave=>$valor){
        if($valor->getId()==$idEstanteria){
            unset($almacenEstanterias[$clave]);
            $valor->eliminar($bd);
        }
    }
    
    foreach($almacenBaldas as $clave=>$valor){
        if($valor->getIdEstanteria()==$idEstanteria){
            unset($almacenBaldas[$clave]);
             $valor->eliminar($bd);
        }
    }
    
    foreach($almacenCajas as $clave=>$valor){
        if($valor->getIdEstanteria()==$idEstanteria){
            unset($almacenCajas[$clave]);
            $valor->eliminar($bd);
        }
    }
    
    $datosTrastero['almacenEstanterias']=$almacenEstanterias;
    $datosTrastero['almacenBaldas']=$almacenBaldas;
    $datosTrastero['almacenCajas']=$almacenCajas;
    $datosTrastero['estanterias']= $estanterias;
    $_SESSION['datosTrastero']=$datosTrastero;
    
    echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje'));
    
}else if(isset($_POST['eliminarBalda'])){
    $numeroEstanteria = trim(filter_input(INPUT_POST, 'numeroEstanteria', FILTER_SANITIZE_STRING));
    $numeroBalda = trim(filter_input(INPUT_POST, 'numeroBalda', FILTER_SANITIZE_STRING));
    $idBalda = trim(filter_input(INPUT_POST, 'idBalda', FILTER_SANITIZE_STRING));
    
    unset($estanterias[$numeroEstanteria][$numeroBalda]);
    
    foreach($almacenBaldas as $clave=>$valor){
        if($valor->getId()==$idBalda){
            unset($almacenBaldas[$clave]);
            $valor->eliminar($bd);
        }
    }
    
    foreach($almacenCajas as $clave=>$valor){
        if($valor->getIdBalda()==$idBalda){
            unset($almacenCajas[$clave]);
            $valor->eliminar($bd);
        }
    }
    
    $datosTrastero['almacenEstanterias']=$almacenEstanterias;
    $datosTrastero['almacenBaldas']=$almacenBaldas;
    $datosTrastero['almacenCajas']=$almacenCajas;
    $datosTrastero['estanterias']= $estanterias;
    $_SESSION['datosTrastero']=$datosTrastero;
    
    echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje'));
    
}else if(isset($_POST['eliminarCaja'])){
    $idCaja = trim(filter_input(INPUT_POST, 'idCaja', FILTER_SANITIZE_STRING));
    foreach($almacenCajas as $clave=>$valor){
        if($valor->getId()==$idCaja){
            unset($almacenCajas[$clave]);
            $valor->eliminar($bd);
        }
    }
    
    $datosTrastero['almacenEstanterias']=$almacenEstanterias;
    $datosTrastero['almacenBaldas']=$almacenBaldas;
    $datosTrastero['almacenCajas']=$almacenCajas;
    $datosTrastero['estanterias']= $estanterias;
    $_SESSION['datosTrastero']=$datosTrastero;
    
    echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje'));
}else{
    echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje'));
}


