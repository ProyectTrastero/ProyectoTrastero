<?php
require "../vendor/autoload.php";

use eftec\bladeone\BladeOne;
use Dotenv\Dotenv;
use App\{
    BD,
    Usuario
};


// Funciones de validación de datos del formulario de registro
// Validación del nombre con expresión regular
function esNombreValido(string $nombre): bool {
    return preg_match("/^\w{3,15}$/", $nombre);
}

function esApellidosValido(string $apellidos): bool {
    return preg_match("/^\w{3,25}$/", $apellidos);
}

// 
function esPasswordValido(string $clave): bool {
    $alMenos1Digito = false;
    for ($i = 0; $i < strlen($clave); $i++) {
        if (is_numeric($clave[$i])) {
            $alMenos1Digito = true;
            break;
        }
    }
    return $alMenos1Digito && strpos($clave, " ") === false && (strlen($clave) >= 6);
}

function esEmailValido(string $email): bool {
    return (filter_var($email, FILTER_VALIDATE_EMAIL) && substr($email, -3) === ".es");
}



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


// Si el usuario ya está validado recojo el nombre y lo meto en la vista de inicio
if (isset($_SESSION['usuario'])) {
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
    } else {
       $usuario = $_SESSION['usuario'];
    /*
    $usuario = $_SESSION['usuario'];
    $nombre = $usuario->getNombre();
    echo $blade->run("inicio", compact('nombre'));
    die;*/
    }
    
//Si no esta el usuario validado
}else{ 
    //Si le da al boton de iniciar sesion
    if (isset($_POST['procsesion'])){
            // Lee los valores del formulario
            $nombre = trim(filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING));
            $clave = trim(filter_input(INPUT_POST, 'clave', FILTER_SANITIZE_STRING));
            $usuario = Usuario::recuperaUsuarioPorCredencial($bd, $nombre, $clave);
                if ($usuario) {
                    session_start();
                    $_SESSION['usuario'] = $usuario;
                    $idUsuario = intval($usuario->getId());
                    $trasteros = App\Trasteros::recuperaTrasteroPorUsuario($bd, $idUsuario);
                    $_SESSION['trasteros'] = $trasteros;
                    //$nombretrastero = $trastero->getNombre();
                // Redirijo a la pantalla de acceso con la sesion usuario y trastero
                   echo $blade->run("acceso", compact ('usuario', 'trasteros'));
                   
                }else{
                // Si los credenciales son incorrectos vista del formulario de sesion con el flag de error activado
                echo $blade->run("sesion", ['error' => true]);
                die;
                } 
    //Si le da al boton de registro
                //LO HACE DANI
    }elseif(isset($_POST['botonregistro'])){
        echo $blade->run("registro");
        die;
    //LO HACE EMMA
    }elseif(isset($_POST['forPassword'])){
        echo "contraseña enviada";
    }else{
        echo $blade->run("sesion");
        die;
    }
}
   /*ESTA HECHO POR DANI
       //Si le damos a confirmar registro 
        }elseif (isset($_POST['procregitro'])){
            //Recoge todas los input
        $usuario = $_SESSION['usuario'];
        $alias = trim(filter_input(INPUT_POST, 'alias', FILTER_SANITIZE_STRING));
        $nombre = trim(filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING));
        $apellidos = trim(filter_input(INPUT_POST, 'apellidos', FILTER_SANITIZE_STRING));
        $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING));
        $clave = trim(filter_input(INPUT_POST, 'clave', FILTER_SANITIZE_STRING));
        
    
        //Validaciones
        $errorAlias = empty($alias) ;
        $errorNombre = empty($nombre) || !esNombreValido($nombre);
        $errorApellidos = empty($apellidos) || !esApellidosValido($apellidos);
        $errorClave = empty($clave) || !esClaveValido($clave);
        $errorEmail = empty($email) || !esEmailValido($email);
        
        if ($errorAlias || $errorNombre || $errorApellidos || $errorClave || $errorEmail) {
            echo $blade->run("registro", compact('alias', 'nombre', 'apellidos', 'clave', 'email', 'errorAlias', 'errorNombre', 'errorApellidos', 'errorClave', 'errorEmail'));
            die;
        } else {
            //constructor
            
            
            
            $usuario->setAlias($alias);
            $usuario->setNombre($nombre);
            $usuario->setNombre($apellidos);
            $usuario->setClave($clave);
            $usuario->setEmail($email);
            
            try {
                $usuario->persiste($bd);
            } catch (PDOException $e) {
                echo $blade->run("registro", ['errorBD' => true]);
                die();
            }       
        }
    *
    */

  
