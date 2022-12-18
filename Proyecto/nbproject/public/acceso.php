<?php
require "../vendor/autoload.php";

use eftec\bladeone\BladeOne;
use Dotenv\Dotenv;
use App\{
    BD,
    Usuario,
    Producto
};
session_start();
// Inicializa el acceso a las variables de entorno
$dotenv = Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

// Inicializa el acceso a las variables de entorno
$views = __DIR__ . '/../vistas';
$cache = __DIR__ . '/../cache';
$blade = new BladeOne($views, $cache, BladeOne::MODE_DEBUG);

// Establece conexión a la base de datos PDO
try {
    $bd = BD::getConexion();
} catch (PDOException $error) {
    echo $blade->run("cnxbderror", compact('error'));
    die;
}



//Esta parte la he añadido yo. Emma

if(!empty($_SESSION['datosTrastero'])){
    $datosTrastero=$_SESSION['datosTrastero'];
        $tipo=$datosTrastero['tipo'];
    if($tipo=="guardar"){
       
        $almacenEstanterias = $datosTrastero['almacenEstanterias'];
        $almacenBaldas =$datosTrastero['almacenBaldas'];
        $almacenCajas =$datosTrastero['almacenCajas'];
        $nuevoTrastero =$datosTrastero['trastero'];
        $trasteroGuardado = $datosTrastero['guardado'];
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
    }
}

//Hasta aquí

if (isset($_REQUEST['acceder'])) {
    $usuario = $_SESSION['usuario'];
    $trasteros = $_SESSION['trasteros'];
    $id = intval($_POST['id']);
    $_SESSION['id']=$id;
    
    //$_SESSION['miTrastero'] = $trasteros[$id];
    header("location:../public/accederTrastero.php"); 
    
}elseif (isset($_REQUEST['añadirTrastero'])) {  
    header("location:../public/añadirTrastero.php"); 
    die;
      
}elseif (isset($_REQUEST['modificar'])) { 
    $_SESSION['idTrastero']=$_POST['id'];
    header("location:../public/modificarTrastero.php"); 
    die;
//Esta parte la he añadido yo. Emma   
}elseif (isset($_REQUEST['eliminar'])){
    $idTrastero=($_POST['id']);
    $trastero = \App\Trasteros::recuperarTrasteroPorId($bd, $idTrastero);
    $trastero->eliminar($bd);
    $estanterias = App\Estanteria::recuperarEstanteriasPorIdTrastero($bd, $idTrastero);
    foreach ($estanterias as $estanteria){
        $idEstanteria=$estanteria->getId();
        $estanteria->eliminar($bd);
        $baldas= App\Balda::recuperarBaldasPorIdEstanteria($bd, $idEstanteria);
        foreach ($baldas as $balda){
            $balda->eliminar($bd);     
        } 
    }
    $cajas= App\Caja::recuperarCajasPorIdTrastero($bd, $idTrastero);
    foreach ($cajas as $caja){
        $caja->eliminar($bd);
    }
    $productos= Producto::recuperarProductosPorIdTrastero($bd, $idTrastero);
    foreach($productos as $producto){
        $producto->eliminar($bd);
    }
    
    $usuario = $_SESSION['usuario'];
    $idUsuario = intval($usuario->getId());
    $trasteros = App\Trasteros::recuperaTrasteroPorUsuario($bd, $idUsuario);
    $_SESSION['trasteros'] = $trasteros;
    echo $blade->run("acceso", compact ('usuario', 'trasteros'));
    die;
    //Falta eliminar los productos que no está la clase hecha y supongo que la habrá hecho Marta.
   
    
    
    
    
    
//Hasta aquí
/*    
}elseif (isset($_POST['salir'])) {
// Destruyo la sesión
        session_unset();
        session_destroy();
        setcookie(session_name(), '', 0, '/');
// Invoco la vista del formulario de iniciar sesion
        header("location:../public/index.php"); 
        die;

        
//DANI SOLO PUEDES TOCAR AQUI       
}elseif (isset($_POST['perfilusuario'])) {
        //Esta parte la esta haciendo Dani
        $usuario = $_SESSION['usuario'];
        $nombre = $usuario->getNombre();
        $clave = $usuario->getClave();
        $email = $usuario->getEmail();
        echo $blade->run("perfil", compact('nombre', 'clave', 'email'));
        die;
*/
///HASTA AQUI ¡¡NO TOQUES MASSSSSSS¡¡¡¡¡jajajaja 
}elseif (isset($_SESSION['usuario'])){
        if (isset($_REQUEST['perfilUsuario'])) {
            header("location: editarPerfil.php");
            die;
        }

        if (isset($_REQUEST['cerrarSesion'])) {
            // Destruyo la sesión
            session_unset();
            session_destroy();
            setcookie(session_name(), '', 0, '/');
            // Invoco la vista del formulario de iniciar sesion
            header('location: index.php');
            //echo $blade->run("sesion");
            die;
        }
    
    $usuario = $_SESSION['usuario'];
    $idUsuario = intval($usuario->getId());
    $trasteros = App\Trasteros::recuperaTrasteroPorUsuario($bd, $idUsuario);
    $_SESSION['trasteros'] = $trasteros;
    echo $blade->run("acceso", compact ('usuario', 'trasteros'));
    die; 
}


