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

//Si le damos a iniciar sesion
if (isset($_POST['procsesion'])) {
    /*
     * Recojemos el alias y la clave 
     * recuperamos el usuario por las credenciales 
     */
    $alias = trim(filter_input(INPUT_POST, 'alias', FILTER_SANITIZE_STRING));
    $clave = trim(filter_input(INPUT_POST, 'clave', FILTER_SANITIZE_STRING));
    $usuario = Usuario::recuperaUsuarioPorCredencial($bd, $alias, $clave);
    /*
     * si el usuario existe se crea una sesion con ese usuario y redirijimos a acceso.php
     */
        if ($usuario) {
            session_start();
            $_SESSION['usuario'] = $usuario;
            header("location:../public/acceso.php");
    /*
     * si no existe mandamos la vista del formulario de sesion con el flag de error activado
     */
            
        }else{
            echo $blade->run("sesion", ['error' => true]);
            die;
        } 
//Si le da al boton de registro          
}elseif (isset($_POST['botonregistro'])){
    header("location:../public/registro.php");
// si le da al boton para recuperar contraseña
}elseif (isset ($_POST['recuperarcontrasena'])){
    header("location:../public/recuperarContraseña.php"); 

//si no mandamos la vista del formulario de inicio de sesion
}else{
    echo $blade->run("sesion");
    die;
}
