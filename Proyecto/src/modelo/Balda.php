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
    private $alias;
    private $idTrastero;
 
    //Transformamos el alias a string antes de instanciar una estantería.
    public function __construct(int $id = null, string $alias = null, int $idTrastero = null) {
        if (!is_null($id)) {
            $this->id = $id;
        }
        if (!is_null($alias)) {
            $this->alias = $alias;
        }
        if (!is_null($idTrastero)) {
            $this->idTrastero = $idTrastero;
        }
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getAlias() {
        return $this->alias;
    }

    public function getidTrastero() {
        return $this->idTrastero;
    }
    
    public function setId($id): void {
        $this->id = $id;
    }
    
    public function setAlias($idUsuario): void {
        $this->alias = $alias;
    }

    public function setIdTrastero($idTrastero): void {
        $this->idTrastero = idTrastero;
    }
    
}