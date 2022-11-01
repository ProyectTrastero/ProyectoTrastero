<?php
require_once __DIR__ . '/../vendor/autoload.php';

use eftec\bladeone\BladeOne;
use Dotenv\Dotenv;

use App\{
    BD,
    Usuario,
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

$datos = array();
if (isset($_POST['submit'])) {

    //recuperamos la informacion
    (isset($_POST['alias'])) ? $alias = Validacion::sanearInput(filter_input(INPUT_POST, "alias")) : $alias = "";

    (isset($_POST['nombre'])) ? $nombre = Validacion::sanearInput(filter_input(INPUT_POST, "nombre")) : $nombre = "";

    (isset($_POST['apellidos'])) ? $apellidos = Validacion::sanearInput(filter_input(INPUT_POST, "apellidos")) : $apellidos = "";

    (isset($_POST['correo'])) ? $correo = Validacion::sanearInput(filter_input(INPUT_POST, "correo")) : $correo = "";

    (isset($_POST['clave'])) ? $clave = Validacion::sanearInput(filter_input(INPUT_POST, "clave")) : $clave = "";

    (isset($_POST['claveRepeat'])) ? $claveRepeat = Validacion::sanearInput(filter_input(INPUT_POST, "claveRepeat")) : $claveRepeat = "";

    //array con los datos del formulario
    $datos = array('alias' => $alias, 'nombre' => $nombre, 'apellidos' => $apellidos, 'correo' => $correo, 'clave' => $clave, 'claveRepeat' => $claveRepeat);
    
    //ejecutamos metodo validarRegistro el cual tiene todas las comprobaciones
    $errores = Validacion::validarRegistro($datos, $bd);
    
    //si tenemos errores volvemos a lanzar la vista registro con los datos que ha introducido y los errores que hemos obtenido
    if (count($errores) > 0) {
        echo $blade->run('registro', ['error' => $errores, 'datos' => $datos]);
    } else {
        //si no hay errores vamos a el index el cual redirige a acceso, no se si mejor deberia ir a acceso desde aca
        header("location: ../public/index.php?error=none");
    }
} else {

    //por defecto muestra vista registro
    echo $blade->run("registro");
}




