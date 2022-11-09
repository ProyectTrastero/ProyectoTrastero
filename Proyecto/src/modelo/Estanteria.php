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
    private $numero;
    private $idTrastero;
 
    //Transformamos el alias a string antes de instanciar una estantería.
    public function __construct(int $id = null, string $numero = null, int $idTrastero = null) {
        if (!is_null($id)) {
            $this->id = $id;
        }
        if (!is_null($numero)) {
            $this->numero = $numero;
        }
        if (!is_null($idTrastero)) {
            $this->idTrastero = $idTrastero;
        }
    }
    
    public function getId() {
        return $this->id;
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
    
    public function setNumero($numero): void {
        $this->numero = $numero;
    }

    public function setIdTrastero($idTrastero): void {
        $this->idTrastero = $idTrastero;
    }
    
    public function añadirEstanteria($conexion): void{
        $consulta="insert into Estanterias (numero, idTrastero) values($this->numero, $this->idTrastero)";
            if($conexion->exec($consulta)==1){
                echo "Estantería añadida correctamente";
            } else {
                echo "fallo al añadir estanteria";
            }
   }
}