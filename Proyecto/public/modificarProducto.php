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
	if (isset($_POST['volver'])) {
		header('location: buscarProducto.php');
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
		if(count($estanterias) > 0 ){
			//si el producto tiene idEstanteria
			if(!is_null($producto->getIdEstanteria())){
				//recuperamos las baldas de la estanteria del producto
				$baldas = Balda::recuperarBaldasPorIdEstanteria($bd,$producto->getIdEstanteria());
			}else{
				//recuperamos las baldas de la primera estanteria
				$baldas = Balda::recuperarBaldasPorIdEstanteria($bd,$estanterias[0]->getId());
			}

			//comprobamos si el producto tiene idBalda
			if(!is_null($producto->getIdBalda())){
				//recuperamos las cajas de la balda del producto
				$cajas = Caja::recuperarCajaPorIdBalda($bd,$producto->getIdBalda());
			}else{
				//recuperamos las cajas de la primera balda
				$cajas = Caja::recuperarCajaPorIdBalda($bd,$baldas[0]->getId());
			}
		}else{
			$baldas = array();
			$cajas = array();
		}

		//recuperamos las cajas sin ubicar
		$cajasSinUbicar = Caja::recuperarCajasSinUbicarPorIdTrastero($bd, $miTrastero->getId());
			
		
	}

	//recibimos las peticiones del cliente 
	if(!is_null($data = json_decode(file_get_contents('php://input'),true))){

		//peticion para devolver cajas sin asignar
		if (array_key_exists('getCajasSinUbicar',$data)) {
			$cajasSinUbicar = Caja::recuperarCajasSinUbicarPorIdTrastero($bd, $miTrastero->getId());
			echo json_encode($cajasSinUbicar);
			die;
		}
		//peticion para devolver estanterias
		if(array_key_exists('getEstanteriasBaldasCajas',$data)){
			
			//obtenemos las estanterias del trastero
			$estanterias = Estanteria::recuperarEstanteriasPorIdTrastero($bd, $miTrastero->getId());
			//si no tenemos estanterias
			if (count($estanterias) == 0 ){
				$arrResponse = array('estanterias'=>$estanterias=array(),'baldas'=>$baldas = array(),'cajas'=>$cajas = array(), 'productoSelected'=>$productoSelected =array());
				echo json_encode($arrResponse);
				die;
			}
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
					$mensaje=['msj-content'=>'Etiqueta creada.' , 'msj-type'=>'success'];
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
			 $arrayInputAñadirEtiquetas = preg_split("/\s/",$inputAñadirEtiquetas);

			//si ubicacion en caja sin ubicacion
			if($ubicacion == 'ubicacionCajasSinAsignar'){
				if($cajasSinUbicar == 0){
					$mensaje['msj-content']="Ubicacion invalida";
					$mensaje['msj-type']="danger";
					echo json_encode($mensaje);
					die;
				}
				$caja=$cajasSinUbicar;
			}

			//si ubicacion en estanteria
			if($ubicacion == 'ubicacionEstanteria'){
				if($estanteria==0 && $balda==0){
					$mensaje['msj-content']="Ubicacion invalida";
					$mensaje['msj-type']="danger";
					echo json_encode($mensaje);
					die;
				}
			}
			
 			//si no se ha seleccionado una caja en estanterias
			 if($caja==0){
				$caja =null;
			}

			//comprobamos si se ha ingresado un nombre 
			if ($nombreProducto == '' ) {
				$errores['nombreInvalido']='true';
				
			//comprobamos si tenemos una ubicacion
			}if($ubicacion == ''){
				$errores['sinUbicacion'] = 'true';
	
			}
			
			$objProductoUpdate = new Producto();
			$objProductoUpdate->setId(intval($idProducto));
			$objProductoUpdate->setNombre(($nombreProducto != '') ? $nombreProducto : null);
			$objProductoUpdate->setDescripcion(($descripcionProducto != '') ? $descripcionProducto : null);
			$objProductoUpdate->setIdEstanteria($estanteria);
			$objProductoUpdate->setIdBalda($balda);
			$objProductoUpdate->setIdCaja($caja);
			$objProductoUpdate->setIdTrastero($miTrastero->getId());

			//si se ha especificado nombre y ubicacion
			if (count($errores)==0) {
				//si producto modificado correctamente
				if ($objProductoUpdate->modificarProducto($bd)) {
					$mensaje['msj-content']="Producto modificado con exito";
					$mensaje['msj-type']="success";
					//recuperamos las etiquetas que tiene el producto
					$etiquetasProducto= Producto::recuperarEtiquetasPorProductoId($bd,$idProducto);

					//comprobamos las etiquetas a añadir
					$existe = false;
					//recorremos las etiquetas del formulario
					foreach ($arrayInputAñadirEtiquetas as $valueArrayInput) {
						if($valueArrayInput == '') continue;
						//recorremos las etiquetas que ya tiene el producto
						foreach($etiquetasProducto as $etiquetaProducto){
							//si la etiqueta del formulario coincide con una que ya tiene el producto
							if($valueArrayInput==$etiquetaProducto['idEtiqueta']){
								$existe= true;
							}
						}
						//si no existe, añadimos la etiqueta
						if(!$existe){
							Producto::añadirEtiquetaProducto($bd,$valueArrayInput,$idProducto);
						}	
						$existe=false;
					}

					//comprobamos las etiquetas a eliminar
					$existe=false;
					//recorremos las etiquetas del producto
					foreach($etiquetasProducto as $etiquetaProducto){
						//recorremos las etiquetas del formulario
						foreach($arrayInputAñadirEtiquetas as $valueArrayInput){
							if($etiquetaProducto['idEtiqueta'] == $valueArrayInput){
								$existe=true;
							}
						}
						// si la etiqueta del producto no esta en las etiquetas del formulario
						if(!$existe){
							//eliminamos la etiqueta
							Producto::eliminarEtiquetaProducto($bd,$etiquetaProducto['id']);
						}
						$existe=false;
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
