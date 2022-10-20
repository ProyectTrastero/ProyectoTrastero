<<<<<<< HEAD:public/index.php
<?php

require "../vendor/autoload.php";

use eftec\bladeone\BladeOne;

/*
  use Dotenv\Dotenv;
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

<<<<<<< HEAD:Proyecto/public/index.php
if (isset($_REQUEST['botonregistro'])){
        echo $blade->run("registro");
}
=======
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Trastero</title>
</head>
<body>
    
</body>
</html>
>>>>>>> emma:Proyecto/public/index.php
=======
echo $blade->run('inicio');

>>>>>>> df40c8b0d10eb6d8418d9ecd6785f685a4973033:public/index.php
