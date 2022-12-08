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
    Validacion
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
    $usuario = $_SESSION['usuario'];
    $idTrastero = $_SESSION['miTrastero']->getId();
    //recuperamos las estanterias del trastero
    $estanterias = Estanteria::recuperarEstanteriasPorIdTrastero($bd, $idTrastero);
    $errores= array();
    $msj=array();

    if (isset($_REQUEST['getIdTrastero'])) {
        echo $idTrastero;
        die;
    }
    
    if (isset($_REQUEST["idEstanteria"])) {    
        //recuperamos el id de la estanteria seleccionada
        $estanteriaSelected = $_REQUEST["idEstanteria"];
        //recuperamos las baldas 
        $baldas = Balda::recuperarBaldasPorIdEstanteria($bd, $estanteriaSelected);
        echo json_encode($baldas);
        die;
    }

    if (isset($_REQUEST['idBalda'])) {
        //recuperamos el id de la balda seleccionada
        $baldaSelected = $_REQUEST['idBalda'];
        //recuperamos las cajas
        $cajas = Caja::recuperarCajaPorIdBalda($bd,$baldaSelected);
        echo json_encode($cajas);
        die;
    }

    if (isset($_REQUEST['getCajasSinAsignar'])) {
        $cajasSinAsignar = Caja::recuperarCajasSinAsignarPorIdTrastero($bd,$idTrastero);
        echo json_encode($cajasSinAsignar);
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
        
        if($ubicacion == 'ubicacionCajasSinAsignar'){
            $caja=$cajaSinAsignar;
        }

        if($caja==0){
            $caja ='';
        }

        if ($nombreProducto == '' ) {
            array_push($errores, 'nombreInvalido');
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
        
        //set a null todos los campos vacios
        foreach ($datos as $key => $value) {
            if ($value=='') {
                $datos[$key]=null;
            }
        }
        
        
        if (count($errores)==0) {
            if (Producto::añadirProducto($bd,$datos)) {
                $msj['msj-content']="Producto añadido con exito";
                $msj['msj-type']="success";
            }
        
        }else{
            $msj['msj-content']="Error al añadir el producto";
            $msj['msj-type']="danger";
        }
    }

    

    echo $blade->run('añadirProducto',['usuario'=>$usuario, 'estanterias'=>$estanterias, 'errores'=>$errores, 'msj'=>$msj]);
}
 