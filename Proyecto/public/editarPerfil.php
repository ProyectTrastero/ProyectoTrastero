<?php
require "../vendor/autoload.php";
use eftec\bladeone\BladeOne;
use Dotenv\Dotenv;
use App\{
    BD
};

// Inicializa blade
$views = __DIR__ . '/../vistas';
$cache = __DIR__ . '/../cache';
$blade = new BladeOne($views, $cache, BladeOne::MODE_DEBUG);

// Inicializa el acceso a las variables de entorno
$dotenv = Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

// Establece conexión a la base de datos PDO
try {
    $bd = BD::getConexion();
} catch (PDOException $error) {
    echo $blade->run("cnxbderror", compact('error'));
    die;
}

session_start();

if (isset($_SESSION['usuario'])) {
	$usuario = $_SESSION['usuario'];
    $nombre = $usuario->getNombre();
    $apellidos = $usuario->getApellidos();
    $alias = $usuario->getAlias();
    $clave = $usuario->getClave();
    $correo = $usuario->getEmail();

	echo $blade->run("perfil", compact('nombre','apellidos','alias', 'clave', 'correo',));
    

	die;
}


