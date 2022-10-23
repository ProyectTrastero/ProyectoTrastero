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



if (isset($_REQUEST['botonlogout'])) {
// Destruyo la sesión
        session_unset();
        session_destroy();
        setcookie(session_name(), '', 0, '/');
// Invoco la vista del formulario de iniciar sesion
        echo $blade->run("sesion");
        die;
} elseif (isset($_REQUEST['botonperfil'])) {
        //Esta parte la esta haciendo Dani
        $usuario = $_SESSION['usuario'];
        $nombre = $usuario->getNombre();
        $clave = $usuario->getClave();
        $email = $usuario->getEmail();
        echo $blade->run("perfil", compact('nombre', 'clave', 'email'));
        die;

//Si no esta el usuario validado
}else{
            // Lee los valores del formulario
            $nombre = trim(filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING));
            $clave = trim(filter_input(INPUT_POST, 'clave', FILTER_SANITIZE_STRING));
            $usuario = Usuario::recuperaUsuarioPorCredencial($bd, $nombre, $clave);
                if ($usuario) {
                    session_start();
                    $_SESSION['usuario'] = $usuario;
                    header("location:../public/acceso.php");
               
                   
                }else{
                // Si los credenciales son incorrectos vista del formulario de sesion con el flag de error activado
                echo $blade->run("sesion", ['error' => true]);
                die;
                } 
} 
    