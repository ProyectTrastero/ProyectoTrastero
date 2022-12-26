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

	//recibimos las peticiones del cliente 
	if(!is_null($data = json_decode(file_get_contents('php://input'),true))){

		//peticion para devolver cajas sin asignar
		if (array_key_exists('getCajasSinUbicar',$data)) {
			$cajasSinUbicar = Caja::recuperarCajasSinAsignarPorIdTrastero($bd, $miTrastero->getId());
			echo json_encode($cajasSinUbicar);
			die;
		}
		//peticion para devolver estanterias
		if(array_key_exists('getEstanteriasBaldasCajas',$data)){

			//obtenemos las estanterias del trastero
			$estanterias = Estanteria::recuperarEstanteriasPorIdTrastero($bd, $miTrastero->getId());

			//saneamos el idEstanteria
			(isset($data['idEstanteriaSelected'])) ? $idEstanteriaSelected = Validacion::sanearInput($data['idEstanteriaSelected']) : $idEstanteriaSelected = "";
			//si tenemos id estanteria selected buscamos las baldas de la estanteria selected
			if($idEstanteriaSelected != ''){
				$baldas = Balda::recuperarBaldasPorIdEstanteria($bd,intval($idEstanteriaSelected));
			}else{
			//si no tenemos id estanteria selected buscamos las baldas de la primera estanteria
				$baldas = Balda::recuperarBaldasPorIdEstanteria($bd,$estanterias[0]->getId());
			}

			//saneamos el idbalda
			(isset($data['idBaldaSelected'])) ? $idBaldaSelected = Validacion::sanearInput($data['idBaldaSelected']) : $idBaldaSelected = "";
			//si tenemos el id balda selected buscamos las cajas de la balda selected
			if($idBaldaSelected != ''){
				$cajas = Caja::recuperarCajaPorIdBalda($bd,intval($idBaldaSelected));
			}else{
				//si no tenemos el id de la balda selected buscamos las cajas de la primera balda
				$cajas = caja::recuperarCajaPorIdBalda($bd,$baldas[0]->getId());
			}

			//saneamos el idCaja
			(isset($data['idCajaSelected'])) ? $idCajaSelected = Validacion::sanearInput(($data['idCajaSelected'])) : $idCajaSelected = "";

			//array con los id de la ubicacion del producto
			$productoSelected = array('idEstanteriaSelected'=>$idEstanteriaSelected, 'idBaldaSelected'=>$idBaldaSelected, 'idCajaSelected'=>$idCajaSelected);

			//guardamos en un array los datos
			$arrResponse = array('estanterias'=>$estanterias,'baldas'=>$baldas,'cajas'=>$cajas, 'productoSelected'=>$productoSelected);
			echo json_encode($arrResponse);
			die;
		}
		//peticion para devolver baldas y cajas de una estanteria selecionada
		if(array_key_exists('getBaldasCajas',$data)){
			//saneamos el id de la estanteria seleccionada
			(isset($data['idEstanteriaSelected'])) ? $idEstanteriaSelected = Validacion::sanearInput($data['idEstanteriaSelected']) : $idEstanteriaSelected = "";

			//recuperamos las baldas 
			$baldas = Balda::recuperarBaldasPorIdEstanteria($bd, intval($idEstanteriaSelected));
			//recuperamos las cajas de la primera balda
			$cajas = Caja::recuperarCajaPorIdBalda($bd, $baldas[0]->getId());

			//formamos array con la respuesta
			$arrResponse = array('baldas'=>$baldas, 'cajas'=>$cajas);
			echo json_encode($arrResponse);
			die;
		}
		//peticion para devolver cajas de una balda seleccionada
		if(array_key_exists('getCajas',$data)){
			//saneamos el id de la balda seleccionada
			(isset($data['idBaldaSelected'])) ? $idBaldaSelected = Validacion::sanearInput($data['idBaldaSelected']) : $idBaldaSelected = "";
			
			//si no tenemos id no hacemos la consulta
			if($idBaldaSelected == '') die;
			//recuperamos las cajas
			$cajas = Caja::recuperarCajaPorIdBalda($bd,$idBaldaSelected);
			echo json_encode($cajas);
			die;
		}

		//peticion para crear etiqueta
		if(array_key_exists('crearEtiqueta',$data)){
			//saneamos el nombre de la etiqueta
			(isset($data['nombreEtiqueta'])) ? $nombreEtiqueta =Validacion::sanearInput($data['nombreEtiqueta']) : $nombreEtiqueta = "";
			if($nombreEtiqueta != ''){
				//creamos objeto etiqueta
				$etiqueta = new Etiqueta(null, $nombreEtiqueta, $usuario->getId());
				//comprobamos si el nombre de la etiqueta ya existe
				if (!$etiqueta->checkExisteNombreEtiqueta($bd)) {
					//el nombre de la etiqueta ya exite
					$mensaje=['msj-content'=>'Nombre de etiqueta ya existe, elija otro.' , 'msj-type'=>'danger'];
				}else{
					//guardamos la etiqueta
					$etiqueta->guardarEtiqueta($bd);
					$mensaje=['msj-content'=>'Etiqueta añadida.' , 'msj-type'=>'success'];
				}
			}else{
				//si nombre de etiqueta vacio
				$mensaje=['msj-content'=>'El campo nombre de etiqueta es obligatorio.' , 'msj-type'=>'danger'];
			}
			echo json_encode($mensaje);
			die;
		}

		//peticion para obtener las etiquetas del usuario
		if(array_key_exists('getEtiquetas',$data)){
			$etiquetas = Etiqueta::recuperaEtiquetasPorUsuario($bd,$usuario->getId());
			echo json_encode($etiquetas);
			die;
		}

		//peticion para modificar el producto 
		if(array_key_exists('modificarProducto',$data)){
			
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

			 //separamos los id de las etiquetas y los guardamos en un array
			 $arrayInputAñadirEtiquetas = preg_split("/\s/",$añadirEtiquetas);

			 //si se ha seleccionado una caja sin ubicacion
			 if($ubicacion == 'ubicacionCajasSinAsignar'){
				$caja=$cajaSinAsignar;
			}
			
 			//si no se ha seleccionado una caja en estanterias
			 if($caja==0){
				$caja =null;
			}

			//comprobamos si se ha ingresado un nombre 
			if ($nombreProducto == '' ) {
				array_push($errores, 'nombreInvalido');
			//comprobamos si tenemos una ubicacion
			}if($ubicacion == ''){
				array_push($errores, 'sinUbicacion');
			}
			
			$objProductoUpdate = new Producto();
			$objProductoUpdate->setNombre(($nombreProducto != '') ? $nombreProducto : null);
			$objProductoUpdate->setDescripcion(($descripcionProducto != '') ? $descripcionProducto : null);
			$objProductoUpdate->setIdEstanteria($estanteria);
			$objProductoUpdate->setIdBalda($balda);
			$objProductoUpdate->setIdCaja($caja);
			$objProductoUpdate->setIdTrastero($miTrastero->getId());

			// $datos=['nombreProducto'=>$nombreProducto,
			// 		'descripcionProducto'=>$descripcionProducto,
			// 		'estanteria'=>$estanteria,
			// 		'balda'=>$balda,
			// 		'caja'=>$caja,
			// 		'idTrastero'=>$miTrastero->getId()
			// 		];

			// //set a null todos los campos vacios para añadir a la base de datos como null
			// foreach ($datos as $key => $value) {
			// 	if ($value=='') {
			// 		$datos[$key]=null;
			// 	}
			// }

			//si se ha especificado nombre y ubicacion
			if (count($errores)==0) {
				//si producto modificado correctamente
				if ($objProductoUpdate->modificarProducto($bd)) {
					$mensaje['msj-content']="Producto modificado con exito";
					$mensaje['msj-type']="success";
					//comprobamos si tenemos etiquetas para añadir al producto
					foreach ($arrayInputAñadirEtiquetas as $idEtiqueta) {
						if($idEtiqueta != ""){
							$idEtiqueta=intval($idEtiqueta);
							//añadimos las etiquetas a el producto
							//si falla mostramos error
							if(!Producto::añadirEtiquetaProducto($bd,$idEtiqueta,$idProducto)){
								$mensaje['msj-content']="Error al añadir la etiqueta al producto";
								$mensaje['msj-type']="danger";
							}
						}
					}
				}else {
					$mensaje['msj-content']="Error al modificar el producto";
					$mensaje['msj-type']="danger";
				}
				
				echo json_encode($mensaje);
				die;
			}else{
				echo json_encode($errores);
				die;
			}

			
		}

	}
	

	echo $blade->run('modificarProducto', [	'producto' => $producto, 
											'estanterias' => $estanterias, 
											'baldas' => $baldas, 
											'cajas' => $cajas, 
											'cajasSinUbicar' => $cajasSinUbicar, 
											'etiquetasProducto' => $etiquetasProducto, 
											'etiquetasUsuario' => $etiquetasUsuario
										]);
}
