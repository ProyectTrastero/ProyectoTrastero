<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App;

use \PDO as PDO;
/**
 * Description of Etiqueta
 *
 * @author Nausicaa
 */
class Etiqueta {
    private $id;
    private $nombre;
    private $usuario;
    private  $producto;
    
    public function __construct($id, $nombre, $usuario, $producto) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->usuario = $usuario;
        $this->producto = $producto;
    }
    
    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getUsuario() {
        return $this->usuario;
    }

    public function getProducto() {
        return $this->producto;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    public function setNombre($nombre): void {
        $this->nombre = $nombre;
    }

    public function setUsuario($usuario): void {
        $this->usuario = $usuario;
    }

    public function setProducto($producto): void {
        $this->producto = $producto;
    }


    public static function recuperaEtiquetasPorUsuario(PDO $bd, int $idUsuario){
        $bd->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
        $sql = 'select * from etiquetas where id=:id';
        $sth = $bd->prepare($sql);
        $sth->execute([":id" => $idUsuario]);
        $sth->setFetchMode(PDO::FETCH_CLASS, Etiqueta::class);
        $etiqueta = array();
            while ($etiqueta = ($sth->fetch()) ?: null){
                $etiquetas[]=$etiqueta;
            }
            if (isset($etiquetas)){
            return $etiquetas;
            }else{
            return "";    
            }
    }

}