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


$idTrastero = $_SESSION['id'];
$miTrastero = Trasteros::recuperarTrasteroPorId($bd, $idTrastero);
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
$datosTrastero=array();
$datosTrastero['nombre']=$miTrastero->getNombre();
$datosTrastero['productos']=array();
$datosTrastero['baldas']=$baldas;
$datosTrastero['estanterias']=$estanterias;
$datosTrastero['cajas']=$cajas;
//}




if(!empty($_REQUEST)){
    if(isset($_REQUEST['cerrarSesion'])){
        session_destroy();
        header("Location: index.php");
        die;
    }else if(isset($_REQUEST['perfilUsuario'])){
        header("Location: editarPerfil.php");
    }else{
        $idSelecionado=trim(filter_input(INPUT_POST,'id',FILTER_SANITIZE_STRING));
        $tipoElemento = trim(filter_input(INPUT_POST,'tipo',FILTER_SANITIZE_STRING));
    $tipo="";
    $productosRecuperados;
    
    if((strncmp($tipoElemento, "trastero", 4) === 0)){
        $tipo="trastero";
    }
    
    if((strncmp($tipoElemento, "estanteria", 4) === 0)){
        $tipo="estanteria";
    }
    
    
    if((strncmp($tipoElemento, "balda", 4) === 0)){
        $tipo="balda";
    }
    
    if((strncmp($tipoElemento, "caja", 4) === 0)){
        $tipo="caja";
    }
    
    if($tipo=="trastero"){
        $productosRecuperados= Producto::recuperarProductosPorIdTrastero($bd, $idTrastero);
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
        $productonuevo['fechaingreso']=$producto->getFechaIngreso();
        $productonuevo['nombre']=$producto->getNombre();
        $productonuevo['descripcion']=$producto->getDescripcion();
        $productonuevo['estanteria']=$producto->obtenerNombreEstanteria($bd);
        $productonuevo['balda']=$producto->obtenerNombreBalda($bd);
        $productonuevo['caja']=$producto->obtenerNombreCaja($bd);
        
        $productos[]=$productonuevo;
    }
    
    header('Content-type: application/json');
    echo json_encode($productos); 

    die;
 
    }
    
}else{
    echo $blade->run('verTrastero', compact('datosTrastero'));
    
}
