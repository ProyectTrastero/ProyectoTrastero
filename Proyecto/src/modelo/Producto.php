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
    private $nombre;
    private $descripcion;
    private $idTrastero;
    private $idEstanteria;
    private $idBalda;
    private $idCaja;
    
    public function __construct(int $id = null, string $nombre = null, string $descripcion = null, int $idTrastero=null, int $idEstanteria=null, int $idBalda= null, int $idCaja=null) {
        if (!is_null($id)) {
        $this->id = $id;
        }
        if (!is_null($nombre)) {
        $this->nombre = $nombre;
        }
        if (!is_null($descripcion)) {
        $this->descripcion = $descripcion;
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
        if (!is_null($idCaja)) {
        $this->idCaja = $idCaja;
        }
    }

    
    public function getId() {
        return $this->id;
    }

    public function getIdTrastero() {
        return $this->idTrastero;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function getIdEstanteria() {
        return $this->idEstanteria;
    }

    public function getIdBalda() {
        return $this->idBalda;
    }

    public function getIdCaja() {
        return $this->idCaja;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    public function setIdTrastero($idTrastero): void {
        $this->idTrastero = $idTrastero;
    }

    public function setNombre($nombre): void {
        $this->nombre = $nombre;
    }

    public function setDescripcion($descripcion): void {
        $this->descripcion = $descripcion;
    }

    public function setIdEstanteria($idEstanteria): void {
        $this->idEstanteria = $idEstanteria;
    }

    public function setIdBalda($idBalda): void {
        $this->idBalda = $idBalda;
    }

    public function setIdCaja($idCaja): void {
        $this->idCaja = $idCaja;
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
    
    public static function recuperarProductosPorIdEstanteria($bd, $idEstanteria): array{
        $consulta="select * from Productos where idEstanteria = $idEstanteria";
        $registro = $bd->query($consulta);
        $registro->setFetchMode(PDO::FETCH_CLASS, Producto::class);
        $productos=($registro->fetchAll()) ?: null;
        if($productos==null){
            $productos=array();
        }
        return $productos;
    }
    
    public static function recuperarProductosPorIdBalda($bd, $idBalda): array{
        $consulta="select * from Productos where idBalda = $idBalda";
        $registro = $bd->query($consulta);
        $registro->setFetchMode(PDO::FETCH_CLASS, Producto::class);
        $productos=($registro->fetchAll()) ?: null;
        if($productos==null){
            $productos=array();
        }
        return $productos;
    }
    
    public static function recuperarProductosPorIdCaja($bd, $idCaja): array{
        $consulta="select * from Productos where idCaja = $idCaja";
        $registro = $bd->query($consulta);
        $registro->setFetchMode(PDO::FETCH_CLASS, Producto::class);
        $productos=($registro->fetchAll()) ?: null;
        if($productos==null){
            $productos=array();
        }
        return $productos;
    }
    
    public function actualizarUbicacion($bd){
        $cosulta="update productos set idEstanteria= $this->idEstanteria, set idBalda=$this->idBalda, set idCaja=$this->idCaja where id=$this->id";
         $bd->exec($consulta);
    }
  
    
}

