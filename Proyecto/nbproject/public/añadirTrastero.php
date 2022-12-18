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
    $almacenEstanterias=array();
    $almacenBaldas = array();
    $almacenCajas = array();
    $mensaje="";
    $indiceNombre = 1;
    $trasteroGuardado = false;
    $tipo="guardar"; 
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
    
//    $datosTrastero['estanterias']=array();
    $datosTrastero['listadoEliminar']=array();
    $datosTrastero['guardado'] = $trasteroGuardado; 
    $datosTrastero['trastero'] = $nuevoTrastero;
    $datosTrastero['almacenEstanterias']=$almacenEstanterias;
    $datosTrastero['almacenBaldas']=$almacenBaldas;
    $datosTrastero['almacenCajas']=$almacenCajas;
    $datosTrastero['tipo'] = $tipo;
//    $datosTrastero['mensaje2']="";
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
    $mensaje = "";
}

 if(isset($_REQUEST['cerrarSesion'])){
    if(!$trasteroGuardado){
        $nuevoTrastero->eliminar($bd);
        foreach($almacenEstanterias as $estanteria){
            $estanteria->eliminar($bd);
        } 

        foreach($almacenBaldas as $balda){
            $balda->eliminar($bd);
        } 

        foreach($almacenCajas as $balda){
            $balda->eliminar($bd);
        } 
    }
    session_destroy();
    header("Location: index.php");
    die;
}else if(isset($_REQUEST['perfilUsuario'])){
    header("Location: editarPerfil.php");
}else if(isset($_POST['añadirEstanteria'])){
    $nuevaEstanteria = new Estanteria();
    $idTrastero = $nuevoTrastero->getId();
//    $estanterias = Estanteria::recuperarEstanteriasPorIdTrastero($bd, $idTrastero);
    if(empty($almacenEstanterias)){
//        $nombreEstanteria = intVal(array_key_last($estanterias));
        $nuevaEstanteria->setNumero(1);
        $nuevaEstanteria->setNombre("Estanteria 1");
    }else{
        $numeroEstanteria = Estanteria::asignarNumero($bd, $idTrastero);
        $nuevaEstanteria->setNombre("Estanteria " .($numeroEstanteria));
        $nuevaEstanteria->setNumero($numeroEstanteria);
    }
    
    $nuevaEstanteria->setIdTrastero($nuevoTrastero->getId());
    $nuevaEstanteria->añadir($bd);
    $idEstanteria= Estanteria::obtenerIdPorNombre($bd, $nuevaEstanteria->getNombre(), $nuevoTrastero->getId());
    $nuevaEstanteria->setId($idEstanteria);
//    $estanterias[]=$nuevaEstanteria;
    $almacenEstanterias = Estanteria::recuperarEstanteriasPorIdTrastero($bd, $idTrastero);
//    $almacenEstanterias[] = $nuevaEstanteria;
    $nuevaBalda = new Balda();
    $baldasRecuperadas = Balda::recuperarBaldasPorIdEstanteria($bd, $idEstanteria);
    if(empty($baldasRecuperadas)){
//        $nombreEstanteria = intVal(array_key_last($estanterias));
        $nuevaBalda->setNumero(1);
        $nuevaBalda->setNombre("Balda 1");
    }else{
        $numeroBalda = Balda::asignarNumero($bd, $idEstanteria);
        $nuevaBalda->setNombre("Balda " .($numeroBalda));
        $nuevaBalda->setNumero($numeroBalda);
    }
    $nuevaBalda->setIdEstanteria($idEstanteria);
    $nuevaBalda->añadir($bd);
//    $idBalda= Balda::obtenerIdPorNombre($bd, $nuevaBalda->getNombre(), $idEstanteria);
//    $nuevaBalda->setId($idBalda);
    $almacenBaldas = Balda::recuperarBaldasPorIdTrastero($bd, $idTrastero);
    $datosTrastero['almacenBaldas']=$almacenBaldas;
//    $datosTrastero['estanterias']= $estanterias;
    $datosTrastero['almacenEstanterias']=$almacenEstanterias;
    $_SESSION['datosTrastero']=$datosTrastero;
    echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje', 'bd'));
}else if(isset($_POST['añadirBalda'])){
    $idEstanteria = trim(filter_input(INPUT_POST, 'idEstanteria', FILTER_SANITIZE_STRING));
    $nuevaBalda = new Balda();
    $baldasRecuperadas = Balda::recuperarBaldasPorIdEstanteria($bd, $idEstanteria);
    if(empty($baldasRecuperadas)){
//        $nombreEstanteria = intVal(array_key_last($estanterias));
        $nuevaBalda->setNumero(1);
        $nuevaBalda->setNombre("Balda 1");
    }else{
        $numeroBalda = Balda::asignarNumero($bd, $idEstanteria);
        $nuevaBalda->setNombre("Balda " .($numeroBalda));
        $nuevaBalda->setNumero($numeroBalda);
    }
    $nuevaBalda->setIdEstanteria($idEstanteria);
    $nuevaBalda->añadir($bd);
//    $idBalda= Balda::obtenerIdPorNombre($bd, $nuevaBalda->getNombre(), $idEstanteria);
//    $nuevaBalda->setId($idBalda);
//    $almacenBaldas[] = $nuevaBalda;
//    $datosTrastero['estanterias']=$estanterias;
    $almacenBaldas = Balda::recuperarBaldasPorIdTrastero($bd, $nuevoTrastero->getId());
    $datosTrastero['almacenBaldas']=$almacenBaldas;
    $_SESSION['datosTrastero']=$datosTrastero;
        echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje', 'bd'));
 
}else if(isset($_POST['añadirUbicacion'])){
        if(!filter_has_var(INPUT_POST,'sinAsignar')) {
            $almacenCajas = Caja::recuperarCajasPorIdTrastero($bd, $nuevoTrastero->getId());
            $nombreEstanteria = trim(filter_input(INPUT_POST, 'estanteria', FILTER_SANITIZE_STRING));
            $nombreBalda = trim(filter_input(INPUT_POST, 'balda', FILTER_SANITIZE_STRING));
            if($nombreBalda==""){
                $mensaje2="Es necearia una balda para ubicar la caja en una estanteria. Seleccione otra opción o cree una balda nueva";
                $datosTrastero['mensaje2']=$mensaje2;
                echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje', 'bd'));
                die;
            }else{
                $idEstanteria = Estanteria::obtenerIdPorNombre($bd, $nombreEstanteria, $nuevoTrastero->getId());
                $idBalda = Balda::obtenerIdPorNombre($bd, $nombreBalda, $idEstanteria); 
                $nuevaCaja = new Caja();           
                $nuevaCaja->setIdEstanteria($idEstanteria);
                $nuevaCaja->setIdBalda($idBalda);
                $nuevaCaja->setIdTrastero($nuevoTrastero->getId());
                $numeroCaja= Caja::asignarNumero($bd, $nuevoTrastero->getId());
                if(empty($almacenCajas)){
                    $nuevaCaja->setNombre("Caja 1");
                }else{
                    $nuevaCaja->setNombre("Caja " .($numeroCaja));
                }
                $nuevaCaja->setNumero($numeroCaja);
                $nuevaCaja->añadir($bd);
                $almacenCajas = Caja::recuperarCajasPorIdTrastero($bd, $nuevoTrastero->getId());
                $datosTrastero['almacenCajas']=$almacenCajas;
                $_SESSION['datosTrastero']=$datosTrastero;

            }
         
        }else{
//           
            $nuevaCaja = new Caja();
            $nuevaCaja->setIdTrastero($nuevoTrastero->getId());
             if(empty($almacenCajas)){
                $numeroCaja = 1;
                $nuevaCaja->setNombre("Caja ".($numeroCaja));
            }else{
                $numeroCaja= Caja::asignarNumero($bd, $nuevoTrastero->getId());
                $nuevaCaja->setNombre("Caja " .($numeroCaja));
            }
            $nuevaCaja->setNumero($numeroCaja);
            $nuevaCaja->añadir($bd);
//            $idCaja=$nuevaCaja->obtenerIdPorNombre($bd, $nuevaCaja->getNombre(), $nuevoTrastero->getId());
//            $nuevaCaja->setId($idCaja);
            $almacenCajas= Caja::recuperarCajasPorIdTrastero($bd, $nuevoTrastero->getId());
            $datosTrastero['almacenCajas']=$almacenCajas;
            $_SESSION['datosTrastero']=$datosTrastero;
        }
        echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje', 'bd'));
}else if(isset($_POST['volver'])){
     echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje', 'bd'));
}else if(isset($_POST['volverAcceso'])){
    if(!$trasteroGuardado){
        $nuevoTrastero->eliminar($bd);
        foreach($almacenEstanterias as $estanteria){
            $estanteria->eliminar($bd);
        } 

        foreach($almacenBaldas as $balda){
            $balda->eliminar($bd);
        } 

        foreach($almacenCajas as $balda){
            $balda->eliminar($bd);
        } 
    }
    
    $_SESSION['datosTrastero'] = array();

    header("Location: acceso.php");
    
}else if(isset($_POST['guardar'])){
    $nombreTrastero = trim(filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING));
    
    if($nombreTrastero!=""){
        if(!Trasteros::existeNombre($bd, $nombreTrastero, $idUsuario)){
            $nuevoTrastero->actualizarNombre($bd, $nombreTrastero);
            $trasteroGuardado = true;
            $datosTrastero['guardado']=$trasteroGuardado;
            $_SESSION['datosTrastero'] = $datosTrastero;

            $mensaje = "Su trastero se ha creado correctamente. Pulse volver para volver a la página principal";
            echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje', 'bd'));
   
        }else{
            $mensaje = "Ya existe un trastero para este usuario con este nombre.";
            echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje', 'bd'));
        }
    }else{
        $mensaje = "El nombre del trastero no puede estar vacío.";
        echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje', 'bd'));
    } 
     
}else if(isset($_POST['eliminarEstanteria'])){
    $idEstanteria=trim(filter_input(INPUT_POST, 'idEstanteria', FILTER_SANITIZE_STRING));
    
    foreach($almacenEstanterias as $clave=>$valor){
        if($valor->getId()==$idEstanteria){
            $valor->eliminar($bd);
        }
    }
    
    foreach($almacenBaldas as $clave=>$valor){
        if($valor->getIdEstanteria()==$idEstanteria){
             $valor->eliminar($bd);
        }
    }
    
    foreach($almacenCajas as $clave=>$valor){
        if($valor->getIdEstanteria()==$idEstanteria){
            $valor->eliminar($bd);
        }
    }
    
    $datosTrastero['almacenEstanterias']= Estanteria::recuperarEstanteriasPorIdTrastero($bd, $nuevoTrastero->getId());
    $datosTrastero['almacenBaldas']= Balda::recuperarBaldasPorIdTrastero($bd, $nuevoTrastero->getId());
    $datosTrastero['almacenCajas']=$almacenCajas;
    $_SESSION['datosTrastero']=$datosTrastero;
    
    echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje', 'bd'));
    
}else if(isset($_POST['eliminarBalda'])){
    $numeroEstanteria = trim(filter_input(INPUT_POST, 'numeroEstanteria', FILTER_SANITIZE_STRING));
    $numeroBalda = trim(filter_input(INPUT_POST, 'numeroBalda', FILTER_SANITIZE_STRING));
    $idBalda = trim(filter_input(INPUT_POST, 'idBalda', FILTER_SANITIZE_STRING));
    
    foreach($almacenBaldas as $clave=>$valor){
        if($valor->getId()==$idBalda){
            $valor->eliminar($bd);
        }
    }
    
    foreach($almacenCajas as $clave=>$valor){
        if($valor->getIdBalda()==$idBalda){
            $valor->eliminar($bd);
        }
    }
    
    $datosTrastero['almacenBaldas']= Balda::recuperarBaldasPorIdTrastero($bd, $nuevoTrastero->getId());
    $datosTrastero['almacenCajas']=$almacenCajas;
    $_SESSION['datosTrastero']=$datosTrastero;
    
     echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje', 'bd'));
    
}else if(isset($_POST['eliminarCaja'])){
    $idCaja = trim(filter_input(INPUT_POST, 'idCaja', FILTER_SANITIZE_STRING));
    foreach($almacenCajas as $clave=>$valor){
        if($valor->getId()==$idCaja){
            unset($almacenCajas[$clave]);
            $valor->eliminar($bd);
        }
    }
    
//    $datosTrastero['almacenEstanterias']=$almacenEstanterias;
//    $datosTrastero['almacenBaldas']=$almacenBaldas;
    $datosTrastero['almacenCajas']=$almacenCajas;
    $_SESSION['datosTrastero']=$datosTrastero;
    
    echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje', 'bd'));
}else{
    echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje', 'bd'));
}


