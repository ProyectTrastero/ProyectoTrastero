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


if (isset($_POST['registrarse'])) {
    
    //recuperamos la informacion
    
    (isset($_POST['alias'])) ? $alias = Validacion::sanearInput(filter_input(INPUT_POST, 'alias')) : $alias="";
    (isset($_POST['nombre'])) ? $nombre = Validacion::sanearInput(filter_input(INPUT_POST, 'nombre')) : $nombre="";
    (isset($_POST['apellidos'])) ? $apellidos = Validacion::sanearInput(filter_input(INPUT_POST, 'apellidos')) : $apellidos="";
    (isset($_POST['correo'])) ? $correo = Validacion::sanearInput(filter_input(INPUT_POST, 'correo')) : $correo="";
    (isset($_POST['clave'])) ? $clave = Validacion::sanearInput(filter_input(INPUT_POST, 'clave')) : $clave="";
    (isset($_POST['claveRepeat'])) ? $claveRepeat = Validacion::sanearInput(filter_input(INPUT_POST, 'claveRepeat')) : $claveRepeat="";
    
    //array para enviar a la vista y asi mantener los datos
    $datos = array('alias' =>$alias,'nombre'=>$nombre,'apellidos'=>$apellidos,'correo'=>$correo,'clave'=>$clave,'claveRepeat'=>$claveRepeat);

    //validamos los campos, si todos los campos son validos se registra el usuario
    $errores = validarRegistro($bd,$datos);
    
    
    //si tenemos errores volvemos a lanzar la vista registro 
    if (count($errores) > 0) {
        echo $blade->run('registro', ['error' => $errores,'datos' => $datos]);
    } else {
        //si no hay errores vamos a el index el cual redirige a acceso, no se si mejor deberia ir a acceso desde aca
        header("location: ../public/index.php");
    }
}else if(isset($_POST['volver'])){
    header("location: ../public/index.php");
}
else{

    //por defecto muestra vista registro
    echo $blade->run("registro");
}

function validarRegistro(PDO $bd, array $datos): array
{
    $errores = array();

    if (!Validacion::camposVacios($datos)) {
        array_push($errores, "camposVacios");
    }

    if (!Validacion::aliasInvalido($datos['alias'])) {
        array_push($errores, "usuarioInvalido");
    }
    if (!Validacion::nombreInvalido($datos['nombre'])) {
        array_push($errores, "nombreInvalido");
    }
    if (!Validacion::apellidoInvalido($datos['apellidos'])) {
        array_push($errores, "apellidoInvalido");
    }
    if (!Validacion::clavesNoIguales($datos['clave'], $datos['claveRepeat'])) {
        array_push($errores, "clavesNoIguales");
    }
    if (!Validacion::claveInvalida($datos['clave'])) {
        array_push($errores, "claveInvalida");
    }
    if (!Validacion::correoInvalido($datos['correo'])) {
        array_push($errores, "correoInvalido");
    }

    if (!empty($datos['alias'])) {
        if (!Usuario::checkExisteAlias($bd, $datos['alias'])) {
            array_push($errores, "aliasExiste");
        }
    }

    if (!empty($datos['correo'])) {
        if (!Usuario::checkExisteCorreo($bd, $datos['correo'])) {
            array_push($errores, "correoExiste");
        }
    }
    //si no hay errores agregamos el usuario a la base de datos
    if (count($errores) == 0) {
        Usuario::agregarUsuario(
            $bd,
            $datos['alias'],
            $datos['clave'],
            $datos['nombre'],
            $datos['apellidos'],
            $datos['correo']
        );
    }

    return $errores;
}