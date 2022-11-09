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


if (isset($_POST['submit'])) {
    
    //recuperamos la informacion
    (isset($_POST['alias'])) ? $alias = $_POST['alias'] : $alias="";
    
    (isset($_POST['nombre'])) ? $nombre = $_POST['nombre'] : $nombre="";
    
    (isset($_POST['apellidos'])) ? $apellidos = $_POST['apellidos'] : $apellidos="";
    
    (isset($_POST['email'])) ? $email = $_POST['email'] : $email="";
    
    (isset($_POST['clave'])) ? $clave = $_POST['clave'] : $clave="";
    
    (isset($_POST['contraseñaRepeat'])) ? $contraseñaRepeat = $_POST['contraseñaRepeat'] : $contraseñaRepeat="";
    
    //array para enviar a la vista y asi mantener los datos
    $datos = array('alias' =>$alias,'nombre'=>$nombre,'apellidos'=>$apellidos,'email'=>$email,'clave'=>$clave,'contraseñaRepeat'=>$contraseñaRepeat);

    
    $validacion = new Validacion($alias, $nombre, $apellidos, $clave, $contraseñaRepeat, $email);
    
    //ejecutamos metodo registrar usuario el cual tiene todas las comprobaciones
    
    $errores = $validacion->registrarUsuario();
    
    
    //si tenemos errores volvemos a lanzar la vista registro 
    if (count($errores) > 0) {
        echo $blade->run('registro', ['error' => $errores,'datos' => $datos]);
    } else {
        //si no hay errores vamos a el index el cual redirige a acceso, no se si mejor deberia ir a acceso desde aca
        header("location: ../public/index.php?error=none");
        
    }
}else{

    //por defecto muestra vista registro
    echo $blade->run("registro");
}
