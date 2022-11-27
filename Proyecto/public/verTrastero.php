<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

require "../vendor/autoload.php";

use eftec\bladeone\BladeOne;
use Dotenv\Dotenv;

use App\{
    BD,
    Usuario,
    Validacion, 
    Estanteria, 
    Balda,
    Caja,
    Trasteros
};
// Inicializa el acceso a las variables de entorno
$dotenv = Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();


// Inicializa el acceso a las variables de entorno
$views = __DIR__ . '/../vistas';
$cache = __DIR__ . '/../cache';
$blade = new BladeOne($views, $cache, BladeOne::MODE_DEBUG);

try {
    $bd = BD::getConexion();
} catch (PDOException $error) {
    echo $blade->run("cnxbderror", compact('error'));
    die;
}
session_start();

if(empty($_SESSION['datosTrastero'])){
    $datosTrastero = array();
    $idTrastero = $_SESSION['idTrastero'];
    $usuario = $_SESSION['usuario'];
    $baldas=array();
    $estanterias = Estanteria::recuperarEstanteriasPorIdTrastero($bd, $idTrastero);
    foreach ($estanterias as $estanteria){
        $idEstanteria=$estanteria->getId();
        $listadoBaldas= Balda::recuperarBaldasPorIdEstanteria($bd, $idEstanteria);
        foreach($listadoBaldas as $balda){
            $baldas[]=$balda;
        }   
    }
    $cajas = Caja::recuperarCajasPorIdTrastero($bd, $idTrastero);
    
    //Falta Recuperar los productos
    $datosTrastero['cajas'] = $cajas;
    $datosTrastero['baldas']= $baldas;
    $datosTrastero['estanterias']=$estanterias;
    $_SESSION['datosTrastero']=$datosTrastero;
    
   
}else{
    $datosTrastero=$_SESSION['datosTrastero'];
    $usuario = $_SESSION['usuario'];
    
}

if(isset($_POST['volver'])){
    //Modificar con la página de Dani.
    header("Location: paginaDani.php");
}else{
    echo $blade->run('verTrastero', compact('datosTrastero'));
}
