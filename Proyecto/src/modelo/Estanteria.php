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
        return $this->numero;
    }

    public function getidTrastero() {
        return $this->idTrastero;
    }
    
    public function setId($id): void {
        $this->id = $id;
    }
    
    public function setNombre($numero): void {
        $this->numero = $numero;
    }

    public function setIdTrastero($idTrastero): void {
        $this->idTrastero = $idTrastero;
    }
    
    public function añadirEstanteria($conexion): void{
        $consulta="insert into Estanterias (nombre, idTrastero) values($this->nombre, $this->idTrastero)";
            if($conexion->exec($consulta)==1){
                echo "Estantería añadida correctamente";
            } else {
                echo "fallo al añadir estanteria";
            }
   }
}