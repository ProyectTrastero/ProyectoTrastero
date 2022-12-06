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
    Trasteros,
    Producto
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

//if(empty($_SESSION['datosTrastero'])){
//    $datosTrastero = array();
//    $idTrastero = $_SESSION['idTrastero'];
//    $usuario = $_SESSION['usuario'];
//    $baldas=array();
//    $estanterias = Estanteria::recuperarEstanteriasPorIdTrastero($bd, $idTrastero);
//    foreach ($estanterias as $estanteria){
//        $idEstanteria=$estanteria->getId();
//        $listadoBaldas= Balda::recuperarBaldasPorIdEstanteria($bd, $idEstanteria);
//        foreach($listadoBaldas as $balda){
//            $baldas[]=$balda;
//        }   
//    }
//    $cajas = Caja::recuperarCajasPorIdTrastero($bd, $idTrastero);
//    
//    //Falta Recuperar los productos
//    $datosTrastero['cajas'] = $cajas;
//    $datosTrastero['baldas']= $baldas;
//    $datosTrastero['estanterias']=$estanterias;
//    $_SESSION['datosTrastero']=$datosTrastero;
//    
//   
//}else{
//    $datosTrastero=$_SESSION['datosTrastero'];
//    $usuario = $_SESSION['usuario'];
//    
//}


$idTrastero = $_SESSION['id'];
//$miTrastero = Trasteros::recuperarTrasteroPorId($bd, $idTrastero);
$estanterias = Estanteria::recuperarEstanteriasPorIdTrastero($bd, $idTrastero);
$baldas = array();
foreach ($estanterias as $estanteria){
    $idEstanteria= $estanteria->getId();
    $baldasRecuperadas= Balda::recuperarBaldasPorIdEstanteria($bd, $idEstanteria);
    foreach ($baldasRecuperadas as $balda) {
        $baldas[]=$balda;
    }
}
$cajas= Caja::recuperarCajasPorIdTrastero($bd, $idTrastero);
//$productos = App\Producto:: recuperarProductosPorIdTrastero($bd, $idTrastero);
//if(isset($_SESSION['datosTrastero'])){
//   $datosTrastero=$_SESSION['datosTrastero'];
//}else{
    $datosTrastero=array();
    $datosTrastero['productos']=array();
    $datosTrastero['baldas']=$baldas;
    $datosTrastero['estanterias']=$estanterias;
    $datosTrastero['cajas']=$cajas;
//}




if(!empty($_REQUEST)){
    $idSelecionado=trim(filter_input(INPUT_POST,'id',FILTER_SANITIZE_STRING));
    $tipo="";
    $productosRecuperados;
    foreach ($estanterias as $estanteria){
        if($idSelecionado==$estanteria->getId()){
            $tipo="estanteria";
        }
    }
    
    foreach ($baldas as $balda){
        if($idSelecionado==$balda->getId()){
            $tipo="balda";
        }
    }
    
    foreach ($cajas as $caja){
        if($idSelecionado==$caja->getId()){
            $tipo="caja";
        }
    }
    
    if($tipo=="estanteria"){
        $productosRecuperados= Producto::recuperarProductosPorIdEstanteria($bd, $idSelecionado);
    }
    if($tipo=="balda"){
        $productosRecuperados= Producto::recuperarProductosPorIdBalda($bd, $idSelecionado);
    }
    if($tipo=="caja"){
         $productosRecuperados= Producto::recuperarProductosPorIdCaja($bd, $idSelecionado);
    }
   
//    $datosTrastero['productos']=$productosRecuperados;
//    $_SESSION['datosTrastero']=$datosTrastero;
    $productos=array();
    foreach ($productosRecuperados as $producto) {
        $productonuevo['fechaingreso']='22/07/22';
        $productonuevo['nombre']=$producto->getNombre();
        $productonuevo['descripcion']=$producto->getDescripcion();
        
        $productos[]=$productonuevo;
    }
    
    header('Content-type: application/json');
    echo json_encode($productos); 

    die;
 
}else{
    echo $blade->run('verTrastero', compact('datosTrastero'));
    
}
