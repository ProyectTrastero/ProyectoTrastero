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
    Trasteros, 
};
// Inicializa el acceso a las variables de entorno
$dotenv = Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();


// Inicializa el acceso a las variables de entorno
$views = __DIR__ . '/../vistas';
$cache = __DIR__ . '/../cache';
$blade = new BladeOne($views, $cache, BladeOne::MODE_DEBUG);

//Concexion con la base de datos
try {
    $bd = BD::getConexion();
} catch (PDOException $error) {
    echo $blade->run("cnxbderror", compact('error'));
    die;
}
session_start();
//Si la sesion datostrastero estña vacía
if(empty($_SESSION['datosTrastero'])){
    $idTrastero=$_SESSION['idTrastero'];
    $usuario = $_SESSION['usuario'];
    $idUsuario = $usuario->getId();
    $nuevoTrastero = Trasteros::recuperarTrasteroPorId($bd, $idTrastero);
    $datosTrastero = array();
    $almacenEstanterias= Estanteria::recuperarEstanteriasPorIdTrastero($bd, $idTrastero);
    $almacenBaldas = Balda::recuperarBaldasPorIdTrastero($bd, $idTrastero);
    $almacenCajas = Caja::recuperarCajasPorIdTrastero($bd, $idTrastero);
    $mensaje="";
    $trasteroGuardado = false;
    $tipo="modificar"; 
    $datosTrastero['listadoEliminar']=array();
    $datosTrastero['guardado'] = $trasteroGuardado; 
    $datosTrastero['trastero'] = $nuevoTrastero;
    $datosTrastero['almacenEstanterias']=$almacenEstanterias;
    $datosTrastero['almacenBaldas']=$almacenBaldas;
    $datosTrastero['almacenCajas']=$almacenCajas;
    $datosTrastero['tipo'] = $tipo;
    $_SESSION['datosTrastero']=$datosTrastero;
    //Si no está vacía la recupero
}else{
    $usuario = $_SESSION['usuario'];
    $idUsuario = $usuario->getId();
    $datosTrastero=$_SESSION['datosTrastero'];
    $tipo=$datosTrastero['tipo'];
    $listadoEliminar=$datosTrastero['listadoEliminar'];
    $almacenEstanterias = $datosTrastero['almacenEstanterias'];
    $almacenBaldas =$datosTrastero['almacenBaldas'];
    $almacenCajas =$datosTrastero['almacenCajas'];
    $nuevoTrastero =$datosTrastero['trastero'];
    $trasteroGuardado = $datosTrastero['guardado'];
    $mensaje = "";
}
//Si cierro sesión destruyo la sesión y vuelvo a la página principal
 if(isset($_REQUEST['cerrarSesion'])){
    session_destroy();
    header("Location: index.php");
    die;
    //Si selecciono perfil de usuario.
}else if(isset($_REQUEST['perfilUsuario'])){
    header("Location: editarPerfil.php");
    
    //Añadir una estantería nueva
}else if(isset($_POST['añadirEstanteria'])){
    $nuevaEstanteria = new Estanteria();
    $idTrastero = $nuevoTrastero->getId();
    $estanterias = Estanteria::recuperarEstanteriasPorIdTrastero($bd, $idTrastero);
    //Si es la primera estanteria le asigno el numero uno
    if(empty($estanterias)){
        $nuevaEstanteria->setNumero(1);
        $nuevaEstanteria->setNombre("Estanteria 1");
    //Si ya hay más estanterias le asigno el número que le corresponde.
    }else{
        $numeroEstanteria = Estanteria::asignarNumero($bd, $idTrastero);
        $nuevaEstanteria->setNombre("Estanteria " .($numeroEstanteria));
        $nuevaEstanteria->setNumero($numeroEstanteria);
    }
    
    $nuevaEstanteria->setIdTrastero($nuevoTrastero->getId());
    $nuevaEstanteria->añadir($bd);
    $idEstanteria= Estanteria::obtenerIdPorNombre($bd, $nuevaEstanteria->getNombre(), $nuevoTrastero->getId());
    $nuevaEstanteria->setId($idEstanteria);
    $creados[]=$nuevaEstanteria;
    //Creo balda por defecto.
    $nuevaBalda = new Balda();
    $baldasRecuperadas = Balda::recuperarBaldasPorIdEstanteria($bd, $idEstanteria);
    $nuevaBalda->setNumero(1);
    $nuevaBalda->setNombre("Balda 1");
   
    $nuevaBalda->setIdEstanteria($idEstanteria);
    $nuevaBalda->añadir($bd);
    $almacenEstanterias = Estanteria::recuperarEstanteriasPorIdTrastero($bd, $idTrastero);
    $almacenBaldas = Balda::recuperarBaldasPorIdTrastero($bd, $idTrastero);
    $datosTrastero['almacenBaldas']=$almacenBaldas;
    $datosTrastero['almacenEstanterias']=$almacenEstanterias;
    $_SESSION['datosTrastero']=$datosTrastero;
    echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje', 'bd'));
//Añado una balda nueva a la estantería.
}else if(isset($_POST['añadirBalda'])){
    $idEstanteria = trim(filter_input(INPUT_POST, 'idEstanteria', FILTER_SANITIZE_STRING));
    $nuevaBalda = new Balda();
    $baldasRecuperadas = Balda::recuperarBaldasPorIdEstanteria($bd, $idEstanteria);
    if(empty($baldasRecuperadas)){
        $nuevaBalda->setNumero(1);
        $nuevaBalda->setNombre("Balda 1");
    }else{
        $numeroBalda = Balda::asignarNumero($bd, $idEstanteria);
        $nuevaBalda->setNombre("Balda " .($numeroBalda));
        $nuevaBalda->setNumero($numeroBalda);
    }
    $nuevaBalda->setIdEstanteria($idEstanteria);
    $nuevaBalda->añadir($bd);
    $almacenBaldas = Balda::recuperarBaldasPorIdTrastero($bd, $nuevoTrastero->getId());
    $datosTrastero['almacenBaldas']=$almacenBaldas;
    $_SESSION['datosTrastero']=$datosTrastero;
        echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje', 'bd'));
//Añado una caja nueva
}else if(isset($_POST['añadirCaja'])){
    //SI se le asinga ubicación
        if(!filter_has_var(INPUT_POST,'sinAsignar')) {
            $nombreEstanteria = trim(filter_input(INPUT_POST, 'estanteria', FILTER_SANITIZE_STRING));
            $nombreBalda = trim(filter_input(INPUT_POST, 'balda', FILTER_SANITIZE_STRING));
            if($nombreBalda==""){
                $mensaje2="Es necesaria una balda para ubicar la caja en una estanteria. Seleccione otra opción o cree una balda nueva.";
                $datosTrastero['mensaje2']=$mensaje2;
                echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje', 'bd'));
                die;
            }
            $idEstanteria = Estanteria::obtenerIdPorNombre($bd, $nombreEstanteria, $nuevoTrastero->getId());
            $idBalda = Balda::obtenerIdPorNombre($bd, $nombreBalda, $idEstanteria); 
            $nuevaCaja = new Caja();           
            $nuevaCaja->setIdEstanteria($idEstanteria);
            $nuevaCaja->setIdBalda($idBalda);
            $nuevaCaja->setIdTrastero($nuevoTrastero->getId());
           //Asigno a la primera caja el valor de 1  
           if(empty($almacenCajas)){
                $numeroCaja = 1;
                $nuevaCaja->setNombre("Caja ".($numeroCaja));
                $nuevaCaja->setNumero($numeroCaja);
            //Si no es la primera le asigno el valor que le corresponde
            }else{
                $numeroCaja = Caja::asignarNumero($bd, $nuevoTrastero->getId());
                $nuevaCaja->setNombre("Caja " .($numeroCaja));
                $nuevaCaja->setNumero($numeroCaja);
            }
            //Guardo la caja en la bbdd
            $nuevaCaja->añadir($bd);
            $almacenCajas= Caja::recuperarCajasPorIdTrastero($bd, $nuevoTrastero->getId());
            $datosTrastero['almacenCajas']=$almacenCajas;
            $_SESSION['datosTrastero']=$datosTrastero;
        //Creo una caga sin ubicación asignada.    
        }else{
            $nuevaCaja = new Caja();
            $nuevaCaja->setIdTrastero($nuevoTrastero->getId());
             if(empty($almacenCajas)){
                $numeroCaja = 1;
                $nuevaCaja->setNombre("Caja ".($numeroCaja));
            }else{
                $numeroCaja = Caja::asignarNumero($bd, $nuevoTrastero->getId());
                $nuevaCaja->setNombre("Caja " .($numeroCaja));
            }
            $nuevaCaja->setNumero($numeroCaja);
            $nuevaCaja->añadir($bd);
            $almacenCajas= Caja::recuperarCajasPorIdTrastero($bd, $nuevoTrastero->getId());
            $datosTrastero['almacenCajas']=$almacenCajas;
            $_SESSION['datosTrastero']=$datosTrastero;
        }
        echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje', 'bd'));
//Vuelvo a la vista añadirTrastero para continuar modificándolo.
}else if(isset($_POST['volver'])){
     echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje', 'bd'));
     //Vuelvo a la página de acceso a trasteros.
}else if(isset($_POST['volverAcceso'])){      
        $_SESSION['datosTrastero']= array();
        header("Location:acceso.php");
//Elimina una estantería
}else if(isset($_POST['eliminarEstanteria'])){
    $idEstanteria=trim(filter_input(INPUT_POST, 'idEstanteria', FILTER_SANITIZE_STRING));
    $productosRecuperados= \App\Producto::recuperarProductosPorIdEstanteria($bd, $idEstanteria);
    //Si no tiene productos la elimino de la bbdd
    if(empty($productosRecuperados)){
        foreach($almacenEstanterias as $estanteria){
            if($estanteria->getId()==$idEstanteria){
                $estanteria->eliminar($bd);
            }
        }

        foreach($almacenBaldas as $balda){
            if($balda->getIdEstanteria()==$idEstanteria){
                 $balda->eliminar($bd);
            }
        }

        foreach($almacenCajas as $clave=>$valor){
            if($valor->getIdEstanteria()==$idEstanteria){
                $valor->eliminar($bd);
            }
        }

        $datosTrastero['almacenEstanterias']= Estanteria::recuperarEstanteriasPorIdTrastero($bd, $nuevoTrastero->getId());
        $datosTrastero['almacenBaldas']= Balda::recuperarBaldasPorIdTrastero($bd, $nuevoTrastero->getId());
        $datosTrastero['almacenCajas']=Caja::recuperarCajasPorIdTrastero($bd, $nuevoTrastero->getId());
        $_SESSION['datosTrastero']=$datosTrastero;

        echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje', 'bd'));
    //Si contiene productos guardo el listado a eliminar a la espera de confirmación.
    }else{
        foreach($almacenEstanterias as $estanteria){
            if($estanteria->getId()==$idEstanteria){
                $listadoEliminar[]=$estanteria;
            }
        }

        foreach($almacenBaldas as $balda){
            if($balda->getIdEstanteria()==$idEstanteria){
                 $listadoEliminar[]=$balda;;
            }
        }

        foreach($almacenCajas as $caja){
            if($caja->getIdEstanteria()==$idEstanteria){
                $listadoEliminar[]=$caja;
            }
        }
        
        $datosTrastero['recuperados']=$productosRecuperados;
        $datosTrastero['listadoEliminar']=$listadoEliminar;
        $_SESSION['datosTrastero']= $datosTrastero;
        echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje', 'bd'));
    }
//Elimino tras confirmar eliminación.   
}else if(isset($_POST['aceptar'])){
    $datosTrastero=$_SESSION['datosTrastero'];
    $productosRecuperados=$datosTrastero['recuperados'];
    foreach ($listadoEliminar as $elemento){
        $elemento->eliminar($bd);
    }
    //Actualizo la ubicación de los productos.
    foreach ($productosRecuperados as $producto){
        $producto->actualizarUbicacion($bd);
    }

    $datosTrastero['listadoEliminar']=array();
    $datosTrastero['almacenEstanterias']= Estanteria::recuperarEstanteriasPorIdTrastero($bd, $nuevoTrastero->getId());
    $datosTrastero['almacenBaldas']= Balda::recuperarBaldasPorIdTrastero($bd, $nuevoTrastero->getId());
    $datosTrastero['almacenCajas']= Caja::recuperarCajasPorIdTrastero($bd, $nuevoTrastero->getId());

    $_SESSION['datosTrastero']=$datosTrastero;
    echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje', 'bd'));
//Si seleccionan cancelar elimino el listado a eliminar.        
}else if (isset($_POST['cancelar'])){
    $datosTrastero['listadoEliminar']=array();
    $_SESSION['datosTrastero']=$datosTrastero;
     echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje', 'bd'));
//Elimina la balda seleccionada
}else if(isset($_POST['eliminarBalda'])){
    $idBalda = trim(filter_input(INPUT_POST, 'idBalda', FILTER_SANITIZE_STRING));
    $productosRecuperados= \App\Producto::recuperarProductosPorIdBalda($bd, $idBalda);
    //Si no contiene productos la elimino directamente.
    if(empty($productosRecuperados)){
           
    foreach($almacenBaldas as $balda){
        if($balda->getId()==$idBalda){
            $balda->eliminar($bd);
        }
    }
    
    foreach($almacenCajas as $clave=>$valor){
        if($valor->getIdBalda()==$idBalda){
            $valor->eliminar($bd);
        }
    }
    
    $datosTrastero['almacenBaldas']= Balda::recuperarBaldasPorIdTrastero($bd, $nuevoTrastero->getId());
    $datosTrastero['almacenCajas']= Caja::recuperarCajasPorIdTrastero($bd, $nuevoTrastero->getId());
    $_SESSION['datosTrastero']=$datosTrastero;
    
     echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje', 'bd'));
    //Si contiene productos quedo a la espera de confirmación
    }else{
        foreach($almacenBaldas as $balda){
            if($balda->getId()==$idBalda){
                 $listadoEliminar[]=$balda;;
            }
        }

        foreach($almacenCajas as $caja){
            if($caja->getIdBalda()==$idBalda){
                $listadoEliminar[]=$caja;
            }
        }
        
        $datosTrastero['recuperados']=$productosRecuperados;
        $datosTrastero['listadoEliminar']=$listadoEliminar;
        $_SESSION['datosTrastero']= $datosTrastero;
        echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje', 'bd'));
        
    }

//Elimina la caja del trastero.   
}else if(isset($_POST['eliminarCaja'])){
    $idCaja = trim(filter_input(INPUT_POST, 'idCaja', FILTER_SANITIZE_STRING));
    $productosRecuperados= \App\Producto::recuperarProductosPorIdCaja($bd, $idCaja);
    //Si no contiene productos la elimino directamente.
    if(empty($productosRecuperados)){
        foreach($almacenCajas as $clave=>$valor){
        if($valor->getId()==$idCaja){
            $valor->eliminar($bd);
        }
    }
    
    $datosTrastero['almacenCajas']= Caja::recuperarCajasPorIdTrastero($bd, $nuevoTrastero->getId());
    $_SESSION['datosTrastero']=$datosTrastero;
    
    echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje', 'bd'));
    //Si contiene productos las guardo a la espera de ser eliminadas.
    }else{
        foreach($almacenCajas as $caja){
            if($caja->getId()==$idCaja){
                $listadoEliminar[]=$caja;
            }
        }
        
        $datosTrastero['recuperados']=$productosRecuperados;
        $datosTrastero['listadoEliminar']=$listadoEliminar;
        $_SESSION['datosTrastero']= $datosTrastero;
        echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje', 'bd'));
    }
 
}else{
    echo $blade->run('añadirTrastero', compact('datosTrastero', 'mensaje', 'bd'));
}


