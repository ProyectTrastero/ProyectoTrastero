<?php
namespace App;

// PDO se usa para interaccionar con la base de datos relacional
use \PDO as PDO;

/**
 * Usuario representa al usuario que está usando la aplicación
 */
class Usuario extends BD {
    /**
     * Identificador del usuario y alias
     */
    private $usuario;

    /**
     * Nombre del usuario
     */
    private $nombre;

         /**
     * Apellidos del usuario
     */
    private $apellidos;
       
        /**
     * Email del usuario
     */
    private $email;
       
    /**
     * Clave del usuario
     */
    private $clave;
   

    /**
     * Constructor de la clase Usuario
     *
         * @param string $usuario Alias del usuario
     * @param string $nombre Nombre del usuario
         * @param string $apellidos Nombre del usuario
     * @param string $clave Clave del usuario
     * @param string $email Email del usuario
     *
     * @returns Hangman
     */
    public function __construct(string $usuario = null, string $nombre = null, string $apellidos = null, string $email = null, string $clave = null) {
        if (!is_null($usuario)) {
            $this->usuario = $usuario;
        }
                if (!is_null($nombre)) {
            $this->nombre = $nombre;
        }
                if (!is_null($apellidos)) {
            $this->apellidos = $apellidos;
        }
                if (!is_null($email)) {
            $this->email = $email;
        }
        if (!is_null($clave)) {
            $this->clave = $clave;
        }
       
    }


    public function getUsuario(): string {
        return $this->usuario;
    }
         public function setUsuario(string $usuario) {
        $this->usuario = $usuario;
    }


    public function getNombre(): string {
        return $this->nombre;
    }
    public function setNombre(string $nombre) {
        $this->nombre = $nombre;
    }


    public function getApellidos(): string {
        return $this->apellidos;
    }
    public function setApellidos(string $apellidos) {
        $this->apellidos = $apellidos;
    }

        public function getEmail(): string {
        return $this->email;
    }
    public function setEmail(string $email) {
        $this->email = $email;
    }
       
       
    public function getClave(): string {
        return $this->clave;
    }
    public function setClave(string $clave) {
        $this->clave = $clave;
    }


   


    /**
     * Recupera un objeto usuario dado su nombre de usuario y clave
     *
     * @param PDO $bd Conexión a la base de datos
     * @param string $nombre Nombre de usuario
     * @param string $clave Clave del usuario
     *
     * @returns Usuario que corresponde a ese nombre y clave o null en caso contrario
     */
    public static function recuperaUsuarioPorCredencial(PDO $bd, string $nombre, string $clave): ?Usuario {
        $bd->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
        $sql = 'select * from usuarios where nombre=:nombre and clave=:clave';
        $sth = $bd->prepare($sql);
        $sth->execute([":nombre" => $nombre, ":clave" => $clave]);
        $sth->setFetchMode(PDO::FETCH_CLASS, Usuario::class);
        $usuario = ($sth->fetch()) ?: null;
        return $usuario;
    }

    //agregamos el usuario a la base de datos
    protected function agregarUsuario($usuario,$nombre,$apellido,$contrasena,$telefono,$email){
        $query="insert into usuarios (usuario, contraseña, nombre, apellidos, correo, telefono) values(?, ?, ?, ?, ?, ?)";
        $stmt = $this->getConexion()->prepare($query);
        //$stmt = $this->connect()->prepare($query);
        

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
        $stmt = $this->getConexion()->prepare($query);
        //$stmt = $this->connect()->prepare($query);
        

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
