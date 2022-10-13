<?php
require "../vendor/autoload.php";


use eftec\bladeone\BladeOne;
use Dotenv\Dotenv;
use App\{
    BD,
    Usuario
};

//session_start();  ---No puedo iniciar aqui sesion por que si no la vista que me aparece es la de usuarios

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
// Si el usuario ya está validado
if (isset($_SESSION['usuario'])) {
         echo $blade->run("inicio");
    /*// Si se solicita cerrar la sesión
    if (isset($_REQUEST['botonlogout'])) {
        // Destruyo la sesión
        session_unset();
        session_destroy();
        setcookie(session_name(), '', 0, '/');
        // Invoco la vista del formulario de login
        echo $blade->run("formlogin");
        die;
    } else { // Si se solicita una nueva partida
        $usuario = $_SESSION['usuario'];
        // Redirijo al cliente al script de gestión del juego
        header("Location:juego.php?botonnuevapartida");
        die;
    }*/


    // Sino
} else {
        echo $blade->run("inicio");
}
if (isset($_REQUEST['botonsesion'])){
    echo $blade->run("sesion");
}

if (isset($_REQUEST['botonregistro'])){
        echo $blade->run("registro");
}