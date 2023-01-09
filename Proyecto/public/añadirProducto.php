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
    //cerramos sesion
    if (isset($_REQUEST['cerrarSesion'])) {
        session_destroy();
        header("Location: index.php");
        die;
    }
    //vamos a editar perfil
    if (isset($_REQUEST['perfilUsuario'])) {
        header("Location: editarPerfil.php");
        die;
    }


    $usuario = $_SESSION['usuario'];
    $idTrastero = $_SESSION['miTrastero']->getId();

    //recuperamos las estanterias del trastero
    $estanterias = Estanteria::recuperarEstanteriasPorIdTrastero($bd, $idTrastero);

    //comprobamos si tenemos estanterias
    if (count($estanterias) > 0) {
        //recuperamos baldas
        $baldas = Balda::recuperarBaldasPorIdEstanteria($bd, $estanterias[0]->getId());
        //recuperamos las cajas
        $cajas = Caja::recuperarCajaPorIdBalda($bd, $baldas[0]->getId());
    } else {
        //inicializamos baldas con un array vacio
        $baldas = array();
        //inicializamos cajas con un array vacio
        $cajas = array();
    }

    //recuperamos las cajas sin ubicar
    $cajasSinUbicar = Caja::recuperarCajasSinUbicarPorIdTrastero($bd, $idTrastero);

    //recuperamos las etiquetas del usuario
    $etiquetas = Etiqueta::recuperaEtiquetasPorUsuario($bd, $usuario->getId());

    $arrayAñadirEtiquetas = array();

    $errores = array();
    $msj = array();

    ///peticiones del front


    if (isset($_GET["idEstanteria"])) {
        //recuperamos el id de la estanteria seleccionada
        $estanteriaSelected = $_REQUEST["idEstanteria"];
        //recuperamos las baldas 
        $baldas = Balda::recuperarBaldasPorIdEstanteria($bd, $estanteriaSelected);
        $cajas = Caja::recuperarCajaPorIdBalda($bd, $baldas[0]->getId());
        $response = ['baldas' => $baldas, 'cajas' => $cajas];
        echo json_encode($response);
        die;
    }

    if (isset($_GET['idBalda'])) {
        //recuperamos el id de la balda seleccionada
        $baldaSelected = $_REQUEST['idBalda'];
        //recuperamos las cajas
        $cajas = Caja::recuperarCajaPorIdBalda($bd, $baldaSelected);
        echo json_encode($cajas);
        die;
    }


    if (isset($_GET['crearEtiqueta'])) {
        $nombreEtiqueta = Validacion::sanearInput($_REQUEST['crearEtiqueta']);
        //comprobamos si la etiqueta tiene un nombre
        if ($nombreEtiqueta != '') {
            $etiqueta = new Etiqueta(null, $nombreEtiqueta, $usuario->getId());
            //comprobamos si el nombre de la etiqueta ya existe
            if (!$etiqueta->checkExisteNombreEtiqueta($bd)) {
                //el nombre de la etiqueta ya exite
                $mensaje = ['msj-content' => 'Nombre de etiqueta ya existe, elija otro.', 'msj-type' => 'danger'];
            } else {
                //guardamos la etiqueta
                $etiqueta->guardarEtiqueta($bd);
                $mensaje = ['msj-content' => 'Etiqueta creada.', 'msj-type' => 'success'];
            }
        } else {
            $mensaje = ['msj-content' => 'El campo nombre de etiqueta es obligatorio.', 'msj-type' => 'danger'];
        }
        echo json_encode($mensaje);
        die;
    }



    if (isset($_GET['añadirEtiqueta'])) {
        $idEtiqueta = intval($_REQUEST['añadirEtiqueta']);
        $objectEtiqueta = Etiqueta::recuperarEtiquetaPorId($bd, $idEtiqueta);
        array_push($arrayAñadirEtiquetas, $objectEtiqueta);
        echo json_encode($arrayAñadirEtiquetas);

        die;
    }
    //despues de crear una etiqueta, recuperamos las etiquetas para actualizar el select
    if (isset($_GET['getEtiquetas'])) {
        //recuperamos las etiquetas del usuario
        $etiquetasUpdate = Etiqueta::recuperaEtiquetasPorUsuario($bd, $usuario->getId());
        echo json_encode($etiquetasUpdate);

        die;
    }
    //peticion para eliminar etiqueta
    if(isset($_GET['idEliminarEtiqueta'])){
        //recibimos el id de la etiqueta
        $idEliminarEtiqueta = $_GET['idEliminarEtiqueta'];
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


    //recibimos las peticiones post del cliente en formato json
    if (!is_null($data = json_decode(file_get_contents('php://input'), true))) {
        

        //peticion para añadir producto
        if (array_key_exists('añadirProducto', $data)) {
            (isset($data['nombreProducto'])) ? $nombreProducto = Validacion::sanearInput($data['nombreProducto']) : $nombreProducto = '';
            (isset($data['descripcionProducto'])) ? $descripcionProducto = Validacion::sanearInput($data['descripcionProducto']) : $descripcionProducto = '';
            (isset($data['estanteria'])) ? $estanteria = intval(Validacion::sanearInput($data['estanteria'])) : $estanteria = '';
            (isset($data['balda'])) ? $balda = intval(Validacion::sanearInput($data['balda'])) : $balda = '';
            (isset($data['caja'])) ? $caja = intval(Validacion::sanearInput($data['caja'])) : $caja = '';
            (isset($data['cajasSinUbicar'])) ? $idCajaSinUbicar = intval(Validacion::sanearInput($data['cajasSinUbicar'])) : $idCajaSinUbicar = '';
            (isset($data['ubicacion'])) ? $ubicacion = $data['ubicacion'] : $ubicacion = "";
            (isset($data['inputAñadirEtiquetas'])) ? $añadirEtiquetas = Validacion::sanearInput($data['inputAñadirEtiquetas']) : $añadirEtiquetas = '';

            //comprobamos si se ha ingresado un nombre 
            if ($nombreProducto == '') {
                $mensaje['error'] = 'nombreInvalido';
                $mensaje['msj-content'] = "Ingresa un nombre al producto.";
                $mensaje['msj-type'] = "danger";
                echo json_encode($mensaje);
                die;
            }

            //si ubicacion en caja sin ubicacion
            if ($ubicacion == 'ubicacionCajasSinUbicar') {
                if ($idCajaSinUbicar == 0) {
                    $mensaje['msj-content'] = "Ubicacion invalida";
                    $mensaje['msj-type'] = "danger";
                    echo json_encode($mensaje);
                    die;
                }
                $caja = $idCajaSinUbicar;
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

            //si no se ha seleccionado una caja en estanterias
            if ($caja == 0) {
                $caja = '';
            }

            //separamos los id de las etiquetas
            $arrayInputAñadirEtiquetas = preg_split("/\s/", $añadirEtiquetas);


            //fecha creacion producto
            $fechaIngreso = Producto::obtenerFechaIngreso();

            $datos = [
                'nombreProducto' => $nombreProducto,
                'descripcionProducto' => $descripcionProducto,
                'fechaIngreso' => $fechaIngreso,
                'estanteria' => $estanteria,
                'balda' => $balda,
                'caja' => $caja,
                'idTrastero' => $idTrastero
            ];

            //set a null todos los campos vacios para añadir a la base de datos como null
            foreach ($datos as $key => $value) {
                if ($value == '') {
                    $datos[$key] = null;
                }
            }


            //añadimos el producto
            $idProducto = Producto::añadirProducto($bd, $datos);
            //si producto añadido correctamente
            if ($idProducto != -1) {
                //mensaje producto añadido correctamente
                $mensaje['msj-content'] = "Producto añadido con exito";
                $mensaje['msj-type'] = "success";
                //comprobamos si tenemos etiquetas para añadir al producto
                foreach ($arrayInputAñadirEtiquetas as $idEtiqueta) {
                    if ($idEtiqueta != "") {
                        $idEtiqueta = intval($idEtiqueta);
                        //añadimos las etiquetas a el producto
                        if (!Producto::añadirEtiquetaProducto($bd, $idEtiqueta, $idProducto)) {
                            //si falla mostramos error
                            $mensaje['msj-content'] = "Error al añadir etiquetas al producto";
                            $mensaje['msj-type'] = "danger";
                        }
                    }
                }
            }

            echo json_encode($mensaje);
            die;
        }
    }




    echo $blade->run('añadirProducto', [
        'usuario' => $usuario,
        'estanterias' => $estanterias,
        'baldas' => $baldas,
        'cajas' => $cajas,
        'cajasSinUbicar' => $cajasSinUbicar,
        'etiquetas' => $etiquetas,
        'errores' => $errores,
        'msj' => $msj
    ]);
}
