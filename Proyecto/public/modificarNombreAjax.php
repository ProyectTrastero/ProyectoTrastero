<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */
require "../vendor/autoload.php";
use App\{
    BD,
    Usuario,
    Validacion, 
    Estanteria, 
    Balda,
    Caja,
    Trastero
};

session_start();

$datosTrastero=$_SESSION['datosTrastero'];
$almacenEstanterias = $datosTrastero['almacenEstanterias'];
$almacenBaldas = $datosTrastero['almacenBaldas'];
$almacenCajas = $datosTrastero['almacenCajas'];

function existeNombre($nombre, $almacenBaldas, $almacenCajas, $almacenEstanterias){
    $encontrado = false;
    foreach($almacenBaldas as $clave=>$valor){
        if($valor->getNombre()==$nombre){
            $encontrado = true;
        }
    }

    foreach($almacenCajas as $clave=>$valor){
        if($valor->getNombre()==$nombre){
            $encontrado = true;
        }
    }

    foreach($almacenEstanterias as $clave=>$valor){
        if($valor->getNombre()==$nombre){
            $encontrado = true;
        }
    }
    
    return $encontrado;
}


$existe = false;
$nombre=trim(filter_input(INPUT_POST, 'nombre',FILTER_SANITIZE_STRING));
$nuevoNombre =trim(filter_input(INPUT_POST, 'nuevoNombre',FILTER_SANITIZE_STRING));
$response=[];
if(existeNombre($nuevoNombre, $almacenCajas, $almacenBaldas, $almacenEstanterias)){
    $response['cambiado']=false;
    $response['nombre'] = $nombre;
}else{
    foreach($almacenBaldas as $clave=>$valor){
        if($valor->getNombre()==$nombre){
            $valor->setNombre($nuevoNombre);
        }
    }
    
    foreach($almacenCajas as $clave=>$valor){
        if($valor->getNombre()==$nombre){
            $valor->setNombre($nuevoNombre);
        }
    }
    
    foreach($almacenEstanterias as $clave=>$valor){
        if($valor->getNombre()==$nombre){
            $valor->setNombre($nuevoNombre);
        }
    }
    
    
    $response['cambiado']=true;
}

$datosTrastero['almacenEstanterias']=$almacenEstanterias;
$datosTrastero['almacenBaldas'] = $almacenBaldas;
$datosTrastero['almacenCajas'] = $almacenCajas;
$_SESSION['datosTrastero'] = $datosTrastero;



header('Content-type: application/json');
echo json_encode($response); 
die;   
