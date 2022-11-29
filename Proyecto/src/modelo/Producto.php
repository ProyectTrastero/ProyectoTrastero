<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App;

// PDO se usa para interaccionar con la base de datos relacional
use \PDO as PDO;


/**
 * Description of Productos
 *
 * @author Nausicaa
 */
class Producto {
    private $id;
    private $idTrastero;
    private $nombre;
    private $descripcion;
    private $idEstanteria;
    private $idBalda;
    private $idCaja;
    
    public function __construct($id = null, $trastero= null, $nombre = null, $descripcion = null, $estanteria = null, $balda =null, $caja = null) {
        $this->id = $id;
        $this->idTrastero = $trastero;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->idEstanteria = $estanteria;
        $this->idBalda = $balda;
        $this->idCaja = $caja;
    }
    
    public function getId() {
        return $this->id;
    }

    public function getTrastero() {
        return $this->trastero;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function getEstanteria() {
        return $this->estanteria;
    }

    public function getBalda() {
        return $this->balda;
    }

    public function getCaja() {
        return $this->caja;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    public function setTrastero($trastero): void {
        $this->trastero = $trastero;
    }

    public function setNombre($nombre): void {
        $this->nombre = $nombre;
    }

    public function setDescripcion($descripcion): void {
        $this->descripcion = $descripcion;
    }

    public function setEstanteria($estanteria): void {
        $this->estanteria = $estanteria;
    }

    public function setBalda($balda): void {
        $this->balda = $balda;
    }

    public function setCaja($caja): void {
        $this->caja = $caja;
    }


     public static function recuperaProductosPorPalabra(PDO $bd, string $palabra){
        $bd->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
        $sql = 'select * from productos where nombre LIKE :nombre';
        $sth = $bd->prepare($sql);
        $sth->execute([":nombre" => $palabra]);
        $sth->setFetchMode(PDO::FETCH_CLASS, Producto::class);
        $producto = array();
            while ($producto = ($sth->fetch()) ?: null){
                $productos[]=$producto;
            }
            if (isset($productos)){
            return $productos;
            }else{
            return "";    
            }
    }
    
    public static function recuperarProductosPorIdTrastero($bd, $idTrastero): array{
        $consulta="select * from Productos where idTrastero = $idTrastero order by id asc";
        $registro = $bd->query($consulta);
        $registro->setFetchMode(PDO::FETCH_CLASS, Producto::class);
        $productos=($registro->fetchAll()) ?: null;
         if($productos==null){
            $productos=array();
        }
        return $productos;
    }

    
}

