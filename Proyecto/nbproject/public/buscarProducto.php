<?php
require "../vendor/autoload.php";

use eftec\bladeone\BladeOne;
use Dotenv\Dotenv;
use App\{
    BD,
    Usuario,
    Producto
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

//Si le damos a buscar producto
if (isset($_POST['buscarProducto'])) {
    //Si el imput de producto tiene contenido 
    if (($_POST['palabraBuscar'])!=""){
        /*
         * Recogemos los datos de usuario, etiqueta, y trastero de las sesiones y la palabra de la busqueda por 
         * el imput y recuperamos todos los productos que contengan esa palabra en el trastero
         */
        $usuario= $_SESSION['usuario'];
        $etiquetas = $_SESSION['etiquetas']; 
        $palabra = $_POST ['palabraBuscar'];
        $miTrastero=$_SESSION['miTrastero'];
        $idTrastero = $miTrastero->getId();
        $productos= Producto::recuperaProductosPorPalabraYTrastero($bd, $palabra, $idTrastero);
            //Si nos devulelve los productos en blanco mandamos un mesaje a la vista buscarProducto
            if ($productos==""){
                $msj1_tipo="danger";
                $msj1="No existen productos con esas caracteristicas";
                echo $blade->run("buscarProducto", compact ('usuario', 'productos', 'miTrastero', 'etiquetas', 'msj1', 'msj1_tipo'));
                die; 
            //si hay productos no enviamos ningun mensaje
            }else{
                $msj1="";
                echo $blade->run("buscarProducto", compact ('usuario', 'productos', 'miTrastero', 'etiquetas', 'msj1'));
                die; 
            }
    //Si seleccionamos etiquetas para la busqueda        
    }elseif(isset ($_POST['IdsEtiquetas'])){
        $usuario= $_SESSION['usuario'];
        $etiquetas = $_SESSION['etiquetas']; 
        $miTrastero=$_SESSION['miTrastero'];
            //Si el usuario no tuviera etiquetas
            if ($etiquetas == null){
                $msj1_tipo="info";
                $msj1="Usted no tiene etiquetas";
                echo $blade->run("buscarProducto", compact ('usuario', 'miTrastero', 'etiquetas', 'msj1', 'msj1_tipo'));
                die; 
            //Si el usuario si tiene etiquetas
            }else{
                //Recorremos todas las etiquetas 
                foreach($_POST['IdsEtiquetas'] as $idEtiqueta){
                    $idEtiquetas[]=$idEtiqueta;
                }
                $idTrastero = $miTrastero->getId();
                //buscamos los productos con esas etiquetas
                $productos= App\Producto::buscarProductosPorIdEtiquetaYTrastero($bd, $idEtiquetas, $idTrastero);
                    //Si nos devulelve los productos en blanco mandamos un mesaje a la vista buscarProducto
                    if ($productos==""){
                        $msj1_tipo="danger";
                        $msj1="No existen productos con esas caracteristicas";
                        echo $blade->run("buscarProducto", compact ('usuario', 'productos', 'miTrastero', 'etiquetas', 'msj1', 'msj1_tipo'));
                        die; 
                    //si hay productos no enviamos ningun mensaje
                    }else{
                        $msj1="";
                        echo $blade->run("buscarProducto", compact ('usuario', 'productos', 'miTrastero', 'etiquetas', 'msj1'));
                        die; 
                    }
            }    
    /*
     * Si le damos al boton de buscar sin marcar ninguna etiqueta ni meter ninguna palabra en el imput
     * mandamos un mesaje de tipo informacion a la vista de buscar producto
     */
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
//si pulsamos sobre modificar producto
}elseif (isset($_REQUEST['modificarProducto'])) { 
    //recogemos el id del producto que vamos a modificar y redirigimos a modificar producto con ese id
    (isset($_POST['id'])) ? $idProducto = $_POST['id'] : $idProducto = "";
    header("location:../public/modificarProducto.php?idProducto=$idProducto"); 
    die;
//si pulsamos sobre volver
}elseif (isset($_POST['volverTrasteros'])) {
    header("location:../public/accederTrastero.php"); 
    die;
//si pulsamos sobre eliminar producto
}elseif (isset($_POST['eliminarProducto'])) {
    if (isset($_POST['IdsProductos'])){
        //recogemos los ids de los productos seleccionados y los borramos 
        foreach($_POST['IdsProductos'] as $idProducto){
            App\Producto::eliminarProductoporID($bd, $idProducto);   
        }
        $msj1_tipo="success";
        $msj1="Elementos eliminados correctamente";
        $usuario = $_SESSION['usuario'];
        $trasteros = $_SESSION['trasteros'];
        $miTrastero = $_SESSION['miTrastero'];
        $idUsuario = $usuario->getId();
        $idUsuario  = intval($idUsuario); 
        
        $etiquetas = App\Etiqueta::recuperaEtiquetasPorUsuario($bd, $idUsuario);
        echo $blade->run("buscarProducto", compact ('usuario', 'miTrastero', 'etiquetas', 'msj1', 'msj1_tipo'));
    /*
     * si pulsamos sobre eliminar producto sin seleccionar ninguno, mandamos un mensaje tipo info
     * a la vista buscar producto
     */
    }else{
        $msj1_tipo="info";
        $msj1="Debe seleccionar algun elemento para eliminarlos";
        $usuario = $_SESSION['usuario'];
        $trasteros = $_SESSION['trasteros'];
        $miTrastero = $_SESSION['miTrastero'];
        $idUsuario = $usuario->getId();
        $idUsuario  = intval($idUsuario); 
       
        $etiquetas = App\Etiqueta::recuperaEtiquetasPorUsuario($bd, $idUsuario);
        echo $blade->run("buscarProducto", compact ('usuario', 'miTrastero', 'etiquetas', 'msj1', 'msj1_tipo'));
    }
    die;
//Si no pulsamos sobre nada y solo cargamos la pagina
}else{
    //
    if (isset($_REQUEST['perfilUsuario'])) {
        header("location: editarPerfil.php");
        die;
    }

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
     * no enviamos ningun mensaje a la vista, recogemos las variables de las sesiones 
     * y usamos un metodo de la clase Etiqueta para recuperar las etiquetas del usuario y asi guardarlas en una sesion
     */
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
