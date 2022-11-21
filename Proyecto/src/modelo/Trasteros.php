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

    public function __construct(int $id = null, int $idUsuario = null, string $nombre = null) {
        if (!is_null($id)) {
            $this->id = $id;
        }
        if (!is_null($idUsuario)) {
            $this->idUsuario = $idUsuario;
        }
        if (!is_null($nombre)) {
            $this->nombre = $nombre;
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


    public function setId($id): void {
        $this->id = $id;
    }
    
    public function setIdUsuario($idUsuario): void {
        $this->idUsuario = $idUsuario;
    }

    public function setNombre($nombre): void {
        $this->nombre = $nombre;
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
    
    public static function existeNombre($bd, $nombre, $idUsuario): bool{
        $existe = false;
        $consulta = "select * from trasteros where nombre = '$nombre' and idUsuario = $idUsuario";
        $respuesta=$bd->query($consulta);
        while($registro=$respuesta->fetch(PDO::FETCH_OBJ)){
            if($nombre==$registro->nombre){
                $existe = true;
            }
        }
        return $existe;
    }
    
    public function guardarTrastero($bd): void{
        $consulta="insert into Trasteros (nombre, idUsuario) values('$this->nombre', $this->idUsuario)";
        $bd->exec($consulta);
    }
    
    public function recuperarTrasteroPorNombre($bd, $nombreTrastero): Trasteros{
        $consulta = "select * from Trasteros where nombre='$nombreTrastero'";
        $registro=$bd->query($consulta);
        $registro->setFetchMode(PDO::FETCH_CLASS, Trasteros::class);
        $trastero = ($registro->fetch()) ?: null;
        return $trastero;
    }
    
    public function eliminar($bd): void{
       $consulta ="delete from Trasteros where id = $this->id";
       $bd->exec($consulta);
       
   }
   
   public function actualizarNombre($bd, $nuevoNombre){
        $consulta="update Trasteros set nombre = '$nuevoNombre' where id = $this->id";
       $bd->exec($consulta);
   }
    
    
}    
?>