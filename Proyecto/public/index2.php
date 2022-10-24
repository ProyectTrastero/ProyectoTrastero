<?php
require "../vendor/autoload.php";
use eftec\bladeone\BladeOne;
// Inicializa el acceso a las variables de entorno
$views = __DIR__ . '/../vistas';
$cache = __DIR__ . '/../cache';
$blade = new BladeOne($views, $cache, BladeOne::MODE_DEBUG);

if(isset($_POST["botonregistro"])){
    header("location: registro.php");
}
echo $blade->run("sesion");