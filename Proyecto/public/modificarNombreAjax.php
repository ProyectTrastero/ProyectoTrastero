<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */
session_start();

function existeNombre($nombre){
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

$datosTrastero = $_SESSION['datosTrastero'];
$almacenBaldas = $datosTrastero['almacenBaldas'];
$almacenEstanterias = $datosTrastero['almacenEstanterias'];
$almacenCajas = $datosTrastero['almacenCajas'];
$existe = false;
$nombre=trim(filter_input(INPUT_POST, 'nombre',FILTER_SANITIZE_STRING));
$nuevoNombre =trim(filter_input(INPUT_POST, 'nuevoNombre',FILTER_SANITIZE_STRING));
$response=[];
if(existeNombre($nuevoNombre)){
    $response['cambiado']=false;
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



header('Content-type: application/json');
echo json_encode($response); 
die;   
