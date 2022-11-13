<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App;

// PDO se usa para interaccionar con la base de datos relacional
use \PDO as PDO;


/**
 * Description of Trasteros
 *
 * @author Nausicaa
 */
class Trasteros {
    private $id;
    private $idUsuario;
    private $nombre;
    private $numEstanterias;
    private $numCajas;

    public function __construct(int $id = null, int $idUsuario = null, string $nombre = null, int $numEstanterias = null, int $numCajas = null) {
        if (!is_null($id)) {
            $this->id = $id;
        }
        if (!is_null($idUsuario)) {
            $this->idUsuario = $idUsuario;
        }
        if (!is_null($nombre)) {
            $this->nombre = $nombre;
        }
        if (!is_null($numEstanterias)) {
            $this->numEstanterias = $numEstanterias;
        }
        if (!is_null($numCajas)) {
            $this->numCajas = $numCajas;
        }
    }

    public function getId() {
        return $this->id;
    }
    
    public function getIdUsuario() {
        return $this->idUsuario;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getNumEstanterias() {
        return $this->numEstanterias;
    }

    public function getNumCajas() {
        return $this->numCajas;
    }

    public function setId($id): void {
        $this->id = $id;
    }
    
    public function setIdUsuario($idUsuario): void {
        $this->idUsuario = $idUsuario;
    }

    public function setNombre($nombre): void {
        $this->nombre = $nombre;
    }

    public function setNumEstanterias($numEstanterias): void {
        $this->numEstanterias = $numEstanterias;
    }

    public function setNumCajas($numCajas): void {
        $this->numCajas = $numCajas;
    }

    
    public static function recuperaTrasteroPorUsuario(PDO $bd, int $idUsuario){
        $bd->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
        $sql = 'select * from trasteros where idUsuario=:idUsuario';
        $sth = $bd->prepare($sql);
        $sth->execute([":idUsuario" => $idUsuario]);
        $sth->setFetchMode(PDO::FETCH_CLASS, Trasteros::class);
        $trastero = array();
            while ($trastero = ($sth->fetch()) ?: null){
                $trasteros[]=$trastero;
            }
            if (isset($trasteros)){
            return $trasteros;
            }else{
            return "";    
            }
    }
    
    /*$sql = "select id, nombre from productos order by nombre";
        $sth = $conProyecto->prepare($sql);
        try {
            $sth->execute();
        } catch (PDOException $ex) {
            die("Error al recuperar los productos " . $ex->getMessage());
        }

        $productos = array();
        while ($producto = $sth->fetch(PDO::FETCH_OBJ)) {
            $productos[] = $producto;
        }
        $sth = null;
        $conProyecto = null;*/
    
    
}    
?>