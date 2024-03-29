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
    Trastero
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

$datosTrastero=$_SESSION['datosTrastero'];
$almacenEstanterias = $datosTrastero['almacenEstanterias'];
$almacenBaldas = $datosTrastero['almacenBaldas'];
$almacenCajas = $datosTrastero['almacenCajas'];

function existeNombre($nombre, $almacenBaldas, $almacenCajas, $almacenEstanterias){
    $encontrado = false;
        //Solo vamos a restringir las entradas con nombres iguales en las cajas y en las estanterías,
    //en las baldas no ya que entendemos que se darán repeticiones en ellas.

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
$idElemento = trim(filter_input(INPUT_POST, 'id',FILTER_SANITIZE_STRING));
$nombre = trim(filter_input(INPUT_POST, 'nombre',FILTER_SANITIZE_STRING));
$nuevoNombre = trim(filter_input(INPUT_POST, 'nuevoNombre',FILTER_SANITIZE_STRING));
$response=[];
if(trim($nuevoNombre)==""){
    $response['cambiado']=false;
    $response['mensaje'] = "El nombre del elemento no puede estar vacío."; 
}else if(existeNombre($nuevoNombre, $almacenBaldas, $almacenCajas, $almacenEstanterias)){
    $response['cambiado']=false;
    $response['mensaje'] = "Ya existe un elemento con ese nombre.";
}else{
    
    if(str_contains($nombre, 'Balda')){
        $idEstanteria = Balda::obtenerIdEstanteria($bd, $idElemento);
        foreach($almacenBaldas as $clave=>$valor){
            if($valor->getNombre()==$nombre && $valor->getIdEstanteria()==$idEstanteria){
                $valor->setNombre($nuevoNombre);
                $valor->actualizarNombre($bd, $nuevoNombre);
            }
        }
        
    }else{
    
    foreach($almacenCajas as $clave=>$valor){
        if($valor->getNombre()==$nombre){
            $valor->setNombre($nuevoNombre);
            $valor->actualizarNombre($bd, $nuevoNombre);
        }
    }
    
    foreach($almacenEstanterias as $clave=>$valor){
        if($valor->getNombre()==$nombre){
            $valor->setNombre($nuevoNombre);
            $valor->actualizarNombre($bd, $nuevoNombre);
        }
    }
        
    }
    
//    
    
    
    $response['cambiado']=true;
}

$datosTrastero['almacenEstanterias']=$almacenEstanterias;
$datosTrastero['almacenBaldas'] = $almacenBaldas;
$datosTrastero['almacenCajas'] = $almacenCajas;
$_SESSION['datosTrastero'] = $datosTrastero;



header('Content-type: application/json');
echo json_encode($response); 
die;   
