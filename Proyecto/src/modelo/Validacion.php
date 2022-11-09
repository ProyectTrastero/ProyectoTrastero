<?php
namespace App;
use App\Usuario;

class Validacion extends Usuario{
    
    private $contraseñaRepeat;
    

    public function __construct( $alias, $nombre, $apellido,
    $contraseña, $contraseñaRepeat, $email)
    {
        parent::__construct(1,$alias,$nombre,$apellido,$email,$contraseña);

        $this->contraseñaRepeat= $contraseñaRepeat;
        
    }

    public function registrarUsuario(){
       $errores= array();
       
        if(!$this->camposVacios()){
            array_push($errores,"camposVacios");
        }
        
        if(!$this->usuarioInvalido()){
            array_push($errores,"usuarioInvalido");
        }
        if(!$this->nombreInvalido()){
            array_push($errores,"nombreInvalido");
        }
        if(!$this->apellidoInvalido()){
            array_push($errores,"apellidoInvalido");
        }
        if(!$this->contrasenasNoIguales()){
            array_push($errores,"contrasenasNoIguales");
        }
        if(!$this->contrasenaInvalida()){
            array_push($errores,"contrasenaInvalida");
        }
        if(!$this->emailInvalido()){
            array_push($errores,"emailInvalido");
        }
        
        if(empty(!$this->getAlias())){
            if(!$this->checkExistAlias($this->getAlias())){
                array_push($errores,"aliasExiste");
            }
        }

        if(empty(!$this->getEmail())){
            if(!$this->checkExistCorreo($this->getEmail())){
                array_push($errores,"correoExiste");
            }
        }
        //si no hay errores agregamos el usuario a la base de datos
        if(count($errores)== 0){
            $this->agregarUsuario($this->getAlias(),  $this->getClave(),$this->getNombre(), $this->getApellidos(),
                                $this->getEmail());
            return $errores;
        }else{
            return $errores;
        }
        
    }
    //si hay algun campo vacio
    //
    private function camposVacios(){

        if(empty($this->getAlias()) || empty($this->getNombre()) || empty($this->getApellidos()) 
        || empty($this->getClave()) || empty($this->contraseñaRepeat)  || empty($this->getEmail())){

            return false;
        }else{
            return true;
        }
    }
     
     
    //formatio valido : Los alias solo pueden contener letras, números, guiones y guiones bajos
    //y una longitud maxima de 100 caracteres
    private function usuarioInvalido(){
        $this->setAlias($this->sanearInput($this->getAlias()));
        if(!preg_match("/^[a-zA-Z0-9-_]{1,100}$/", $this->getAlias())){
            return false;
        }else{
            return true;
        }
    }
    //formato valido: debe comenzar por una letra,
    //solo se admiten espacios en blanco y letras
    //longitud maxima de 100 caracteres
    private function nombreInvalido(){
        $this->setNombre($this->sanearInput($this->getNombre()));
        if(!preg_match("/^[A-Za-z][\sA-Za-z]{0,99}$/", $this->getNombre())){
            return false;
        }else{
            return true;
        }
    }
    //formato valido: debe comenzar por una letra,
    //solo se admiten espacios en blanco y letras
    //longitud maxima de 100 caracteres
    private function apellidoInvalido(){
        $this->setApellidos($this->sanearInput($this->getApellidos()));
        if(!preg_match("/^[A-Za-z][\sA-Za-z]{0,99}$$/", $this->getApellidos())){
            return false;
        }else{
            return true;
        }
    }
    //comprobamos que las contraseñas coincidan
    private function contrasenasNoIguales(){
        $contrasena=$this->sanearInput($this->getClave());
        $contraseñaRepeat=$this->sanearInput($this->contraseñaRepeat);
        if($contrasena !== $contraseñaRepeat){
            return false;
        }else{
            return true;
        }
    }

    //longitud minina 8, al menos un digito, una minuscula y una mayuscula
    private function contrasenaInvalida(){
        $this->setClave($this->sanearInput($this->getClave()));
        if(!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/",$this->getClave())){
            return false;
        }else{
            return true;
        }
    }
    //formato valido email@email.com
    private function emailInvalido(){
        $this->setEmail($this->sanearInput($this->getEmail()));
        if(!filter_var($this->getEmail(), FILTER_VALIDATE_EMAIL)){
            return false;
        }else{
            return true;
        }
    }
    
    
    
    //saneamos lo introducido por el usuario en los imputs
    function sanearInput($data) {
        //eliminamos caracteres innecesarios como espacios de más, saltos de linea, tabulaciones
        $data = trim($data);
        //eliminamos backslashes
        $data = stripslashes($data);
        //combierte caracteres especiales en entidades html
        $data = htmlspecialchars($data);
        return $data;
    }


}


