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
    //private $fecha;
    private $nombre;
    private $descripcion;
    private $fechaIngreso;
    private $idTrastero;
    private $idEstanteria;
    private $idBalda;
    private $idCaja;
    
    public function __construct(int $id = null, string $nombre = null, string $descripcion = null, string $fechaIngreso = null, int $idTrastero=null, int $idEstanteria=null, int $idBalda= null, int $idCaja=null) {
        if (!is_null($id)) {
        $this->id = $id;
        }
        /*if (!is_null($fecha)) {
        $this->nombre = $fecha;
        }*/
        if (!is_null($nombre)) {
        $this->nombre = $nombre;
        }
        if (!is_null($descripcion)) {
        $this->descripcion = $descripcion;
        }
        if (!is_null($fechaIngreso)) {
        $this->fechaIngreso = $fechaIngreso;
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

    /*public function getFecha() {
        return $this->fecha;
    }
    public function setFecha($fecha): void {
        $this->fecha = $fecha;
    }*/
    
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
    
    public function getFechaIngreso() {
        return $this->fechaIngreso;
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
    
    public function setFechaIngreso($fechaIngreso): void {
        $this->fechaIngreso = $fechaIngreso;
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

    public static function recuperarProductoPorId (PDO $bd, int $idProducto){
        $query =    "select * from productos where id = :idProducto";
        $stmt = $bd->prepare($query);
        if (!$stmt->execute([':idProducto'=>$idProducto])) {
            //si falla el insert
            $stmt = null;
            return false;
        }else{
            $stmt->setFetchMode(PDO::FETCH_CLASS, Producto::class);
            $producto = $stmt->fetch();
            $stmt=null;
            return $producto;
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
        $consulta="update productos set idEstanteria=NULL, idBalda=NULL, idCaja=NULL where id=$this->id";
        $bd->exec($consulta);
    }
  
    public function añadirProducto (PDO $bd, ):int{
        $query =    "insert into productos (nombre, descripcion, fechaIngreso,idTrastero,idEstanteria,idBalda,idCaja) 
                    values (:nombre, :descripcion,:fechaIngreso, :idTrastero, :idEstanteria, :idBalda, :idCaja)";
        $stmt = $bd->prepare($query);
        if (!$stmt->execute([   ':nombre'=>$this->getNombre(), 
                                ':descripcion'=>$this->getDescripcion(),
                                ':fechaIngreso'=>$this->getFechaIngreso(),
                                ':idTrastero'=>$this->getIdTrastero(), 
                                ':idEstanteria'=>$this->getIdEstanteria(),
                                ':idBalda'=>$this->getIdBalda(),
                                ':idCaja'=>$this->getIdCaja()])) {
            //si falla el insert
            $stmt = null;
            return -1;
        }else{
            //si todo bien
            //recuperamos el id del producto añadido
            $id=intval($bd->lastInsertId());
            $stmt=null;
            return $id;
        }
    }

    public function modificarProducto (PDO $bd){
        $sql = "update productos set nombre = :nombre, descripcion = :descripcion, idTrastero = :idTrastero, 
                idEstanteria = :idEstanteria, idBalda = :idBalda, idCaja = :idCaja
                where id = :id";
        $stmt = $bd->prepare($sql);
        if (!$stmt->execute([   ':nombre'=>$this->getNombre(), 
                                ':descripcion'=> $this->getDescripcion(),   
                                ':idTrastero'=>$this->getIdTrastero(), 
                                ':idEstanteria'=>$this->getIdEstanteria(),
                                ':idBalda'=>$this->getIdBalda(),
                                ':idCaja' => $this->getIdCaja(),
                                ':id'=>$this->getId()])){
            //si falla el update
            $stmt = null;
            return false;                            
        }else{
            $stmt=null;
            return true;
        }

    }
    
    public function eliminar($bd){
        $consulta="delete from productos where id=$this->id";
        $bd->exec($consulta);
        $consulta2="delete from etiquetasproductos where idProducto=$this->id";
        $bd->exec($consulta2);
        
    }

    public static function añadirEtiquetaProducto(PDO $bd, int $idEtiqueta, int $idProducto ):bool{
        try {
         $query = "insert into etiquetasproductos (idEtiqueta, idProducto) values (:idEtiqueta, :idProducto)";
         $stmt =$bd->prepare($query);
         $stmt->execute([':idEtiqueta'=>$idEtiqueta, ':idProducto'=>$idProducto]);
         $stmt = null;
         return true;
         
         } catch (\PDOException $e ) {
         $stmt = null;
         return false;
        }
     }

    public static function eliminarEtiquetaProducto(PDO $bd, int $idEtiquetaProducto):bool{
        $sql='delete from etiquetasproductos where id = :id';
        $stmt = $bd->prepare($sql);
        if(!$stmt->execute([':id'=>$idEtiquetaProducto])){
            $stmt = null;
            return false;
        }else{
            $stmt = null;
            return true;
        }
    }

    public static function recuperarEtiquetasPorProductoId(PDO $bd, int $idProducto):array{
        $sql = "select ep.id, ep.idProducto, ep.idEtiqueta,  e.nombre as 'nombreEtiqueta' from etiquetasproductos ep \n"
                . "inner join etiquetas e on ep.idEtiqueta = e.id \n"
                . "where idProducto = :idProducto;";
        $stmt = $bd->prepare($sql);
        $stmt->execute([':idProducto'=>$idProducto]);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $etiquetasProducto=($stmt->fetchAll()) ?: null;
        if($etiquetasProducto==null){
            $etiquetasProducto=array();
        }
        return $etiquetasProducto;
        
    }

    public static function eliminarProductoporID($bd, int $idProducto){
            $consulta="delete from productos where id=$idProducto";
            $bd->exec($consulta);
            $consulta2="delete from etiquetasproductos where idProducto=$idProducto";
            $bd->exec($consulta2);
    }
 
    public static function buscarProductosPorIdEtiquetaYTrastero($bd, array $idEtiquetas, $idTrastero){
        $producto = array();
        $bd->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
        foreach ($idEtiquetas as $valor) {
        $consulta = "select P.id, P.nombre, descripcion, fechaIngreso, idTrastero, idEstanteria, idBalda, idCaja  from productos P join etiquetasproductos D on (D.idProducto = P.id) and (D.idEtiqueta = :idEtiqueta) and (P.idTrastero = :idTrastero)";
        $registro = $bd->prepare($consulta);
        $registro->execute([":idEtiqueta" => $valor, ":idTrastero" => $idTrastero]);
        $registro->setFetchMode(PDO::FETCH_CLASS, Producto::class);
        while ($producto = ($registro->fetch()) ?: null){
                $productos[]=$producto;
            }
        }
        
      
       
            
            if (isset($productos)){
            return $productos;
            }else{
            return "";    
            }
    }
    
     public static function recuperaProductosPorPalabraYTrastero(PDO $bd, string $palabra, $idTrastero){
        $bd->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
        $sql = 'select * from productos where (nombre LIKE :nombre) and (idTrastero = :idTrastero)';
        $sth = $bd->prepare($sql);
        $palabra = "%$palabra%";
        $sth->execute([":nombre" => $palabra, ":idTrastero" => $idTrastero]);
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
    
    public static function comprobarProductosPorIdEtiqueta($bd, array $idEtiquetas, $idsProductos){
        $bd->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
        foreach ($idEtiquetas as $valor) {
        $consulta = "select * from productos P join etiquetasproductos D on D.idProducto = P.id and D.idEtiqueta = :idEtiqueta and P.id = :idProducto";
        $registro = $bd->prepare($consulta);
        $registro->execute([":idEtiqueta" => $valor, "idProducto" => $idsProductos]);
        }
        $registro->setFetchMode(PDO::FETCH_CLASS, Producto::class);
      
        $producto = array();
            while ($producto = ($registro->fetch()) ?: null){
                $productos[]=$producto;
            }
            if (isset($productos)){
            return $productos;
            }else{
            return "";    
            }
    }
    
    public function obtenerNombreEstanteria($bd){
        if($this->idEstanteria==null){
            $nombre="Sin asignar";
            
        }else{
            $consulta="select nombre from estanterias where id = $this->idEstanteria";
            $registro= $bd->query($consulta);
            $reg= $registro->fetch(PDO::FETCH_OBJ);
            $nombre=$reg->nombre;
        }
        return $nombre;
    }
    
    public function obtenerNombreBalda($bd){
        if($this->idBalda==null){
            $nombre="Sin asignar";
            
        }else{
            $consulta="select nombre from baldas where id = $this->idBalda";
            $registro= $bd->query($consulta);
            $reg= $registro->fetch(PDO::FETCH_OBJ);
            $nombre=$reg->nombre;
        }
        return $nombre;
    }
    
    public function obtenerNombreCaja($bd){
        if($this->idCaja==null){
            $nombre="Sin asignar";
            
        }else{
            $consulta="select nombre from cajas where id = $this->idCaja";
            $registro= $bd->query($consulta);
            $reg= $registro->fetch(PDO::FETCH_OBJ);
            $nombre=$reg->nombre;
        }
        
        return $nombre;
    }
    
    public static function obtenerFechaIngreso(){
        $fecha=getDate();
        $formatoFecha=$fecha['year']."-".$fecha['mon']."-".$fecha['mday'];
        return $formatoFecha;
}
}

