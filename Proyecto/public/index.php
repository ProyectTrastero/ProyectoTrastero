<?php

require "../vendor/autoload.php";
use eftec\bladeone\BladeOne;

/*
  use App\{
  BD,
  Usuario
  };
 */

// Inicializamos blade
$views = __DIR__ . '/../vistas';
$cache = __DIR__ . '/../cache';
$blade = new BladeOne($views, $cache, BladeOne::MODE_DEBUG);



if (isset($_REQUEST['botonregistro'])) {
    echo $blade->run('registro');
    
    die;
}

if(isset($_GET['error'])=="none"){
  echo $blade->run ('sesion');
  die;
}



//echo $blade->run('inicio');
echo $blade->run('perfil');

