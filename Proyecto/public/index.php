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

//Esta parte la he añadido yo. Emma

if(!empty($_SESSION['datosTrastero'])){
    $datosTrastero=$_SESSION['datosTrastero'];
        $tipo=$datosTrastero['tipo'];
    if($tipo=="guardar"){
       
        $almacenEstanterias = $datosTrastero['almacenEstanterias'];
        $almacenBaldas =$datosTrastero['almacenBaldas'];
        $almacenCajas =$datosTrastero['almacenCajas'];
        $nuevoTrastero =$datosTrastero['trastero'];
        $trasteroGuardado = $datosTrastero['guardado'];
            if(!$trasteroGuardado){
                $nuevoTrastero->eliminar($bd);
                foreach($almacenEstanterias as $clave=>$valor){
                    $valor->eliminar($bd);
                } 

                foreach($almacenBaldas as $clave=>$valor){
                    $valor->eliminar($bd);
                } 

                foreach($almacenCajas as $clave=>$valor){
                    $valor->eliminar($bd);
                } 
            }
        $_SESSION['datosTrastero'] = array();
    }
}

//Hasta aquí

if (isset($_POST['procsesion'])) {
    $alias = trim(filter_input(INPUT_POST, 'alias', FILTER_SANITIZE_STRING));
    $clave = trim(filter_input(INPUT_POST, 'clave', FILTER_SANITIZE_STRING));
    $usuario = Usuario::recuperaUsuarioPorCredencial($bd, $alias, $clave);
        if ($usuario) {
            session_start();
            $_SESSION['usuario'] = $usuario;
            header("location:../public/acceso.php");
        }else{
                // Si los credenciales son incorrectos vista del formulario de sesion con el flag de error activado
            echo $blade->run("sesion", ['error' => true]);
            die;
        } 
//Si le da al boton de registro          
}elseif (isset($_POST['botonregistro'])){
    header("location:../public/registro.php"); 
}elseif (isset ($_POST['recuperarcontrasena'])){
    header("location:../public/recuperarContraseña.php"); 
    
}else{
    echo $blade->run("sesion");
    die;
}
