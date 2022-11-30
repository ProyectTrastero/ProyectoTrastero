<?php
namespace App;

// PDO se usa para interaccionar con la base de datos relacional
use \PDO as PDO;

/**
 * Usuario representa al usuario que está usando la aplicación
 */
class Usuario{
    private $id;
    private $alias;
    private $nombre;
    private $apellidos;
    private $email;
    private $clave;
    private $correo;
   
    public function __construct(int $id=null,string $alias=null,string $nombre=null,string $apellidos=null,string $correo=null, string $clave=null) {
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
        if (!is_null($correo)) {
            $this->correo = $correo;
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

    public function getClave() {
        return $this->clave;
    }
    public function getCorreo(){
        return $this->correo;
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


    public function setCorreo($correo):void{
        $this->correo=$correo;
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
    public static function recuperaUsuarioPorCredencial(PDO $bd, string $alias, string $clave): ?Usuario {
        $bd->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
        $sql = 'select * from usuarios where alias=:alias and clave=:clave';
        $sth = $bd->prepare($sql);
        $sth->execute([":alias" => $alias, ":clave" => $clave]);
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
    public static function agregarUsuario(PDO $bd, string $alias, string $clave, string $nombre, string $apellidos, string $correo):bool {
        //$bd->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
        $query="insert into usuarios (alias,clave, nombre,  apellidos, correo) values(:alias,:clave, :nombre,  :apellidos, :correo)";
        //$stmt = $bd->prepare($query);
        $stmt=$bd->prepare($query);
        //encriptamos la contraseña
        //$hasshedContraseña = password_hash($clave, PASSWORD_DEFAULT);
        //si falla el insert
        if(!$stmt->execute([":alias" => $alias,":clave" => $clave, ":nombre" => $nombre, ":apellidos" => $apellidos, ":correo" => $correo])){
            $stmt=null;
            return false;
        }else{
        //si todo bien
           $stmt=null;
           return true;
        }
    }

    /**
     * Para modificar el Usuario
     */
    public static function modificarUsuario(PDO $bd,  int $id, string $alias, string $nombre, string $apellidos, string $clave, string $correo): bool {
        $sql = "update usuarios set alias = :alias, nombre = :nombre, apellidos = :apellidos, clave = :clave, correo = :correo where id = :id";
        //$sql = "update usuarios set alias = :alias , nombre = :nombre where id = :id";
        $stmt = $bd->prepare($sql);
        if(!$stmt->execute([":alias" => $alias, ":nombre" => $nombre, ":apellidos" => $apellidos, ":clave" => $clave, ":correo" => $correo,":id" => $id])){
        //if(!$stmt->execute([":alias" => $alias, ":nombre" => $nombre, ":id" => $id])){
            $stmt=null;
            return false;
        }else{
            $stmt=null;
            return true;
        }

        
    }
    //comprobamos si el alias ya existe 
    public static function checkExisteAlias (PDO $bd, string $alias):bool {
        $query = "select alias from usuarios where alias = :alias;";
        $stmt =$bd->prepare($query);
        //si falla 
        if(!$stmt->execute([":alias"=>$alias])){
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
    //comprobamos si el correo ya existe 
    public static function checkExisteCorreo (PDO $bd, string $correo):bool {
        $query = "select alias from usuarios where correo = :correo ;";
        $stmt = $bd->prepare($query);        

        //si falla 
        if(!$stmt->execute([":correo"=>$correo])){
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

    public static function  existeCorreo(PDO $bd, $correo): bool{
        $existe=false;
        $consulta=$bd->query("select correo from Usuarios");
        while($registro=$consulta->fetch(PDO::FETCH_OBJ)){
            if($correo==$registro->correo){
                $existe=true;
            }
        }
        return $existe;
    }
    
    public static function obtenerContraseña(PDO $bd, $correo){
        $contraseña="";
        $consulta=$bd->query("select correo, clave from Usuarios");
        while($registro=$consulta->fetch(PDO::FETCH_OBJ)){
            if($correo==$registro->correo){
                $contraseña=$registro->clave;
            }
        }
        return $contraseña;
    }

    public static function obtenerAlias(PDO $bd, $correo){
        $aliasrecuperado="";
        $consulta=$bd->query("select correo, alias from Usuarios");
        while($registro=$consulta->fetch(PDO::FETCH_OBJ)){
            if($correo==$registro->correo){
                $aliasrecuperado=$registro->alias;
            }
        }
        return $aliasrecuperado;
    }

   
}
