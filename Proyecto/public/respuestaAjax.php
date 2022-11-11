<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

session_start();

$datosTrastero = $_SESSION['datosTrastero'];
$estanterias=$datosTrastero['estanterias'];
$estanteriaSeleccionada=filter_input(INPUT_POST, 'estanteriaSeleccionada',FILTER_SANITIZE_STRING);
//$numeroBaldas=count($estanterias[intval($estanteriaSeleccionada)]);
$numeroBaldas=$estanterias[intVal($estanteriaSeleccionada)-1];
$response=[];
try {
    $response['numeroBaldas']=$numeroBaldas;  
} catch (Exception $ex) {
    $response['error'] = true;
}
header('Content-type: application/json');
echo json_encode($response); 
die;   
