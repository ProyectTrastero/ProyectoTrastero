<?php
require "../vendor/autoload.php";

use eftec\bladeone\BladeOne;
use Dotenv\Dotenv;
use App\{
    BD,
    Usuario
};

// Inicializa el acceso a las variables de entorno
$dotenv = Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

// Inicializa el acceso a las variables de entorno
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

if (isset($_SESSION['usuario'])){
    $usuario = $_SESSION['usuario'];
    $idUsuario = intval($usuario->getId());
    $trasteros = App\Trasteros::recuperaTrasteroPorUsuario($bd, $idUsuario);
    $_SESSION['trasteros'] = $trasteros;
    
    echo $blade->run("acceso", compact ('usuario', 'trasteros'));

    
}elseif (isset($_REQUEST['salir'])) {
// Destruyo la sesión
        session_unset();
        session_destroy();
        setcookie(session_name(), '', 0, '/');
// Invoco la vista del formulario de iniciar sesion
        echo $blade->run("sesion");
        die;

        
//DANI SOLO PUEDES TOCAR AQUI       
// }elseif (isset($_REQUEST['perfilusuario'])) {
//         //Esta parte la esta haciendo Dani
//         $usuario = $_SESSION['usuario'];

//         $nombre = $usuario->getNombre();
//         $apellidos = $usuario->getApellidos();
//         $alias = $usuario->getAlias();
//         $clave = $usuario->getClave();
//         $email = $usuario->getEmail();
//         echo $blade->run("perfil", compact('nombre','apellidos','alias', 'clave', 'email',));
//         die;

// ///HASTA AQUI ¡¡NO TOQUES MASSSSSSS¡¡¡¡¡jajajaja 
}


