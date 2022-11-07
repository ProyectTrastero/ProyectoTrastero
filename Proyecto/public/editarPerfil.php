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

session_start();
if (isset($_SESSION['usuario'])) {

    if (isset($_POST['guardar'])) {
        //obtenemos los datos
        (isset($_POST['nombre'])) ? $nombre = Validacion::sanearInput(filter_input(INPUT_POST, 'nombre')) : $nombre="";
        (isset($_POST['apellidos'])) ? $apellidos = Validacion::sanearInput(filter_input(INPUT_POST, 'apellidos')) : $apellidos="";
        (isset($_POST['alias'])) ? $alias = Validacion::sanearInput(filter_input(INPUT_POST, 'alias')) : $alias="";
        (isset($_POST['clave'])) ? $clave = Validacion::sanearInput(filter_input(INPUT_POST, 'clave')) : $clave="";
        (isset($_POST['correo'])) ? $correo = Validacion::sanearInput(filter_input(INPUT_POST, 'correo')) : $correo="";
        $datos= array('nombre'=>$nombre, 'apellidos'=>$apellidos, 'alias'=>$alias, 'clave'=>$clave, 'correo'=>$correo);
        //validamos los datos


        
    }
    if (isset($_POST['volver'])) {
        # code...
    }

    $usuario = $_SESSION['usuario'];
    $nombre = $usuario->getNombre();
    $apellidos= $usuario->getApellidos();
    $alias = $usuario->getAlias();
    $clave = $usuario->getClave();
    $correo = $usuario->getCorreo();
    echo $blade->run("perfil", compact('nombre','apellidos','alias', 'clave', 'correo'));
    die;
}
    
function validarCambiosPerfil(array $datos):array {
    $errores=array();

    if (!Validacion::camposVacios($datos)) {
        array_push($errores,'camposVacios');
    }

    if (!Validacion::nombreInvalido($datos['nombre'])) {
        array_push($errores,'nombreInvalido');
    }

    if (!Validacion::apellidoInvalido($datos['apellidos'])) {
        array_push($errores,'apellidoInvalido');
    }

    if(!Validacion::aliasInvalido($datos['alias'])){
        array_push($errores, 'aliasInvalido');
    }

    if(!Validacion::claveInvalida($datos['clave'])){
        array_push($errores, 'claveInvalida');
    }

    if(!Validacion::correoInvalido($datos['correo'])){
        array_push($errores, 'correoInvalido');
    }

    if(count($errores) == 0){
        //guardo los cambios
    }
    return $errores;

}