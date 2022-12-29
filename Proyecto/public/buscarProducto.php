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
        $usuario= $_SESSION['usuario'];
        $etiquetas = $_SESSION['etiquetas']; 
        
        $palabra = $_POST ['palabraBuscar'];
        $miTrastero=$_SESSION['miTrastero'];
        $idTrastero = $miTrastero->getId();
        $productos= App\Producto::recuperaProductosPorPalabraYTrastero($bd, $palabra, $idTrastero);
            if ($productos==""){
                $msj1_tipo="danger";
                $msj1="No existen productos con esas caracteristicas";
                echo $blade->run("buscarProducto", compact ('usuario', 'productos', 'miTrastero', 'etiquetas', 'msj1', 'msj1_tipo'));
                die; 
            }else{
                $msj1="";
                echo $blade->run("buscarProducto", compact ('usuario', 'productos', 'miTrastero', 'etiquetas', 'msj1'));
                die; 
            }

    }elseif(isset ($_POST['IdsEtiquetas'])){
        $usuario= $_SESSION['usuario'];
        $etiquetas = $_SESSION['etiquetas']; 
        $miTrastero=$_SESSION['miTrastero'];
        
            if ($etiquetas == null){
                $msj1_tipo="info";
                $msj1="Usted no tiene etiquetas";
                echo $blade->run("buscarProducto", compact ('usuario', 'miTrastero', 'etiquetas', 'msj1', 'msj1_tipo'));
                die; 
            }else{
                foreach($_POST['IdsEtiquetas'] as $idEtiqueta){
                    $idEtiquetas[]=$idEtiqueta;
                }
                $idTrastero = $miTrastero->getId();
                $productos= App\Producto::buscarProductosPorIdEtiquetaYTrastero($bd, $idEtiquetas, $idTrastero);
                    if ($productos==""){
                        $msj1_tipo="danger";
                        $msj1="No existen productos con esas caracteristicas";
                        echo $blade->run("buscarProducto", compact ('usuario', 'productos', 'miTrastero', 'etiquetas', 'msj1', 'msj1_tipo'));
                        die; 
                    }else{
                        $msj1="";
                        echo $blade->run("buscarProducto", compact ('usuario', 'productos', 'miTrastero', 'etiquetas', 'msj1'));
                        die; 
                    }
            }    
    }else{
        $msj1_tipo="info";
        $msj1="Debe añadir alguna palabra o etiqueta a la busqueda";
        $productos="";
        $miTrastero=$_SESSION['miTrastero'];
        $usuario= $_SESSION['usuario'];
        $etiquetas = $_SESSION['etiquetas']; 
        echo $blade->run("buscarProducto", compact ('usuario', 'productos', 'miTrastero', 'etiquetas', 'msj1', 'msj1_tipo'));
        die; 
    }
}elseif (isset($_REQUEST['modificarProducto'])) { 
    //enviamos el id del producto que vamos a modificar
    (isset($_POST['id'])) ? $idProducto = $_POST['id'] : $idProducto = "";
    header("location:../public/modificarProducto.php?idProducto=$idProducto"); 
    die;
}elseif (isset($_POST['volverTrasteros'])) {
    header("location:../public/accederTrastero.php"); 
    die;
}elseif (isset($_POST['eliminarProducto'])) {
    if (isset($_POST['IdsProductos'])){
        foreach($_POST['IdsProductos'] as $idProducto){
            App\Producto::eliminarProductoporID($bd, $idProducto);   
        }
        $msj1="Elementos eliminados correctamente";
        $usuario = $_SESSION['usuario'];
        $trasteros = $_SESSION['trasteros'];
        $miTrastero = $_SESSION['miTrastero'];
        $idUsuario = $usuario->getId();
        $idUsuario  = intval($idUsuario); 
        
        $etiquetas = App\Etiqueta::recuperaEtiquetasPorUsuario($bd, $idUsuario);
        echo $blade->run("buscarProducto", compact ('usuario', 'miTrastero', 'etiquetas', 'msj1'));
    }else{
        $msj1="Debe seleccionar algun elemento para eliminarlos";
        $usuario = $_SESSION['usuario'];
        $trasteros = $_SESSION['trasteros'];
        $miTrastero = $_SESSION['miTrastero'];
        $idUsuario = $usuario->getId();
        $idUsuario  = intval($idUsuario); 
       
        $etiquetas = App\Etiqueta::recuperaEtiquetasPorUsuario($bd, $idUsuario);
        echo $blade->run("buscarProducto", compact ('usuario', 'miTrastero', 'etiquetas', 'msj1'));
    }

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
    $msj1="";
    $usuario = $_SESSION['usuario'];
    $trasteros = $_SESSION['trasteros'];
    $miTrastero = $_SESSION['miTrastero'];
    $idUsuario = $usuario->getId();
    $idUsuario  = intval($idUsuario); 
    $etiquetas = App\Etiqueta::recuperaEtiquetasPorUsuario($bd, $idUsuario);
    $_SESSION['etiquetas']=$etiquetas;
    echo $blade->run("buscarProducto", compact ('usuario', 'miTrastero', 'etiquetas','msj1'));
    die;
}
