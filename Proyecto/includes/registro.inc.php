<?php

require "../vendor/autoload.php";

use eftec\bladeone\BladeOne;

if (isset($_POST['submit'])) {
    $views = __DIR__ . '/../vistas';
    $cache = __DIR__ . '/../cache';
    $blade = new BladeOne($views, $cache, BladeOne::MODE_DEBUG);

    //recuperamos la informacion
    (isset($_POST['usuario'])) ? $usuario = $_POST['usuario'] : $usuario="";
    
    (isset($_POST['nombre'])) ? $nombre = $_POST['nombre'] : $nombre="";
    
    (isset($_POST['apellido'])) ? $apellido = $_POST['apellido'] : $apellido="";
    
    (isset($_POST['telefono'])) ? $telefono = $_POST['telefono'] : $telefono="";
    
    (isset($_POST['email'])) ? $email = $_POST['email'] : $email="";
    
    (isset($_POST['contrasena'])) ? $contrasena = $_POST['contrasena'] : $contrasena="";
    
    (isset($_POST['contrasenaRepeat'])) ? $contrasenaRepeat = $_POST['contrasenaRepeat'] : $contrasenaRepeat="";
    
    
    $datos = array('usuario' =>$usuario,'nombre'=>$nombre,'apellido'=>$apellido,'telefono'=>$telefono,'email'=>$email,'contrasena'=>$contrasena,'contrasenaRepeat'=>$contrasenaRepeat);
    //incluimos todas las clases necesarias
    require_once '../config/config.php';
    //include '../public/index.php';
    include "../src/modelo/Dbh.class.php";
    include '../src/modelo/Registro.class.php';
    include '../src/controllers/Registro.controller.php';

    $registrar = new RegistroController($usuario, $nombre, $apellido, $contrasena, $contrasenaRepeat,
            $telefono, $email);

    //ejecutamos metodo registrar usuario el cual tiene todas las comprobaciones

    $errores = $registrar->registrarUsuario();
    
    
    //si tenemos errores volvemos a lanzar la vista registro 
    if (count($errores) > 0) {
        echo $blade->run('registro', ['error' => $errores,'datos' => $datos]);
    } else {
        //si no hay errores
        header("location: ../public/index.php?error=none");
        
    }
} 
