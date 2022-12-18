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

try {
    $bd = BD::getConexion();
} catch (PDOException $error) {
    echo $blade->run("cnxbderror", compact('error'));
    die;
}

session_start();

$datosTrastero = $_SESSION['datosTrastero'];
$almacenBaldas=$datosTrastero['almacenBaldas'];
$almacenEstanterias = $datosTrastero['almacenEstanterias'];
$nombreEstanteria=filter_input(INPUT_POST, 'estanteriaSeleccionada',FILTER_SANITIZE_STRING);
//$nombreEstanteria="Estanteria 1";
$idEstanteria= Estanteria::obtenerIdPorNombre($bd, $nombreEstanteria, $datosTrastero['trastero']->getId());
$baldas= Balda::recuperarBaldasPorIdEstanteria($bd, $idEstanteria);
//$idEstanteria;
//foreach($almacenEstanterias as $clave=>$valor){
//    if($valor->getNombre()==$nombreEstanteria){
//       $idEstanteria=$valor->getId();
//    }
//}
$baldasRecuperadas=array();
foreach ($baldas as $balda){
    
        $baldasRecuperadas[]=$balda->getNombre();
    
}
//$numeroBaldas=count($estanterias[intval($estanteriaSeleccionada)]);
//$baldas=$estanterias[intVal($estanteriaSeleccionada)-1];
$response=[];
try {
    $response['nombreBaldas']=$baldasRecuperadas;  
} catch (Exception $ex) {
    $response['error'] = true;
}
header('Content-type: application/json');
echo json_encode($response); 
die;   
