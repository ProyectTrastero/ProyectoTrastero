<?php

class Registro extends Dbh {

   
    //la tabla es de prueba
    protected function agregarUsuario($usuario,$nombre,$apellido,$contrasena,$telefono,$email){
        $query="insert into usuarios (usuario, contraseña, nombre, apellidos, correo, telefono) values(?, ?, ?, ?, ?, ?)";
        $stmt = $this->connect()->prepare($query);
        

        //encriptamos la contraseña
        $hasshedContrasena = password_hash($contrasena, PASSWORD_DEFAULT);

        if(!$stmt->execute(array($usuario,$hasshedContrasena,$nombre,$apellido,$email,$telefono))){
            $stmt=null;
            return false;
        }else{
            //si todo bien simplemente cerramos la conexion
            $stmt = null;
            return true;
        }
    }

    //comprobamos si el usuario o el email ya existe 
    protected function checkUsuario ($usuario, $email){
        $query = "select usuario from usuarios where usuario = ? or correo = ?;";
        $stmt = $this->connect()->prepare($query);
        

        //si falla nos redirige a la pagina de registro nuevamente
        if(!$stmt->execute(array($usuario, $email))){
            //cerramos conexion
            $stmt = null;
            return false;
            
        }
        //si no falla la sentencia comprobamos si tenemos algun resultado
        //si mayor que 0 es que ya existe
        if($stmt->rowCount() > 0 ){
            $stmt = null;
            return false;
        }else{
            $stmt = null;
            return true;
        }
    }

}
