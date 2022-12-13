<?php
require "../vendor/autoload.php";

use eftec\bladeone\BladeOne;
use Dotenv\Dotenv;
use App\{
    BD,
    Usuario
};

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

session_start();

if (isset($_POST['buscarProducto'])) {
    $palabra = $_POST ['palabraBuscar'];
    $productos= App\Producto::recuperaProductosPorPalabra($bd, $palabra);
    $_SESSION['productos'] = $productos;
    $miTrastero=$_SESSION['miTrastero'];
    $usuario= $_SESSION['usuario'];
    echo $blade->run("buscarProducto", compact ('usuario', 'productos', 'miTrastero'));
    die; 
    
}elseif (isset($_POST['añadirEtiquetas'])) {  
    header("location:../public/añadirEtiqueta.php"); 
    die;
      
}elseif (isset($_REQUEST['modificarProducto'])) { 
    header("location:../public/modificarProducto.php"); 
    die;
}elseif (isset($_REQUEST['eleminaSeleccion'])) {
    echo "Borrado";
}elseif (isset($_POST['volverTrasteros'])) {
    header("location:../public/accederTrastero.php"); 
    die;
}else{
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
    $trasteros = $_SESSION['trasteros'];
    $miTrastero = $_SESSION['miTrastero'];
 
    $idUsuario = $usuario->getId();
    $idUsuario  = intval($idUsuario);
    
    $etiquetas = App\Etiqueta::recuperaEtiquetasPorUsuario($bd, $idUsuario);
    

    
    echo $blade->run("buscarProducto", compact ('usuario', 'miTrastero', 'etiquetas'));
    die;
}
