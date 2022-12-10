<?php

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
    private $idUsuario;
    
    public function __construct(int $id = null, string $nombre=null, int $idUsuario= null) {
        if (!is_null($id)) {
            $this->id = $id;
        }
        if (!is_null($nombre)) {
            $this->nombre = $nombre;
        }
        if (!is_null($idUsuario)) {
        $this->idUsuario = $idUsuario;
        }
    }
    
    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getIdUsuario() {
        return $this->idUsuario;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    public function setNombre($nombre): void {
        $this->nombre = $nombre;
    }

    public function setIdUsuario($idUsuario): void {
        $this->idUsuario = $idUsuario;
    }

    public static function recuperarEstanteriasPorIdTrastero($bd, $idTrastero): array{
        $consulta="select * from Estanterias where idTrastero = $idTrastero order by numero asc";
        $registro = $bd->query($consulta);
        $registro->setFetchMode(PDO::FETCH_CLASS, Estanteria::class);
        $estanterias=($registro->fetchAll()) ?: null;
         if($estanterias==null){
            $estanterias=array();
        }
        return $estanterias;
    }

    
    public static function recuperaEtiquetasPorUsuario(PDO $bd, int $idUsuario){
    $bd->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
    $sql = 'select * from etiquetas where idUsuario=:id';
    $sth = $bd->prepare($sql);
    $sth->execute([":id" => $idUsuario]);
    $sth->setFetchMode(PDO::FETCH_CLASS, Etiqueta::class);
    $etiqueta=($sth->fetchAll()) ?: null;
         if($etiqueta==null){
            $etiqueta=array();
        }
        return $etiqueta;
    }

}