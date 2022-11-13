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
    private $nombre;
    private $idTrastero;
    private $idEstanteria;
    private $idBalda;
 
    //Transformamos el alias a string antes de instanciar una estantería.
    public function __construct(int $id = null, string $nombre = null, int $idTrastero = null, int $idEstanteria = null, int $idBalda = null) {
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
    
}