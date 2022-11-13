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

}