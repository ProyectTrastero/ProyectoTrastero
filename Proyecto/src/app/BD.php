<?php

namespace App;

use \PDO as PDO;

class BD {

    protected static $bd = null;

    private function __construct() {
        self::$bd = new PDO("mysql:host=" . constant("DB_HOST") . ";dbname=" . constant("DB_DATABASE"),constant("DB_USERNAME"), constant("DB_PASSWORD"));
        self::$bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function getConexion() {
        if (!self::$bd) {
            new BD();
        }
        return self::$bd;
    }

}
