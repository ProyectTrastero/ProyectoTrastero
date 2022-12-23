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
if (isset($_SESSION['usuario'])) {
	$miTrastero = $_SESSION['miTrastero'];
	$usuario = $_SESSION['usuario'];
	$estanterias = null;
	$baldas = null;
	$cajas = null;
	$cajasSinUbicar=null;


	if (isset($_REQUEST['cerrarSesion'])) {
		session_destroy();
		header("Location: index.php");
		die;
	}
	if (isset($_REQUEST['perfilUsuario'])) {
		header("Location: editarPerfil.php");
		die;
	}
	if (isset($_POST['volver'])) {
		header('location: buscarProducto.php');
		die;
	}

	//recuperamos el id del producto que vamos a modificar
	if (isset($_GET['idProducto'])) {

		$idProducto = intval(Validacion::sanearInput($_GET['idProducto']));
		//recuperamos la informacion del producto
		$producto = Producto::recuperarProductoPorId($bd, $idProducto);
		//comprobamos que el producto pertenece a este trastero
		if ($producto->getIdTrastero() != $miTrastero->getId()) die;
		//recupetamos las etiquetas del producto
		$etiquetasProducto = Producto::recuperarEtiquetasPorProductoId($bd, $idProducto);
		//recuperamos las etiquetas del usuario
		$etiquetasUsuario = Etiqueta::recuperaEtiquetasPorUsuario($bd, $usuario->getId());


		//si tenemos el producto en una estanteria
		if (!is_null($producto->getIdEstanteria())) {
			//recuperamos las estanterias del trastero
			$estanterias = Estanteria::recuperarEstanteriasPorIdTrastero($bd, $miTrastero->getId());
			//recuperamos las baldas de la estanteria en la que tenemos el producto
			$baldas = Balda::recuperarBaldasPorIdEstanteria($bd, $producto->getIdEstanteria());
			//comprobamos si el producto esta dentro de una caja
			if (!is_null($producto->getIdCaja())) {
				//recuperamos las cajas de la estanteria en la que tenemos el producto
				$cajas = Caja::recuperarCajaPorIdBalda($bd, $producto->getIdBalda());
			}
		}
		//si tenemos el producto en una caja sin ubicar
		elseif (is_null($producto->getIdEstanteria()) && !is_null($producto->getIdCaja())) {
			$cajasSinUbicar = Caja::recuperarCajasSinAsignarPorIdTrastero($bd, $miTrastero->getId());
		}
	}
	// $daniel = $_POST['getCajasSinAsignar'];

	echo $blade->run('modificarProducto', [	'producto' => $producto, 
											'estanterias' => $estanterias, 
											'baldas' => $baldas, 
											'cajas' => $cajas, 
											'cajasSinUbicar' => $cajasSinUbicar, 
											'etiquetasProducto' => $etiquetasProducto, 
											'etiquetasUsuario' => $etiquetasUsuario
										]);
}
