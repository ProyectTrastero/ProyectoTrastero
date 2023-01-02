<?php
require "../vendor/autoload.php";

use eftec\bladeone\BladeOne;
use Dotenv\Dotenv;
use App\{
    BD,
    Usuario,
    Producto,
    Trasteros
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

//Si le damos a acceder recojemos las diferentes variables en sesiones y redirigimos a accederTrastero.php
if (isset($_REQUEST['acceder'])) {
    $usuario = $_SESSION['usuario'];
    $trasteros = $_SESSION['trasteros'];
    $id = intval($_POST['id']);
    $_SESSION['id']=$id;
    header("location:../public/accederTrastero.php"); 

//Si le damos a añadir Trastero 
}elseif (isset($_REQUEST['añadirTrastero'])) {  
    header("location:../public/añadirTrastero.php"); 
    die;

//Si le damos a modificar Trastero guardamos el id del trastero en una sesion y redirigimos a modificarTrastero.php 
}elseif (isset($_REQUEST['modificar'])) { 
    $_SESSION['idTrastero']=$_POST['id'];
    header("location:../public/modificarTrastero.php"); 
    die;
    
//Esta parte la he añadido yo. Emma   
}elseif (isset($_REQUEST['eliminar'])){
    $idTrastero=($_POST['id']);
    $trastero = Trasteros::recuperarTrasteroPorId($bd, $idTrastero);
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
    
    /*
     * Recogemos los datos del usuario de la sesion para recuparer los trasteros y los guardamos
     * en una sesion para mandar ambos datos a la vista acceso
     */
    $usuario = $_SESSION['usuario'];
    $idUsuario = intval($usuario->getId());
    $trasteros = Trasteros::recuperaTrasteroPorUsuario($bd, $idUsuario);
    $_SESSION['trasteros'] = $trasteros;
    echo $blade->run("acceso", compact ('usuario', 'trasteros'));
    die;

// Si xiste sesion de usuario
}elseif (isset($_SESSION['usuario'])){
        //Si le damos a perfil nos envia a la pagina para editar el perfil
        if (isset($_REQUEST['perfilUsuario'])) {
            header("location: editarPerfil.php");
            die;
        }
        //Si le damos a cerrar sesion 
        if (isset($_REQUEST['cerrarSesion'])) {
            // Destruyo la sesión
            session_unset();
            session_destroy();
            setcookie(session_name(), '', 0, '/');
            //Redirigimos al formulario de iniciar sesion
            header('location: index.php');
            die;
        }
    /*
     * Recogemos los datos del usuario de la sesion para recuparer los trasteros y los guardamos
     * en una sesion para mandar ambos datos a la vista acceso
     */
    $usuario = $_SESSION['usuario'];
    $idUsuario = intval($usuario->getId());
    $trasteros = Trasteros::recuperaTrasteroPorUsuario($bd, $idUsuario);
    $_SESSION['trasteros'] = $trasteros;
    echo $blade->run("acceso", compact ('usuario', 'trasteros'));
    die; 
}


