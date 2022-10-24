<?php
namespace App;

// PDO se usa para interaccionar con la base de datos relacional
use \PDO as PDO;

/**
 * Usuario representa al usuario que está usando la aplicación
 */
class Usuario extends BD {
    private $id;
    private $alias;
    private $nombre;
    private $apellidos;
    private $email;
    private $clave;
   
    public function __construct(int $id=null,string $alias=null,string $nombre=null,string $apellidos=null,string $email=null, string $clave=null) {
        if (!is_null($id)) {
            $this->id = $id;
        }
        if (!is_null($alias)) {
            $this->alias = $alias;
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

    
    public function getId() {
        return $this->id;
    }

    public function getAlias() {
        return $this->alias;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getApellidos() {
        return $this->apellidos;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getClave() {
        return $this->clave;
    }

    
    public function setId($id): void {
        $this->id = $id;
    }

    public function setAlias($alias): void {
        $this->alias = $alias;
    }

    public function setNombre($nombre): void {
        $this->nombre = $nombre;
    }

    public function setApellidos($apellidos): void {
        $this->apellidos = $apellidos;
    }

    public function setEmail($email): void {
        $this->email = $email;
    }

    public function setClave($clave): void {
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
    
    /**
     * Para modificar el Usuario
     */
    public function persiste(PDO $bd): bool {
        $sql = "update usuarios set alias = :alias, nombre = :nombre, apellidos = apellidos, clave = :clave, correo = :correo where id = :id";
        $sth = $bd->prepare($sql);
        $result = $sth->execute([":alias" => $this->alias, ":nombre" => $this->nombre, ":apellidos" => $this->apellidos, ":clave" => $this->clave, ":correo" => $this->correo, ":id" => $this->id]);
        return ($result);
    }

    
    
    /*
     * Funcion para agregar un usuario
     */
    public function agregarUsuario( string $alias, string $clave, string $nombre, string $apellidos, string $correo){
        //$bd->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
        $query="insert into usuarios (alias, nombre, clave, apellidos, correo) values(:alias, :nombre, :clave, :apellidos, :correo)";
        //$stmt = $bd->prepare($query);
        $stmt=$this->getConexion()->prepare($query);
        //encriptamos la contraseña
        $hasshedContraseña = password_hash($clave, PASSWORD_DEFAULT);
        //si falla el insert
        if(!$stmt->execute([":alias" => $this->alias, ":nombre" => $this->nombre, ":apellidos" => $this->apellidos, ":clave" => $hasshedContraseña, ":correo" => $this->email])){
            $stmt=null;
            return false;
        }else{
        //si todo bien
           $stmt=null;
           return true;
        }
       
        
        //encriptamos la contraseña
        /*$hasshedContrasena = password_hash($clave, PASSWORD_DEFAULT);

        if(!$stmt->execute(array($alias,$hasshedContrasena,$nombre,$apellidos,$email))){
            $stmt=null;
            return false;
        }else{
            //si todo bien simplemente cerramos la conexion
            $stmt = null;
            return true;
        }*/
    }
    //comprobamos si el usuario o el email ya existe 
    protected function checkUsuario ($alias, $correo){
        $query = "select alias from usuarios where alias = :alias or correo = :correo ;";
        $stmt = $this->getConexion()->prepare($query);
        //$stmt = $this->connect()->prepare($query);
        

        //si falla nos redirige a la pagina de registro nuevamente
        if(!$stmt->execute([":alias"=>$alias, ":correo"=>$correo])){
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
