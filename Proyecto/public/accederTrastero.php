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

if (isset($_POST['volverTodosTrasteros'])){
    header("location:../public/acceso.php"); 
    die;
}elseif (isset($_POST['verTrastero'])){
    echo "Vemos trastero";
    die;
}elseif (isset($_POST['añadirProducto'])){
    header("location:../public/añadirProducto.php"); 
    die;
}elseif (isset($_POST['buscarProducto'])){
    echo "buscamos productoo";
    die;
}else{
    $usuario = $_SESSION['usuario'];
    $trasteros = $_SESSION['trasteros'];
    $miTrastero = $_SESSION['miTrastero'];
    echo $blade->run("accederTrastero", compact ('usuario', 'miTrastero'));
    die;
}