<?php
require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();

//definimos las constantes de la aplicacion

define('HOST', $_ENV['HOST']);
define('DBNAME', $_ENV['DBNAME']);
define('USER', $_ENV['USER']);
define('PASSWORD', $_ENV['PASSWORD']);
define('CHARSET', $_ENV['CHARSET']);
