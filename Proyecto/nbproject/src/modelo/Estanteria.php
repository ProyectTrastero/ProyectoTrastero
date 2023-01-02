<?php
namespace App;

// PDO se usa para interaccionar con la base de datos relacional
use \PDO as PDO;
/**
 * Description of Trasteros
 *
 * @author Emma
 */
class Estanteria implements \JsonSerializable {
    private $id;
    private $nombre;
    private $numero;
    private $idTrastero;
 
    //Transformamos el alias a string antes de instanciar una estantería.
    public function __construct(int $id = null, string $nombre = null, int $numero=null, int $idTrastero = null) {
        if (!is_null($id)) {
            $this->id = $id;
        }
        if (!is_null($nombre)) {
            $this->nombre = $nombre;
        }
        if (!is_null($numero)) {
            $this->numero = $numero;
        }
        if (!is_null($idTrastero)) {
            $this->idTrastero = $idTrastero;
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
     public function getNumero() {
        return $this->numero;
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
     public function setNumero($numero): void {
        $this->numero = $numero;
    }

    public function setIdTrastero($idTrastero): void {
        $this->idTrastero = $idTrastero;
    }
    
    public function añadir($bd): void{
        $consulta="insert into Estanterias (nombre, numero, idTrastero) values('$this->nombre', $this->numero, $this->idTrastero)";
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
        $consulta="select * from Estanterias where idTrastero = $idTrastero order by numero asc";
        $registro = $bd->query($consulta);
        $registro->setFetchMode(PDO::FETCH_CLASS, Estanteria::class);
        $estanterias=($registro->fetchAll()) ?: null;
         if($estanterias==null){
            $estanterias=array();
        }
        return $estanterias;
    }
    
    public static function obtenerNombrePorId($bd, $id){
        $consulta=$bd->query("select nombre from estanterias where id=$id");
        $registro=$consulta->fetch(PDO::FETCH_OBJ);
        $idRecuperado=$registro->id;
        
        return $idRecuperado;
   }
   
      public static function asignarNumero($bd, $idTrastero): int{
       //Asignamos a la variable $numero el primer numero disponible que no exista.
        $numero=1;
        $consulta=$bd->query("select numero from estanterias where idTrastero = $idTrastero order by id");
        while($registro=$consulta->fetch(PDO::FETCH_OBJ)){
            if($numero==$registro->numero){
                $numero++;
            }
        }
     return $numero;  
   }
 
}