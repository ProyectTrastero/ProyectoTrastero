<?php
namespace App;

// PDO se usa para interaccionar con la base de datos relacional
use \PDO as PDO;
/**
 * Description of Trasteros
 *
 * @author Emma
 */

class Caja implements \JsonSerializable {
    private $id;
    private $nombre;
    private $idTrastero;
    private $idEstanteria;
    private $idBalda;
 
    //Transformamos el alias a string antes de instanciar una estantería.
    public function __construct(string $id = null, string $nombre = null, string $idTrastero = null, string $idEstanteria = null, string $idBalda = null) {
        if (!is_null($id)) {
            $this->id = $id;
        }
        if (!is_null($nombre)) {
            $this->nombre = $nombre;
        }
        if (!is_null($idTrastero)) {
            $this->idTrastero = $idTrastero;
        }
        if (!is_null($idEstanteria)) {
            $this->idEstanteria = $idEstanteria;
        }
        if (!is_null($idBalda)) {
            $this->idBalda = $idBalda;
        }
    }

    public function jsonSerialize(){
        $variables = get_object_vars($this);
        return $variables;
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getNombre() {
        return $this->nombre;
    }

    public function getIdTrastero() {
        return $this->idTrastero;
    }
    public function getIdEstanteria() {
        return $this->idEstanteria;
    }
    public function getIdBalda() {
        return $this->idBalda;
    }
    
    public function setId($id): void {
        $this->id = $id;
    }
    
    public function setNombre($nombre): void {
        $this->nombre = $nombre;
    }

    public function setIdTrastero($idTrastero): void {
        $this->idTrastero = $idTrastero;
    }
    public function setIdEstanteria($idEstanteria): void {
        $this->idEstanteria = $idEstanteria;
    }
    public function setIdBalda($idBalda): void {
        $this->idBalda = $idBalda;
    }
    
    public function actualizarNombre($bd, $nuevoNombre){
       $consulta="update Cajas set nombre = '$nuevoNombre' where id = $this->id";
       $bd->exec($consulta);
   }
   
   public static function obtenerIdPorNombre($bd, $nombre, $idTrastero): int{
        $consulta=$bd->query("select id from cajas where nombre='$nombre' and idTrastero=$idTrastero");
        $registro=$consulta->fetch(PDO::FETCH_OBJ);
        $idRecuperado=$registro->id;
        return $idRecuperado;
   }
   
   public function añadir($bd){
       if($this->idEstanteria==null){
           $consulta="insert into Cajas (nombre, idTrastero) values('$this->nombre',$this->idTrastero)";
           $bd->exec($consulta);
       }else{
           $consulta="insert into Cajas (nombre, idTrastero, idEstanteria, idBalda) values('$this->nombre',$this->idTrastero, $this->idEstanteria, $this->idBalda)";
           $bd->exec($consulta); 
       }
        
    }
    
    public function eliminar($bd): void{
       $consulta = "delete from Cajas where id = $this->id";
       $bd->exec($consulta);
   }
   
    public static function recuperarCajasPorIdTrastero($bd, $idTrastero): array{
        $consulta="select * from Cajas where idTrastero = '$idTrastero' order by id";
        $registro = $bd->query($consulta);
        $registro->setFetchMode(PDO::FETCH_CLASS, Caja::class);
        $cajas=($registro->fetchAll()) ?: null;
         if($cajas==null){
            $cajas=array();
        }
        return $cajas;
    }

    public static function recuperarCajaPorIdBalda($bd, $idBalda): array{
        $consulta="select * from Cajas where idBalda = '$idBalda' order by id";
        $registro = $bd->query($consulta);
        $registro->setFetchMode(PDO::FETCH_CLASS, Caja::class);
        $cajas=($registro->fetchAll()) ?: null;
         if($cajas==null){
            $cajas=array();
        }
        return $cajas;
    }
    
}