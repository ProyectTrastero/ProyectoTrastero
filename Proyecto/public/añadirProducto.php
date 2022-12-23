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
    Etiqueta,
    Usuario
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
$mensaje="";
if(isset($_SESSION['usuario'])){
    //añadido por Emma para dar funcionalidad al nav
    if(isset($_REQUEST['cerrarSesion'])){
        session_destroy();
        header("Location: index.php");
        die;
    }
    if(isset($_REQUEST['perfilUsuario'])){
        header("Location: editarPerfil.php");
    }
    
    //Hasta aquí
    
    $usuario = $_SESSION['usuario'];
    $idTrastero = $_SESSION['miTrastero']->getId();
    //recuperamos las estanterias del trastero
    $estanterias = Estanteria::recuperarEstanteriasPorIdTrastero($bd, $idTrastero);
    //recuperamos las etiquetas del usuario
    $etiquetas = Etiqueta::recuperaEtiquetasPorUsuario($bd,$usuario->getId());
    
    static $arrayAñadirEtiquetas = array();
    
    $errores= array();
    $msj=array();

    if (isset($_GET['getIdTrastero'])) {
        echo $idTrastero;
        die;
    }
    
    if (isset($_GET["idEstanteria"])) {    
        //recuperamos el id de la estanteria seleccionada
        $estanteriaSelected = $_REQUEST["idEstanteria"];
        //recuperamos las baldas 
        $baldas = Balda::recuperarBaldasPorIdEstanteria($bd, $estanteriaSelected);
        echo json_encode($baldas);
        die;
    }

    if (isset($_GET['idBalda'])) {
        //recuperamos el id de la balda seleccionada
        $baldaSelected = $_REQUEST['idBalda'];
        //recuperamos las cajas
        $cajas = Caja::recuperarCajaPorIdBalda($bd,$baldaSelected);
        echo json_encode($cajas);
        die;
    }

    if (isset($_GET['getCajasSinAsignar'])) {
        $cajasSinAsignar = Caja::recuperarCajasSinAsignarPorIdTrastero($bd,$idTrastero);
        echo json_encode($cajasSinAsignar);
        die;
    }

//    if (isset($_GET['crearEtiqueta'])){
//        $nombreEtiqueta = Validacion::sanearInput($_REQUEST['crearEtiqueta']);
//        if ($nombreEtiqueta != '') {
//            $etiqueta = new Etiqueta(null, $nombreEtiqueta, $usuario->getId());
//            $etiqueta->guardarEtiqueta($bd);
//            $mensaje='etiqueta añadida';
//        }else{
//            $mensaje='El campo nombre de etiqueta es obligatorio.';
//        }
//        echo $mensaje;
//        die;
//    
//    }

    
     if(isset($_POST['crearEtiqueta'])){
        
         $nombreEtiqueta = trim(filter_input(INPUT_POST, 'nombreEtiqueta', FILTER_SANITIZE_STRING));
         $idUsuario = $usuario->getId();
         if($nombreEtiqueta==""){
            $mensaje="El campo nombre de etiqueta es obligatorio.";   
         }else{
             if(Etiqueta::existeEtiqueta($bd, $nombreEtiqueta, $idUsuario)){
                 $mensaje="Ya tiene una etiqueta con ese nombre";
             }else{
                 $etiqueta = new Etiqueta();
                 $etiqueta->setNombre($nombreEtiqueta);
                 $etiqueta->setIdUsuario($idUsuario);
                 $etiqueta->guardarEtiqueta($bd);
                 $mensaje = "Etiqueta creada correctamente";
             }  
         }  
     }
    
    if(isset($_GET['añadirEtiqueta'])){
        $idEtiqueta = intval($_REQUEST['añadirEtiqueta']);
        $objectEtiqueta = Etiqueta::recuperarEtiquetaPorId($bd, $idEtiqueta);
        array_push($arrayAñadirEtiquetas,$objectEtiqueta);
        $daniel = json_encode($arrayAñadirEtiquetas);
        echo json_encode($arrayAñadirEtiquetas);
        
        die;        
    }

    if(isset($_POST['volver'])){
        header('location: accederTrastero.php');
        die;
    }

    if(isset($_POST['añadir'])){
        (isset($_POST['nombreProducto'])) ? $nombreProducto = Validacion::sanearInput($_POST['nombreProducto']) : $nombreProducto = '';
        (isset($_POST['descripcionProducto'])) ? $descripcionProducto = Validacion::sanearInput($_POST['descripcionProducto']) : $descripcionProducto = '';
        (isset($_POST['estanteria'])) ? $estanteria = intval(Validacion::sanearInput($_POST['estanteria'])) : $estanteria = '';
        (isset($_POST['balda'])) ? $balda = intval(Validacion::sanearInput($_POST['balda'])) : $balda = '';
        (isset($_POST['caja'])) ? $caja = intval(Validacion::sanearInput($_POST['caja'])) : $caja = '';
        (isset($_POST['cajasSinAsignar'])) ? $cajaSinAsignar = intval(Validacion::sanearInput($_POST['cajasSinAsignar'])) : $cajaSinAsignar='';
        (isset($_POST['ubicacion'])) ? $ubicacion = $_POST['ubicacion'] : $ubicacion = "";
        (isset($_POST['inputAñadirEtiquetas'])) ? $añadirEtiquetas = Validacion::sanearInput(($_POST['inputAñadirEtiquetas'])) : $añadirEtiquetas = '';
        //separamos los id de las etiquetas
        $arrayInputAñadirEtiquetas = preg_split("/\s/",$añadirEtiquetas);
        //si se ha seleccionado una caja sin ubicacion
        if($ubicacion == 'ubicacionCajasSinAsignar'){
            $caja=$cajaSinAsignar;
        }
        //si no se ha seleccionado una caja en estanterias
        if($caja==0){
            $caja ='';
        }

        //comprobamos si se ha ingresado un nombre 
        if ($nombreProducto == '' ) {
            array_push($errores, 'nombreInvalido');
        //comprobamos si tenemos una ubicacion
        }if($ubicacion == ''){
            array_push($errores, 'sinUbicacion');
        }

        $datos=['nombreProducto'=>$nombreProducto,
                'descripcionProducto'=>$descripcionProducto,
                'estanteria'=>$estanteria,
                'balda'=>$balda,
                'caja'=>$caja,
                'idTrastero'=>$idTrastero
                ];
        
        //set a null todos los campos vacios para añadir a la base de datos como null
        foreach ($datos as $key => $value) {
            if ($value=='') {
                $datos[$key]=null;
            }
        }
        
        
        if (count($errores)==0) {
            $idProducto=Producto::añadirProducto($bd,$datos);
            if ($idProducto != -1) {
                $msj['msj-content']="Producto añadido con exito";
                $msj['msj-type']="success";
                //comprobamos si tenemos etiquetas para añadir al producto
                foreach ($arrayInputAñadirEtiquetas as $idEtiqueta) {
                    if($idEtiqueta != ""){
                        $idEtiqueta=intval($idEtiqueta);
                        //enlazamos la etiqueta con el producto
                        //si falla mostramos error
                        if(!Producto::añadirEtiquetaProducto($bd,$idEtiqueta,$idProducto)){
                            $msj['msj-content']="Error al añadir el etiqueta";
                            $msj['msj-type']="danger";
                        }
                    }
                }
            }
        
        }else{
            $msj['msj-content']="Error al añadir el producto";
            $msj['msj-type']="danger";
        }
    }

    

    echo $blade->run('añadirProducto',['usuario'=>$usuario, 'estanterias'=>$estanterias, 'etiquetas'=>$etiquetas, 'errores'=>$errores, 'msj'=>$msj, 'mensaje'=>$mensaje]);
}
 