<?php
namespace App;
use PDO;

class Validacion{

    static function validarRegistro(array $datos, PDO $bd){
    $errores = array();

    if (!Validacion::camposVacios($datos)) {
        array_push($errores, "camposVacios");
    }

    if (!Validacion::aliasInvalido($datos['alias'])) {
        array_push($errores, "aliasInvalido");
    }
    if (!Validacion::nombreInvalido($datos['nombre'])) {
        array_push($errores, "nombreInvalido");
    }
    if (!Validacion::apellidoInvalido($datos['apellidos'])) {
        array_push($errores, "apellidoInvalido");
    }
    if (!Validacion::contrasenasNoIguales($datos['clave'], $datos['claveRepeat'])) {
        array_push($errores, "contrasenasNoIguales");
    }
    if (!Validacion::contrasenaInvalida($datos['clave'])) {
        array_push($errores, "contrasenaInvalida");
    }
    if (!Validacion::correoInvalido($datos['correo'])) {
        array_push($errores, "emailInvalido");
    }

    if (empty(!$datos['alias'])) {
        if (Usuario::existeAlias($bd, $datos['alias'])) {
            array_push($errores, "aliasExiste");
        }
    }

    if (empty(!$datos['correo'])) {
        if (Usuario::existeCorreo($bd, $datos['correo'])) {
            array_push($errores, "correoExiste");
        }
    }
    //si no hay errores agregamos el usuario a la base de datos
    if (count($errores) == 0) {
        Usuario::agregarUsuario(
            $bd,
            $datos['alias'],
            $datos['clave'],
            $datos['nombre'],
            $datos['apellidos'],
            $datos['correo']
        );
    }
    return $errores;
}
    
    //si hay algun campo vacio
    //
    static function camposVacios(array $campos):bool{

        foreach ($campos as $campo) {
            if(empty($campo)){
                return false;
            }
            return true;
        }

    }
     
     
    //formatio valido : Los alias solo pueden contener letras, números, guiones y guiones bajos
    //y una longitud maxima de 100 caracteres
    static function aliasInvalido($alias){
        
        if(!preg_match("/^[a-zA-Z0-9-_]{1,100}$/", $alias)){
            return false;
        }else{
            return true;
        }
    }
    //formato valido: debe comenzar por una letra,
    //solo se admiten espacios en blanco y letras
    //longitud maxima de 100 caracteres
    static function nombreInvalido($nombre){
        
        if(!preg_match("/^[A-Za-z][\sA-Za-z]{0,99}$/", $nombre)){
            return false;
        }else{
            return true;
        }
    }
    //formato valido: debe comenzar por una letra,
    //solo se admiten espacios en blanco y letras
    //longitud maxima de 100 caracteres
    static function apellidoInvalido($apellidos){
        
        if(!preg_match("/^[A-Za-z][\sA-Za-z]{0,99}$$/", $apellidos)){
            return false;
        }else{
            return true;
        }
    }
    //comprobamos que las contraseñas coincidan
    static function contrasenasNoIguales($clave, $claveRepeat){
        
        if($clave !== $claveRepeat){
            return false;
        }else{
            return true;
        }
    }

    //longitud minina 8, al menos un digito, una minuscula y una mayuscula
    static function contrasenaInvalida($clave){
        
        if(!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/",$clave)){
            return false;
        }else{
            return true;
        }
    }
    //formato valido email@email.com
    static function correoInvalido($correo){
        
        if(!filter_var($correo, FILTER_VALIDATE_EMAIL)){
            return false;
        }else{
            return true;
        }
    }
    
    
    
    //saneamos lo introducido por el usuario en los imputs
    static function sanearInput($input) {
        //eliminamos caracteres innecesarios como espacios de más, saltos de linea, tabulaciones
        $input = trim($input);
        //eliminamos backslashes
        $input = stripslashes($input);
        //combierte caracteres especiales en entidades html
        $input = htmlspecialchars($input);
        return $input;
    }

}



