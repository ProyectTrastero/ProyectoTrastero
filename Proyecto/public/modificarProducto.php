<?php

require_once __DIR__ . '/../vendor/autoload.php';

use eftec\bladeone\BladeOne;
use Dotenv\Dotenv;

use App\{
    BD,
    Balda,
    Caja,
    Estanteria,
    Producto,
    Validacion,
    Etiqueta
};

// Inicializa el acceso a las variables de entorno
$dotenv = Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

// Inicializa blade
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
if(isset($_SESSION['usuario'])){
	
	$miTrastero = $_SESSION['miTrastero'];
	if(isset($_REQUEST['cerrarSesion'])){
		session_destroy();
		header("Location: index.php");
		die;
	}
	if(isset($_REQUEST['perfilUsuario'])){
		header("Location: editarPerfil.php");
		die;
	}
	if(isset($_POST['volver'])){
		header('location: buscarProducto.php');
        die;
	}

	//recuperamos el id del producto que vamos a modificar
	if(isset($_GET['idProducto'])){
		$idProducto = intval(Validacion::sanearInput($_GET['idProducto']));
		//recuperamos la informacion del producto
		$producto = Producto::recuperarProductoPorId($bd,$idProducto);
		//recupetamos las etiquetas del producto
		$etiquetasProducto = Producto::recuperarEtiquetasPorProductoId($bd,$idProducto);
		//comprobamos que el producto pertenece a este trastero
		if($producto->getIdTrastero() != $miTrastero->getId()) die;
	}
	echo $blade->run('modificarProducto',['producto'=>$producto]);
}

