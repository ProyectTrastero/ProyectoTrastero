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
    $usuario = $_SESSION['usuario'];

    if (isset($_POST['volver'])) {
        header('location: acceso.php');
        die;
    }

    if (isset($_POST['guardar'])) {
        //obtenemos los datos
        $id = $usuario->getId();
        (isset($_POST['nombre'])) ? $nombre = Validacion::sanearInput(filter_input(INPUT_POST, 'nombre')) : $nombre = "";
        (isset($_POST['apellidos'])) ? $apellidos = Validacion::sanearInput(filter_input(INPUT_POST, 'apellidos')) : $apellidos = "";
        (isset($_POST['alias'])) ? $alias = Validacion::sanearInput(filter_input(INPUT_POST, 'alias')) : $alias = "";
        (isset($_POST['clave'])) ? $clave = Validacion::sanearInput(filter_input(INPUT_POST, 'clave')) : $clave = "";
        (isset($_POST['correo'])) ? $correo = Validacion::sanearInput(filter_input(INPUT_POST, 'correo')) : $correo = "";

        $datos = array(
            'id' => $id, 
            'nombre' => $nombre, 
            'apellidos' => $apellidos, 
            'alias' => $alias, 
            'clave' => $clave, 
            'correo' => $correo
        );
        //validamos los datos, si datos validos, se actualiza el usuario en la base de datos
        $errores = array();
        $errores = validarCambiosPerfil($bd, $datos);
        //comprobamos si tenemos errores
        if (count($errores) > 0) {
            echo $blade->run('perfil', ['errores' => $errores, 'datos' => $datos]);
            die;
        } else {
            //si no tenemos errores actualizamos el $usuario en local
            $usuario->setNombre($nombre);
            $usuario->setApellidos($apellidos);
            $usuario->setAlias($alias);
            $usuario->setClave($clave);
            $usuario->setCorreo($correo);
        }
    }

    $datos = array(
        'nombre' => $usuario->getNombre(),
        'apellidos' => $usuario->getApellidos(),
        'alias' => $usuario->getAlias(),
        'clave' => $usuario->getClave(),
        'correo' => $usuario->getCorreo()
    );
    echo $blade->run("perfil", ['datos' => $datos] );
    die;
}

function validarCambiosPerfil(PDO $bd, array $datos): array
{
    $errores = array();

    if (!Validacion::camposVacios($datos)) {
        array_push($errores, 'camposVacios');
    }

    if (!Validacion::nombreInvalido($datos['nombre'])) {
        array_push($errores, 'nombreInvalido');
    }

    if (!Validacion::apellidoInvalido($datos['apellidos'])) {
        array_push($errores, 'apellidoInvalido');
    }

    if (!Validacion::aliasInvalido($datos['alias'])) {
        array_push($errores, 'aliasInvalido');
    }
    $daniel= $GLOBALS['usuario']->getAlias();

    if (!empty($datos['alias']) && $datos['alias'] != $GLOBALS['usuario']->getAlias()) {
        if (!Usuario::checkExisteAlias($bd, $datos['alias'])) {
            array_push($errores, "aliasExiste");
        }
    }

    if (!Validacion::claveInvalida($datos['clave'])) {
        array_push($errores, 'claveInvalida');
    }

    if (!Validacion::correoInvalido($datos['correo'])) {
        array_push($errores, 'correoInvalido');
    }

    if (!empty($datos['alias']) && $datos['correo'] != $GLOBALS['usuario']->getCorreo()) {
        if (!Usuario::checkExisteAlias($bd, $datos['alias'])) {
            array_push($errores, "correoExiste");
        }
    }

    if (count($errores) == 0) {
        //guardo los cambios
        if (!Usuario::modificarUsuario($bd, $datos['id'], $datos['alias'], $datos['nombre'], $datos['apellidos'], $datos['clave'], $datos['correo'])) {
            array_push($errores, 'errorModificarUsuario');
        }
    }
    return $errores;
}