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
    if (($_POST['palabraBuscar'])!=""){
        $palabra = $_POST ['palabraBuscar'];
        $miTrastero=$_SESSION['miTrastero'];
        $idTrastero = $miTrastero->getId();
        $productos= App\Producto::recuperaProductosPorPalabraYTrastero($bd, $palabra, $idTrastero);
        $usuario= $_SESSION['usuario'];
        $idUsuario = intval($usuario->getId());
        $etiquetas = App\Etiqueta::recuperaEtiquetasPorUsuario($bd, $idUsuario);  
        echo $blade->run("buscarProducto", compact ('usuario', 'productos', 'miTrastero', 'etiquetas'));
        die; 
    }elseif(isset ($_POST['IdsEtiquetas'])){
        foreach($_POST['IdsEtiquetas'] as $idEtiqueta){
            $idEtiquetas[]=$idEtiqueta;
        }
        $miTrastero=$_SESSION['miTrastero'];
        $idTrastero = $miTrastero->getId();
        $productos= App\Producto::buscarProductosPorIdEtiquetaYTrastero($bd, $idEtiquetas, $idTrastero);
        $usuario= $_SESSION['usuario'];
        $idUsuario = intval($usuario->getId());
        $etiquetas = App\Etiqueta::recuperaEtiquetasPorUsuario($bd, $idUsuario);
        echo $blade->run("buscarProducto", compact ('usuario', 'productos', 'miTrastero', 'etiquetas'));
        die; 
    }else{
        $productos="";
        $miTrastero=$_SESSION['miTrastero'];
        $usuario= $_SESSION['usuario'];
        $idUsuario = intval($usuario->getId());
        $etiquetas = App\Etiqueta::recuperaEtiquetasPorUsuario($bd, $idUsuario);
        $msj = "Debe añadir alguna palabra o etiqueta a la busqueda";
        echo $blade->run("buscarProducto", compact ('usuario', 'productos', 'miTrastero', 'etiquetas', 'msj'));
        die; 
    }
}elseif (isset($_REQUEST['modificarProducto'])) { 
    //enviamos el id del producto que vamos a modificar
    (isset($_POST['id'])) ? $idProducto = $_POST['id'] : $idProducto = "";
    header("location:../public/modificarProducto.php?idProducto=$idProducto"); 
    die;
}elseif (isset($_REQUEST['eleminaSeleccion'])) {
    echo "Borrado";
}elseif (isset($_POST['volverTrasteros'])) {
    header("location:../public/accederTrastero.php"); 
    die;
}elseif (isset($_POST['eliminarProducto'])) {
    foreach($_POST['IdsProductos'] as $idProducto){
        App\Producto::eliminarProductoporID($bd, $idProducto);
    }
    header("location:../public/buscarProducto.php"); 
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
