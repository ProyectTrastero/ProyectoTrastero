<?php

namespace App;

use \PDO as PDO;

class BD {

    protected static $bd = null;

    private function __construct() {
        self::$bd = new \PDO("mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_DATABASE'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
        self::$bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function getConexion() {
        if (!self::$bd) {
            new BD();
        }
        return self::$bd;
    }

}
