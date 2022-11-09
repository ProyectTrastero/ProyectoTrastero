<?php
namespace App;

namespace App;

use App\{
    Usuario
};
use Dotenv\Validator;
use PDO;

class Validacion
{
    
    //si hay algun campo vacio
    //
    static function camposVacios(array $campos): bool
    {
        foreach ($campos as $campo) {
            if (empty(($campo))) {
                return false;
            }
        }
         return true;
    }


    //formatio valido : Los alias solo pueden contener letras, números, guiones y guiones bajos
    //y una longitud maxima de 100 caracteres
    static function aliasInvalido(string $alias): bool
    {

        if (!preg_match("/^[a-zA-Z0-9-_]{1,100}$/", $alias)) {
            return false;
        } else {
            return true;
        }
    }
    //formato valido: debe comenzar por una letra,
    //solo se admiten espacios en blanco y letras
    //longitud maxima de 100 caracteres
    static function nombreInvalido(string $nombre): bool
    {

        if (!preg_match("/^[A-Za-z][\sA-Za-z]{0,99}$/", $nombre)) {
            return false;
        } else {
            return true;
        }
    }
    //formato valido: debe comenzar por una letra,
    //solo se admiten espacios en blanco y letras
    //longitud maxima de 100 caracteres
    static function apellidoInvalido(string $apellido): bool
    {

        if (!preg_match("/^[A-Za-z][\sA-Za-z]{0,99}$/", $apellido)) {
            return false;
        } else {
            return true;
        }
    }
    //comprobamos que las contraseñas coincidan
    static function clavesNoIguales(string $clave, string $claveRepeat): bool
    {

        if ($clave != $claveRepeat) {
            return false;
        } else {
            return true;
        }
    }

    //longitud minina 8, al menos un digito, una minuscula y una mayuscula
    static function claveInvalida(string $clave): bool
    {

        if (!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/", $clave)) {
            return false;
        } else {
            return true;
        }
    }
    //formato valido email@email.com
    static function correoInvalido(string $correo)
    {

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            return false;
        } else {
            return true;
        }
    }



    //saneamos lo introducido por el usuario en los imputs
    static function sanearInput($data)
    {
        //eliminamos caracteres innecesarios como espacios de más, saltos de linea, tabulaciones
        $data = trim($data);
        //eliminamos backslashes
        $data = stripslashes($data);
        //combierte caracteres especiales en entidades html
        $data = htmlspecialchars($data);
        return $data;
    }
}
