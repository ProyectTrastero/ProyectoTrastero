<?php
require_once __DIR__ . '/../vendor/autoload.php';

use eftec\bladeone\BladeOne;
use Dotenv\Dotenv;

use App\{
    BD,
    Balda,
    Estanteria
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
    $id = $_SESSION['id'];
    
    if (isset($_REQUEST["idEstanteria"])) {    
        $estanteriaSelected = $_REQUEST["idEstanteria"];
        $baldas = Balda::getBaldaByIdEstanteria($bd,$estanteriaSelected);

        $daniel = json_encode($baldas);
        echo json_encode($baldas);
        die;
    }

    if (isset($_REQUEST['idBalda'])) {
        $baldaSelected = $_REQUEST['idBalda'];
        //recuperamos las cajas

    }

    

    //recuperamos las estanterias del trastero
    $estanterias = Estanteria::recuperarEstanteriasPorIdTrastero($bd, $id);
    


    echo $blade->run('añadirProducto',['usuario'=>$usuario, 'estanterias'=>$estanterias]);
}
 