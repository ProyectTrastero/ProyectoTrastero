<?php
namespace App;

// PDO se usa para interaccionar con la base de datos relacional
use \PDO as PDO;
/**
 * Description of Trasteros
 *
 * @author Emma
 */

class Balda {
    private $id;
    private $nombre;
    private $idEstanteria;
 
    //Transformamos el alias a string antes de instanciar una estantería.
    public function __construct(int $id = null, string $numero = null, string $nombre = null, string $idEstanteria = null) {
        if (!is_null($id)) {
            $this->id = $id;
        }
        if (!is_null($numero)) {
            $this->numero = $numero;
        }
        if (!is_null($nombre)) {
            $this->nombre = $nombre;
        }
        if (!is_null($idEstanteria)) {
            $this->idEstanteria = $idEstanteria;
        }
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getNumero() {
        return $this->numero;
    }
    
    public function getNombre() {
        return $this->nombre;
    }

    public function getIdEstanteria() {
        return $this->idEstanteria;
    }
 
    public function setId($id): void {
        $this->id = $id;
    }
    
    public function setNumero($numero): void {
        $this->numero = $numero;
    }
    
    public function setNombre($nombre): void {
        $this->nombre = $nombre;
    }

    public function setIdEstanteria($idEstanteria): void {
        $this->idEstanteria = $idEstanteria;
    }
    
    public function añadir($bd){
        $consulta="insert into Baldas (nombre, idEstanteria) values('$this->nombre', $this->idEstanteria)";
        $bd->exec($consulta);
    }
    
    public function actualizarNombre($bd, $nuevoNombre){
       $consulta="update Baldas set nombre = '$nuevoNombre' where id = $this->id";
       $bd->exec($consulta);
   }
   
   public static function obtenerIdPorNombre($bd, $nombre, $idEstanteria){
        $consulta=$bd->query("select id from baldas where nombre='$nombre' and idEstanteria=$idEstanteria");
        $registro=$consulta->fetch(PDO::FETCH_OBJ);
        $idRecuperado=$registro->id;
        
        return $idRecuperado;
   }
   
   public function eliminar($bd): void{
       $consulta ="delete from Baldas where id = $this->id";
       $bd->exec($consulta);
       
   }
   
   public static function obtenerIdEstanteria($bd, $idBalda): int{
       $consulta= "select idEstanteria from baldas where id= $idBalda";
       $respuesta = $bd->query($consulta);
       $registro = $respuesta->fetch(PDO::FETCH_OBJ);
       $idRecuperado = $registro->idEstanteria;
       return $idRecuperado;
   }
   
      public static function recuperarBaldasPorIdEstanteria($bd, $idEstanteria): array{
        $consulta="select * from Baldas where idEstanteria = $idEstanteria order by id asc";
        $registro = $bd->query($consulta);
        $registro->setFetchMode(PDO::FETCH_CLASS, Balda::class);
        $baldas=($registro->fetchAll()) ?: null;
        if($baldas==null){
            $baldas=array();
        }
        return $baldas;
    }

    public static function getBaldaByIdEstanteria($bd, $idEstanteria): array{
        $consulta="select * from Baldas where idEstanteria = $idEstanteria order by id asc";
        $registro = $bd->query($consulta);
        $registro->setFetchMode(PDO::FETCH_ASSOC);
        $baldas=($registro->fetchAll()) ?: null;
        if($baldas==null){
            $baldas=array();
        }
        return $baldas;
    }

}