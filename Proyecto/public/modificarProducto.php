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
	if (isset($_REQUEST['cerrarSesion'])) {
		session_destroy();
		header("Location: index.php");
		die;
	}
	if (isset($_REQUEST['perfilUsuario'])) {
		header("Location: editarPerfil.php");
		die;
	}

	$miTrastero = $_SESSION['miTrastero'];
	$usuario = $_SESSION['usuario'];

	//recuperamos el id del producto que vamos a modificar
	if (isset($_GET['idProducto'])) {

		$idProducto = intval(Validacion::sanearInput($_GET['idProducto']));
		//recuperamos la informacion del producto
		$producto = Producto::recuperarProductoPorId($bd, $idProducto);
		//comprobamos que el producto pertenece a este trastero
		if ($producto->getIdTrastero() != $miTrastero->getId()) die;
		//recuperamos las etiquetas del producto
		$etiquetasProducto = Producto::recuperarEtiquetasPorProductoId($bd, $idProducto);
		//recuperamos las etiquetas del usuario
		$etiquetasUsuario = Etiqueta::recuperaEtiquetasPorUsuario($bd, $usuario->getId());

		//recuperamos las estanterias del trastero
		$estanterias = Estanteria::recuperarEstanteriasPorIdTrastero($bd, $miTrastero->getId());

		//comprobamos si tenemos estanterias
		if (count($estanterias) > 0) {
			//si el producto tiene idEstanteria
			if (!is_null($producto->getIdEstanteria())) {
				//recuperamos las baldas de la estanteria del producto
				$baldas = Balda::recuperarBaldasPorIdEstanteria($bd, $producto->getIdEstanteria());
			} else {
				//recuperamos las baldas de la primera estanteria
				$baldas = Balda::recuperarBaldasPorIdEstanteria($bd, $estanterias[0]->getId());
			}

			//comprobamos si el producto tiene idBalda
			if (!is_null($producto->getIdBalda())) {
				//recuperamos las cajas de la balda del producto
				$cajas = Caja::recuperarCajaPorIdBalda($bd, $producto->getIdBalda());
			} else {
				//recuperamos las cajas de la primera balda
				$cajas = Caja::recuperarCajaPorIdBalda($bd, $baldas[0]->getId());
			}
		} else {
			$baldas = array();
			$cajas = array();
		}

		//recuperamos las cajas sin ubicar
		$cajasSinUbicar = Caja::recuperarCajasSinUbicarPorIdTrastero($bd, $miTrastero->getId());
		
		echo $blade->run('modificarProducto', [
			'producto' => $producto,
			'estanterias' => $estanterias,
			'baldas' => $baldas,
			'cajas' => $cajas,
			'cajasSinUbicar' => $cajasSinUbicar,
			'etiquetasProducto' => $etiquetasProducto,
			'etiquetasUsuario' => $etiquetasUsuario
		]);
	}

	//peticion para eliminar etiqueta
    if(isset($_GET['idEliminarEtiqueta'])){
        //recibimos el id de la etiqueta
        $idEliminarEtiqueta = Validacion::sanearInput($_GET['idEliminarEtiqueta']);
        //creamos objeto etiqueta
        $etiqueta = new Etiqueta();
        $etiqueta->setId($idEliminarEtiqueta);
        //eliminamos etiqueta
        if( $etiqueta->eliminarEtiqueta($bd)){
            //si etiqueta eliminada
            $mensaje['msj-content']='Etiqueta eliminada';
            $mensaje['msj-type'] = 'success';
        }else{
            //error al eliminar etiqueta
            $mensaje['msj-content']='Error al eliminar la etiqueta';
            $mensaje['msj-type'] = 'danger';
        }
        
        echo json_encode($mensaje);
        die;
    }

	//peticion para devolver baldas y cajas de una estanteria selecionada
	if(isset($_GET['idEstanteria'])){
		$idEstanteriaSelected = Validacion::sanearInput($_GET['idEstanteria']);
		//recuperamos las baldas 
		$baldas = Balda::recuperarBaldasPorIdEstanteria($bd, intval($idEstanteriaSelected));
		//recuperamos las cajas de la primera balda
		$cajas = Caja::recuperarCajaPorIdBalda($bd, $baldas[0]->getId());

		//formamos array con la respuesta
		$response = array('baldas' => $baldas, 'cajas' => $cajas);
		echo json_encode($response);
		die;
	}

	//peticion para devolver cajas de una balda seleccionada
	if (isset($_GET['idBalda'])) {
		//saneamos el id de la balda seleccionada
		$idBaldaSelected = Validacion::sanearInput($_GET['idBalda']);
		//recuperamos las cajas
		$cajas = Caja::recuperarCajaPorIdBalda($bd, $idBaldaSelected);
		$response = array('cajas'=>$cajas);
		echo json_encode($response);
		die;
	}

	//peticion para crear etiqueta
	if (isset($_GET['nombreEtiqueta'])) {
		//saneamos el nombre de la etiqueta
		$nombreEtiqueta = Validacion::sanearInput($_GET['nombreEtiqueta']);
		if ($nombreEtiqueta != '') {
			//creamos objeto etiqueta
			$etiqueta = new Etiqueta(null, $nombreEtiqueta, $usuario->getId());
			//comprobamos si el nombre de la etiqueta ya existe
			if (!$etiqueta->checkExisteNombreEtiqueta($bd)) {
				//el nombre de la etiqueta ya exite
				$mensaje = ['msj-content' => 'Nombre de etiqueta ya existe, elija otro.', 'msj-type' => 'danger'];
			} else {
				//guardamos la etiqueta
				$idEtiqueta = $etiqueta->guardarEtiqueta($bd);
				$mensaje = ['msj-content' => 'Etiqueta creada.', 'msj-type' => 'success','idEtiqueta'=>$idEtiqueta];
			}
		} else {
			//si nombre de etiqueta vacio
			$mensaje = ['msj-content' => 'El campo nombre de etiqueta es obligatorio.', 'msj-type' => 'danger'];
		}
		echo json_encode($mensaje);
		die;
	}

	//peticion para obtener las etiquetas del usuario
	if (isset($_GET['getEtiquetas'])) {
		$etiquetas = Etiqueta::recuperaEtiquetasPorUsuario($bd, $usuario->getId());
		echo json_encode($etiquetas);
		die;
	}
	
	//recibimos las peticiones post del cliente en formato json
	if (!is_null($data = json_decode(file_get_contents('php://input'), true))) {

		//peticion para modificar el producto 
		if (array_key_exists('modificarProducto', $data)) {

			$idProducto = Validacion::sanearInput($data['modificarProducto']);
			$errores = array();

			(isset($data['nombreProducto'])) ? $nombreProducto = Validacion::sanearInput($data['nombreProducto']) : $nombreProducto = null;
			(isset($data['descripcionProducto'])) ? $descripcionProducto = Validacion::sanearInput($data['descripcionProducto']) : $descripcionProducto = null;
			(isset($data['estanteria'])) ? $estanteria = intval(Validacion::sanearInput($data['estanteria'])) : $estanteria = null;
			(isset($data['balda'])) ? $balda = intval(Validacion::sanearInput($data['balda'])) : $balda = null;
			(isset($data['caja'])) ? $caja = intval(Validacion::sanearInput($data['caja'])) : $caja = null;
			(isset($data['cajasSinUbicar'])) ? $cajasSinUbicar = intval(Validacion::sanearInput($data['cajasSinUbicar'])) : $cajasSinUbicar = null;
			(isset($data['ubicacion'])) ? $ubicacion = Validacion::sanearInput($data['ubicacion']) : $ubicacion = null;
			(isset($data['inputAñadirEtiquetas'])) ? $inputAñadirEtiquetas = Validacion::sanearInput($data['inputAñadirEtiquetas']) : $inputAñadirEtiquetas = null;

			//comprobamos si se ha ingresado un nombre 
			if ($nombreProducto == '') {
				$mensaje['error'] = 'nombreInvalido';
				$mensaje['msj-content'] = "Error al modificar el producto";
				$mensaje['msj-type'] = "danger";
				echo json_encode($mensaje);
				die;
			}

			//si ubicacion en caja sin ubicacion
			if ($ubicacion == 'ubicacionCajasSinAsignar') {
				if ($cajasSinUbicar == 0) {
					$mensaje['msj-content'] = "Ubicacion invalida";
					$mensaje['msj-type'] = "danger";
					echo json_encode($mensaje);
					die;
				}
				$caja = $cajasSinUbicar;
			}

			//si ubicacion en estanteria
			if ($ubicacion == 'ubicacionEstanteria') {
				if ($estanteria == 0 || $balda == 0) {
					$mensaje['msj-content'] = "Ubicacion invalida";
					$mensaje['msj-type'] = "danger";
					echo json_encode($mensaje);
					die;
				}
			}

			//separamos los id de las etiquetas y los guardamos en un array
			$arrayInputAñadirEtiquetas = preg_split("/\s/", $inputAñadirEtiquetas);

			//si no se ha seleccionado una caja en estanterias
			if ($caja == 0) {
				$caja = null;
			}

			//creamos object producto
			$objProductoUpdate = new Producto();
			$objProductoUpdate->setId(($idProducto != '') ? intval($idProducto) : null);
			$objProductoUpdate->setNombre(($nombreProducto != '') ? $nombreProducto : null);
			$objProductoUpdate->setDescripcion(($descripcionProducto != '') ? $descripcionProducto : null);
			$objProductoUpdate->setIdEstanteria(($estanteria != '') ? $estanteria : null);
			$objProductoUpdate->setIdBalda(($balda != '') ? $balda : null);
			$objProductoUpdate->setIdCaja(($caja != '') ? $caja : null);
			$objProductoUpdate->setIdTrastero($miTrastero->getId());

			//modificamos el producto
			if ($objProductoUpdate->modificarProducto($bd)) {
				//si producto modificado correctamente
				$mensaje['msj-content'] = "Producto modificado con exito";
				$mensaje['msj-type'] = "success";
				//recuperamos las etiquetas que tiene el producto
				$etiquetasProducto = Producto::recuperarEtiquetasPorProductoId($bd, $idProducto);

				//comprobamos las etiquetas a añadir
				$existe = false;
				//recorremos las etiquetas del formulario
				foreach ($arrayInputAñadirEtiquetas as $valueArrayInput) {
					if ($valueArrayInput == '') continue;
					//recorremos las etiquetas que ya tiene el producto
					foreach ($etiquetasProducto as $etiquetaProducto) {
						//si la etiqueta del formulario coincide con una que ya tiene el producto
						if ($valueArrayInput == $etiquetaProducto['idEtiqueta']) {
							$existe = true;
						}
					}
					//si no existe, añadimos la etiqueta
					if (!$existe) {
						Producto::añadirEtiquetaProducto($bd, $valueArrayInput, $idProducto);
					}
					$existe = false;
				}

				//comprobamos las etiquetas a eliminar
				$existe = false;
				//recorremos las etiquetas del producto
				foreach ($etiquetasProducto as $etiquetaProducto) {
					//recorremos las etiquetas del formulario
					foreach ($arrayInputAñadirEtiquetas as $valueArrayInput) {
						if ($etiquetaProducto['idEtiqueta'] == $valueArrayInput) {
							$existe = true;
						}
					}
					// si la etiqueta del producto no esta en las etiquetas del formulario
					if (!$existe) {
						//eliminamos la etiqueta
						Producto::eliminarEtiquetaProducto($bd, $etiquetaProducto['id']);
					}
					$existe = false;
				}
			} else {
				$mensaje['msj-content'] = "Error al modificar el producto";
				$mensaje['msj-type'] = "danger";
			}

			echo json_encode($mensaje);
			die;
		}
	}

}
