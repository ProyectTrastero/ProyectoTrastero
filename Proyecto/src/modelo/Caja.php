<?php
namespace App;

// PDO se usa para interaccionar con la base de datos relacional
use \PDO as PDO;
/**
 * Description of Trasteros
 *
 * @author Emma
 */

class Caja {
    private $id;
    private $numero;
    private $idTrastero;
    private $idEstanteria;
    private $idBalda;
 
    //Transformamos el alias a string antes de instanciar una estantería.
    public function __construct(int $id = null, int $numero = null, int $idTrastero = null, int $idEstanteria = null, int $idBalda = null) {
        if (!is_null($id)) {
            $this->id = $id;
        }
        if (!is_null($numero)) {
            $this->numero = $numero;
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
    
    public function getId() {
        return $this->id;
    }
    
    public function getNumero() {
        return $this->numeero;
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
    
    public function setNumero($numero): void {
        $this->numero = $numero;
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
    
}