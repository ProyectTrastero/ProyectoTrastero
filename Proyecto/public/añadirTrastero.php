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

//Conexión con la base de datos
try {
    $bd = BD::getConexion();
} catch (PDOException $error) {
    echo $blade->run("cnxbderror", compact('error'));
    die;
}
session_start();

//Si no hay datos todavía en $_SESSION['datosTrastero] la creamos e inicislizamos todas las variables necesarias.
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
    
    $datosTrastero['listadoEliminar']=array();
    $datosTrastero['guardado'] = $trasteroGuardado; 
    $datosTrastero['trastero'] = $nuevoTrastero;
    $datosTrastero['almacenEstanterias']=$almacenEstanterias;
    $datosTrastero['almacenBaldas']=$almacenBaldas;
    $datosTrastero['almacenCajas']=$almacenCajas;
    $datosTrastero['tipo'] = $tipo;
    $_SESSION['datosTrastero']=$datosTrastero;
    //Si no cargamos la sesión creada con anterioridad.
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

//Cierra la sesión. 
 if(isset($_REQUEST['cerrarSesion'])){
    //Eliminamos los cambios realizados si no se ha seleccionado guardar.
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
    //Destruimos la sesión y volvemos a la página principal de la aplicación. 
    session_destroy();
    header("Location: index.php");
    die;
}else if(isset($_REQUEST['perfilUsuario'])){
    header("Location: editarPerfil.php");
    //Añadimos una estantería nueva.
}else if(isset($_POST['añadirEstanteria'])){
    $nuevaEstanteria = new Estanteria();
    $idTrastero = $nuevoTrastero->getId();
    //Si es la primera se le asigna el número uno.
    if(empty($almacenEstanterias)){
        $nuevaEstanteria->setNumero(1);
        $nuevaEstanteria->setNombre("Estanteria 1");
    //Si hay más estanterías creadas le asignamos el número siguiente. 
    }else{
        $numeroEstanteria = Estanteria::asignarNumero($bd, $idTrastero);
        $nuevaEstanteria->setNombre("Estanteria " .($numeroEstanteria));
        $nuevaEstanteria->setNumero($numeroEstanteria);
    }
    
    $nuevaEstanteria->setIdTrastero($nuevoTrastero->getId());
    $nuevaEstanteria->añadir($bd);
    $idEstanteria= Estanteria::obtenerIdPorNombre($bd, $nuevaEstanteria->getNombre(), $nuevoTrastero->getId());
    $nuevaEstanteria->setId($idEstanteria);
    $almacenEstanterias = Estanteria::recuperarEstanteriasPorIdTrastero($bd, $idTrastero);
    //Por defecto añadimos la balda 1 a la estantería creada.
    $nuevaBalda = new Balda();
    $nuevaBalda->setNumero(1);
    $nuevaBalda->setNombre("Balda 1");
    $nuevaBalda->setIdEstanteria($idEstanteria);
    $nuevaBalda->añadir($bd);
    $almacenBaldas = Balda::recuperarBaldasPorIdTrastero($bd, $idTrastero);
    $datosTrastero['almacenBaldas']=$almacenBaldas;
    $datosTrastero['almacenEstanterias']=$almacenEstanterias;
    $_SESSION['datosTrastero']=$datosTrastero;
    echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje', 'bd'));
    //Añadomos una balda al la estantería seleccionada.
}else if(isset($_POST['añadirBalda'])){
    $idEstanteria = trim(filter_input(INPUT_POST, 'idEstanteria', FILTER_SANITIZE_STRING));
    $nuevaBalda = new Balda();
    $numeroBalda = Balda::asignarNumero($bd, $idEstanteria);
    $nuevaBalda->setNombre("Balda " .($numeroBalda));
    $nuevaBalda->setNumero($numeroBalda);
    $nuevaBalda->setIdEstanteria($idEstanteria);
    $nuevaBalda->añadir($bd);
    $almacenBaldas = Balda::recuperarBaldasPorIdTrastero($bd, $nuevoTrastero->getId());
    $datosTrastero['almacenBaldas']=$almacenBaldas;
    $_SESSION['datosTrastero']=$datosTrastero;
        echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje', 'bd'));
//Añade una caja al trastero
}else if(isset($_POST['añadirCaja'])){
        //Si no está seleccionado Sin asignar se recogen los datos de la ubicación. 
        if(!filter_has_var(INPUT_POST,'sinAsignar')) {
            $almacenCajas = Caja::recuperarCajasPorIdTrastero($bd, $nuevoTrastero->getId());
            $nombreEstanteria = trim(filter_input(INPUT_POST, 'estanteria', FILTER_SANITIZE_STRING));
            $nombreBalda = trim(filter_input(INPUT_POST, 'balda', FILTER_SANITIZE_STRING));
            if($nombreBalda==""){
                $mensaje2="Es necesaria una balda para ubicar la caja en una estantería. Seleccione otra opción o cree una balda nueva";
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
        //Si se ha seleccionado sin ubicación se crea la caja sin ubicación. 
        }else{     
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
            $almacenCajas= Caja::recuperarCajasPorIdTrastero($bd, $nuevoTrastero->getId());
            $datosTrastero['almacenCajas']=$almacenCajas;
            $_SESSION['datosTrastero']=$datosTrastero;
        }
        echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje', 'bd'));
        //Si seleccionamos volver en la ventana emergente volvemos a la pantalla de crear trastero
}else if(isset($_POST['volver'])){
     echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje', 'bd'));
     //Si seleccionamos Volver volvemos a la pantalla de acceso a trasteros. 
}else if(isset($_POST['volverAcceso'])){
    //Si no se ha guardado los cambios realizados eliminamos las modificaciones realizadas y el trastero creado.
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
//Si se selecciona guardar recogemos el nombre del trastero y lo guardamos en la base de datos.     
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
            //En el caso de que ya exista un trastero con ese nombre. 
        }else{
            $mensaje = "Ya existe un trastero para este usuario con ese nombre.";
            echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje', 'bd'));
        }
    //En el caso de que el nombre esté vacío.
    }else{
        $mensaje = "El nombre del trastero no puede estar vacío.";
        echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje', 'bd'));
    } 
//Elimina estantería seleccionada en la base de datos.      
}else if(isset($_POST['eliminarEstanteria'])){
    $idEstanteria=trim(filter_input(INPUT_POST, 'idEstanteria', FILTER_SANITIZE_STRING));
    
    foreach($almacenEstanterias as $clave=>$valor){
        if($valor->getId()==$idEstanteria){
            $valor->eliminar($bd);
        }
    }
    //Eliminamos también las baldas que pertenezcan a esa estantería. 
    foreach($almacenBaldas as $clave=>$valor){
        if($valor->getIdEstanteria()==$idEstanteria){
             $valor->eliminar($bd);
        }
    }
    //Eliminamos también las cajas que pertenezcan a esa estantería. 
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
//Elimina la balda seleccionada.   
}else if(isset($_POST['eliminarBalda'])){
    $numeroEstanteria = trim(filter_input(INPUT_POST, 'numeroEstanteria', FILTER_SANITIZE_STRING));
    $numeroBalda = trim(filter_input(INPUT_POST, 'numeroBalda', FILTER_SANITIZE_STRING));
    $idBalda = trim(filter_input(INPUT_POST, 'idBalda', FILTER_SANITIZE_STRING));
    
    foreach($almacenBaldas as $clave=>$valor){
        if($valor->getId()==$idBalda){
            $valor->eliminar($bd);
        }
    }
    //Eliminamos también las cajas que estén en esa balda.
    foreach($almacenCajas as $clave=>$valor){
        if($valor->getIdBalda()==$idBalda){
            $valor->eliminar($bd);
        }
    }
    
    $datosTrastero['almacenBaldas']= Balda::recuperarBaldasPorIdTrastero($bd, $nuevoTrastero->getId());
    $datosTrastero['almacenCajas']=$almacenCajas;
    $_SESSION['datosTrastero']=$datosTrastero;
    
     echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje', 'bd'));
//Eliminamos la caja seleccionada. 
}else if(isset($_POST['eliminarCaja'])){
    $idCaja = trim(filter_input(INPUT_POST, 'idCaja', FILTER_SANITIZE_STRING));
    foreach($almacenCajas as $clave=>$valor){
        if($valor->getId()==$idCaja){
            unset($almacenCajas[$clave]);
            $valor->eliminar($bd);
        }
    }

    $datosTrastero['almacenCajas']=$almacenCajas;
    $_SESSION['datosTrastero']=$datosTrastero;
    
    echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje', 'bd'));
}else{
    echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje', 'bd'));
}


