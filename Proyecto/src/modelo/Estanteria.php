<?php
namespace App;

// PDO se usa para interaccionar con la base de datos relacional
use \PDO as PDO;
/**
 * Description of Trasteros
 *
 * @author Emma
 */
class Estanteria {
    private $id;
    private $nombre;
    private $idTrastero;
 
    //Transformamos el alias a string antes de instanciar una estantería.
    public function __construct(int $id = null, string $nombre = null, int $idTrastero = null) {
        if (!is_null($id)) {
            $this->id = $id;
        }
        if (!is_null($nombre)) {
            $this->nombre = $nombre;
        }
        if (!is_null($idTrastero)) {
            $this->idTrastero = $idTrastero;
        }
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getNombre() {
        return $this->nombre;
    }

    public function getidTrastero() {
        return $this->idTrastero;
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
    
    public function añadir($bd): void{
        $consulta="insert into Estanterias (nombre, idTrastero) values('$this->nombre', $this->idTrastero)";
        $bd->exec($consulta);
            
   }
   
   public static function obtenerIdPorNombre($bd, $nombre, $idTrastero){
        $consulta=$bd->query("select id from estanterias where nombre='$nombre' and idTrastero=$idTrastero");
        $registro=$consulta->fetch(PDO::FETCH_OBJ);
        $idRecuperado=$registro->id;
        
        return $idRecuperado;
   }
   
   public function actualizarNombre($bd, $nuevoNombre): void{
       $consulta="update Estanterias set nombre = '$nuevoNombre' where id = $this->id";
       $bd->exec($consulta);
   }
   
   public function eliminar($bd): void{
       $consulta ="delete from Estanterias where id = $this->id";
       $bd->exec($consulta);
   }
   
   public static function recuperarEstanteriasPorIdTrastero($bd, $idTrastero): array{
        $consulta="select * from Estanterias where idTrastero = $idTrastero order by id asc";
        $registro = $bd->query($consulta);
        $registro->setFetchMode(PDO::FETCH_CLASS, Estanteria::class);
        $estanterias=($registro->fetchAll()) ?: null;
         if($estanterias==null){
            $estanterias=array();
        }
        return $estanterias;
    }
 
}