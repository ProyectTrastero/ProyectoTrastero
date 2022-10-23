<?php

use App\Usuario;

class RegistroController extends Usuario{

    
    
    private $contrasenaRepeat;
    private $telefono;
   

    public function __construct( $usuario, $nombre, $apellido,
    $contrasena, $contrasenaRepeat, $telefono, $email)
    {
        parent::__construct(
            $usuario,
            $nombre,
            $apellido,
            $email,
            $contrasena
        );

        // $this->usuario=$usuario;
        // $this->nombre=$nombre;
        // $this->apellido=$apellido;
        //$this->contrasena=$contrasena;
        $this->contrasenaRepeat=$contrasenaRepeat;
        $this->telefono=$telefono;
        // $this->email=$email;
        
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
        if(!$this->telefonoInvalido()){
            array_push($errores,"telefonoInvalido");
        }
        if(empty(!$this->getUsuario()) && !empty($this->getEmail())){
            if(!$this->checkUsuarioExiste()){
                array_push($errores,"usuarioExiste");
            }
        }
        //si no hay errores agregamos el usuario a la base de datos
        if(count($errores)== 0){
            $this->agregarUsuario($this->getUsuario(), $this->getNombre(), $this->getApellidos(),
                                $this->getClave(), $this->telefono, $this->getEmail());
            return $errores;
        }else{
            return $errores;
        }
        
    }

    //si hay algun campo vacio
    private function camposVacios(){

        if(empty($this->getUsuario()) || empty($this->getNombre()) || empty($this->getApellidos()) 
        || empty($this->getClave()) || empty($this->contrasenaRepeat) || empty($this->telefono) || empty($this->getEmail())){

            return false;
        }else{
            return true;
        }
    }
    //formatio valido : debe comenzar con una letra,solo se aceptan catacteres alfanumericos, (-) y (_)
    //longitud minima de 4 y maxima de 30
    private function usuarioInvalido(){
        $usuario=$this->sanearInput($this->getUsuario());
        if(!preg_match("/^[a-zA-Z][a-zA-Z0-9-_]{3,29}$/", $usuario)){
            return false;
        }else{
            return true;
        }
    }
    //formato valido: solo letras longitud minima de 3 y maxima de 20
    private function nombreInvalido(){
        $nombre=$this->sanearInput($this->getNombre());
        if(!preg_match("/^[A-Za-z]{3,20}$/", $nombre)){
            return false;
        }else{
            return true;
        }
    }
   //formato valido: solo letras longitud minima de 3 y maxima de 20
    private function apellidoInvalido(){
        $apellido=$this->sanearInput($this->getApellidos());
        if(!preg_match("/[A-Za-z]{3,20}$/", $apellido)){
            return false;
        }else{
            return true;
        }
    }

    private function contrasenasNoIguales(){
        $contrasena=$this->sanearInput($this->getClave());
        $contrasenaRepeat=$this->sanearInput($this->contrasenaRepeat);
        if($contrasena !== $contrasenaRepeat){
            return false;
        }else{
            return true;
        }
    }

    //contraseña de 8 a 16 caracteres, al menos un digito, una minuscula y una mayuscula
    private function contrasenaInvalida(){
        $contrasena=$this->sanearInput($this->getClave());
        if(!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/",$contrasena)){
            return false;
        }else{
            return true;
        }
    }
    //formato valido email@email.com
    private function emailInvalido(){
        $email=$this->sanearInput($this->getEmail());
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return false;
        }else{
            return true;
        }
    }
    
    //solo numeros
    private function telefonoInvalido(){
        $telefono=$this->sanearInput($this->telefono);
        if(!filter_var($telefono,FILTER_VALIDATE_INT)){
            return false;
        }else{
            return true;
        }
        

    }
    //verificamos si el usuario o el email ya existe en la base de datos
    private function checkUsuarioExiste(){
        $usuario=$this->sanearInput($this->getUsuario());
        $email=$this->sanearInput($this->getEmail());
        if(!$this->checkUsuario($usuario, $email)){
            return false;
        }else{
            return true;
        }
        

    }
    //saneamos lo introducido por el usuario en los imputs
    function sanearInput($data) {
        //eliminamos caracteres innecesarios como espacios, saltos de linea, tabulaciones
        $data = trim($data);
        //eliminamos backslashes
        $data = stripslashes($data);
        //combierte caracteres especiales en entidades html
        $data = htmlspecialchars($data);
        return $data;
    }


}



