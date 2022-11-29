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
    $idTrastero = $_SESSION['idTrastero'];
    $nuevoTrastero= Trasteros::recuperarTrasteroPorId($bd,$idTrastero);
    $almacenEstanterias= Estanteria::recuperarEstanteriasPorIdTrastero($bd, $nuevoTrastero->getId());
    $almacenBaldas = array();
    foreach ($almacenEstanterias as $estanteria){
        $idEstanteria = $estanteria->getId();
        $baldasEstanteria = Balda::recuperarBaldasPorIdEstanteria($bd, $idEstanteria);
        foreach($baldasEstanteria as $balda){
             $almacenBaldas[]=$balda;
        }     
    }
    $almacenCajas = Caja::recuperarCajasPorIdTrastero($bd, $nuevoTrastero->getId());
    
//    $prueba = Caja::recuperarCajasPorIdTrastero($bd, $idTrastero);
    $mensaje="";
    $tipo="modificar";
    $trasteroGuardado = false;
    
    $usuario = $_SESSION['usuario'];
    $idUsuario = $usuario->getId();

    $creados=array();
    $eliminados=array();
    $datosTrastero['eliminados']=$eliminados;
    $datosTrastero['creados']=$creados;
    $datosTrastero['guardado'] = $trasteroGuardado; 
    $datosTrastero['trastero'] = $nuevoTrastero;
    $datosTrastero['almacenEstanterias']=$almacenEstanterias;
    $datosTrastero['almacenBaldas']=$almacenBaldas;
    $datosTrastero['almacenCajas']=$almacenCajas;
    $datosTrastero['tipo'] = $tipo;
    $_SESSION['datosTrastero']=$datosTrastero;
}else{
    $usuario = $_SESSION['usuario'];
    $idUsuario = $usuario->getId();
    $datosTrastero=$_SESSION['datosTrastero'];
    $tipo=$datosTrastero['tipo'];
    $almacenEstanterias = $datosTrastero['almacenEstanterias'];
    $almacenBaldas =$datosTrastero['almacenBaldas'];
    $almacenCajas =$datosTrastero['almacenCajas'];
    $nuevoTrastero =$datosTrastero['trastero'];
    $trasteroGuardado = $datosTrastero['guardado'];
    $creados=$datosTrastero['creados'];
    $eliminados = $datosTrastero['eliminados'];
    $mensaje = "";
}

if(isset($_POST['añadirEstanteria'])){
    $nuevaEstanteria = new Estanteria();
    $idTrastero = $nuevoTrastero->getId();
    $estanterias = Estanteria::recuperarEstanteriasPorIdTrastero($bd, $idTrastero);
    if(empty($estanterias)){
        $nombreEstanteria = intVal(array_key_last($estanterias));
        $nuevaEstanteria->setNombre("Estanteria " .($nombreEstanteria+1));
    }else{
        $nombreEstanteria = intVal(array_key_last($estanterias));
        $nuevaEstanteria->setNombre("Estanteria " .($nombreEstanteria+2));
    }
    
    $nuevaEstanteria->setIdTrastero($nuevoTrastero->getId());
    $nuevaEstanteria->añadir($bd);
    $idEstanteria= Estanteria::obtenerIdPorNombre($bd, $nuevaEstanteria->getNombre(), $nuevoTrastero->getId());
    $nuevaEstanteria->setId($idEstanteria);
    $almacenEstanterias[] = $nuevaEstanteria;
    $creados[] =  $nuevaEstanteria;
    $datosTrastero['creados'] = $creados;
    $nuevaBalda = new Balda();
    $baldasRecuperadas = Balda::recuperarBaldasPorIdEstanteria($bd, $idEstanteria);
    $nombreBalda = intVal(array_key_last($baldasRecuperadas));
    $nuevaBalda->setNombre("Balda " .($nombreBalda+1));
    $nuevaBalda->setIdEstanteria($idEstanteria);
    $nuevaBalda->añadir($bd);
    $idBalda= Balda::obtenerIdPorNombre($bd, $nuevaBalda->getNombre(), $idEstanteria);
    $nuevaBalda->setId($idBalda);
    $almacenBaldas[] = $nuevaBalda;
    $creados[] =  $nuevaBalda;
    $datosTrastero['creados'] = $creados;
    $datosTrastero['almacenBaldas']=$almacenBaldas;
    $datosTrastero['almacenEstanterias']=$almacenEstanterias;
    $_SESSION['datosTrastero']=$datosTrastero;
    echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje'));
}else if(isset($_POST['añadirBalda'])){
    $idEstanteria = trim(filter_input(INPUT_POST, 'idEstanteria', FILTER_SANITIZE_STRING));
    $nuevaBalda = new Balda();
    $baldasRecuperadas = Balda::recuperarBaldasPorIdEstanteria($bd, $idEstanteria);
    $nombreBalda = intVal(array_key_last($baldasRecuperadas));
    $nuevaBalda->setNombre("Balda " .($nombreBalda+2));
    $nuevaBalda->setIdEstanteria($idEstanteria);
    $nuevaBalda->añadir($bd);
    $idBalda= Balda::obtenerIdPorNombre($bd, $nuevaBalda->getNombre(), $idEstanteria);
    $nuevaBalda->setId($idBalda);
    $almacenBaldas[] = $nuevaBalda;
    $creados[] =  $nuevaBalda;
    $datosTrastero['creados'] = $creados;
    $datosTrastero['almacenBaldas']=$almacenBaldas;
    $_SESSION['datosTrastero']=$datosTrastero;
        echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje'));
 
}else if(isset($_POST['añadirCaja'])){
    echo $blade->run('ubicacionCaja', compact('datosTrastero'));
}else if(isset($_POST['añadirUbicacion'])){
        if(!filter_has_var(INPUT_POST,'sinAsignar')) {
            $nombreEstanteria = trim(filter_input(INPUT_POST, 'estanteria', FILTER_SANITIZE_STRING));
            $nombreBalda = trim(filter_input(INPUT_POST, 'balda', FILTER_SANITIZE_STRING));
            $idEstanteria = Estanteria::obtenerIdPorNombre($bd, $nombreEstanteria, $nuevoTrastero->getId());
            $idBalda = Balda::obtenerIdPorNombre($bd, $nombreBalda, $idEstanteria);        
            $nuevaCaja = new Caja();  
            $nuevaCaja->setIdEstanteria($idEstanteria);
            $nuevaCaja->setIdBalda($idBalda);
            $nuevaCaja->setIdTrastero($nuevoTrastero->getId());
            $nombreCaja;
            if(empty($almacenCajas)){
                $nombreCaja = 1;
                $nuevaCaja->setNombre("Caja ".($nombreCaja));
            }else{
                $nombreCaja = intVal(array_key_last($almacenCajas));
                $nuevaCaja->setNombre("Caja " .($nombreCaja+2));
            }
            $nuevaCaja->añadir($bd);
            $idCaja=$nuevaCaja->obtenerIdPorNombre($bd, $nuevaCaja->getNombre(), $nuevoTrastero->getId());
            $nuevaCaja->setId($idCaja);
            $almacenCajas[]=$nuevaCaja;
            $creados[]=$nuevaCaja;
            $datosTrastero['creados']= $creados;
            $datosTrastero['almacenCajas']=$almacenCajas;
            $_SESSION['datosTrastero']=$datosTrastero;

        }else{
            $nuevaCaja = new Caja();
            $nuevaCaja->setIdTrastero($nuevoTrastero->getId());
             if(empty($almacenCajas)){
                $nombreCaja = 1;
                $nuevaCaja->setNombre("Caja ".($nombreCaja));
            }else{
                $nombreCaja = intVal(array_key_last($almacenCajas));
                $nuevaCaja->setNombre("Caja " .($nombreCaja+2));
            }
            $nuevaCaja->añadir($bd);
            $idCaja=$nuevaCaja->obtenerIdPorNombre($bd, $nuevaCaja->getNombre(), $nuevoTrastero->getId());
            $nuevaCaja->setId($idCaja);
            $almacenCajas[]= $nuevaCaja;
            $creados[] = $nuevaCaja;
            $datosTrastero['creados'] = $creados;
            $datosTrastero['almacenCajas']=$almacenCajas;
            $_SESSION['datosTrastero']=$datosTrastero;
        }
        echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje'));
}else if(isset($_POST['volver'])){
    echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje'));
}else if(isset($_POST['volverAcceso'])){
    if(!$trasteroGuardado){
        foreach($creados as $clave=>$valor){
            $valor->eliminar($bd);
        } 

        foreach($eliminados as $clave=>$valor){
            $valor->añadir($bd);
        }
        $_SESSION['datosTrastero'] = array();

        header("Location: acceso.php");
    }else{
        $_SESSION['datosTrastero'] = array();

        header("Location: acceso.php");
    }
    
}else if(isset($_POST['modificar'])){
    
    $trasteroGuardado = true;
    $datosTrastero['guardado']=$trasteroGuardado;
    $_SESSION['datosTrastero'] = $datosTrastero;
    $mensaje = "Su trastero se ha modificado correctamente. Pulse volver para volver a la página principal";
    echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje'));
     
}else if(isset($_POST['eliminarEstanteria'])){
    $idEstanteria=trim(filter_input(INPUT_POST, 'idEstanteria', FILTER_SANITIZE_STRING));
    
    foreach($almacenEstanterias as $clave=>$valor){
        if($valor->getId()==$idEstanteria){
            unset($almacenEstanterias[$clave]);
            $valor->eliminar($bd);
            $eliminados[]=$valor;
        }
    }
    
    foreach($almacenBaldas as $clave=>$valor){
        if($valor->getIdEstanteria()==$idEstanteria){
            unset($almacenBaldas[$clave]);
             $valor->eliminar($bd);
             $eliminados[]=$valor;
        }
    }
    
    foreach($almacenCajas as $clave=>$valor){
        if($valor->getIdEstanteria()==$idEstanteria){
            unset($almacenCajas[$clave]);
            $valor->eliminar($bd);
            $eliminados[]=$valor;
        }
    }
    $datosTrastero['eliminados']=$eliminados;
    $datosTrastero['almacenEstanterias']=$almacenEstanterias;
    $datosTrastero['almacenBaldas']=$almacenBaldas;
    $datosTrastero['almacenCajas']=$almacenCajas;
    $_SESSION['datosTrastero']=$datosTrastero;
    
    echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje'));
    
}else if(isset($_POST['eliminarBalda'])){
    $idBalda = trim(filter_input(INPUT_POST, 'idBalda', FILTER_SANITIZE_STRING));
    
    foreach($almacenBaldas as $clave=>$valor){
        if($valor->getId()==$idBalda){
            unset($almacenBaldas[$clave]);
            $valor->eliminar($bd);
            $eliminados[]=$valor;
        }
    }
    
    foreach($almacenCajas as $clave=>$valor){
        if($valor->getIdBalda()==$idBalda){
            unset($almacenCajas[$clave]);
            $valor->eliminar($bd);
            $eliminados[]=$valor;
        }
    }
    $datosTrastero['eliminados']=$eliminados;
    $datosTrastero['almacenBaldas']=$almacenBaldas;
    $datosTrastero['almacenCajas']=$almacenCajas;
    $_SESSION['datosTrastero']=$datosTrastero;
    
    echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje'));
    
}else if(isset($_POST['eliminarCaja'])){
    $idCaja = trim(filter_input(INPUT_POST, 'idCaja', FILTER_SANITIZE_STRING));
    foreach($almacenCajas as $clave=>$valor){
        if($valor->getId()==$idCaja){
            unset($almacenCajas[$clave]);
            $valor->eliminar($bd);
            $eliminados[]=$valor;
        }
    }
    $datosTrastero['eliminados']=$eliminados;
    $datosTrastero['almacenBaldas']=$almacenBaldas;
    $datosTrastero['almacenCajas']=$almacenCajas;
    $_SESSION['datosTrastero']=$datosTrastero;
    
    echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje'));
}else{
    echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje'));
}


