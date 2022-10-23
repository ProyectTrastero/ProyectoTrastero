<?php

class Dbh {

    private $host;
    private $user;
    private $pwd;
    private $dbname;

    public function __construct()
    {
        $this->host=constant("HOST");
        $this->dbname=constant("DBNAME");
        $this->user=constant("USER");
        $this->pwd=constant("PASSWORD");
        
    }

    protected  function connect(){
        try{

            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->dbname;
            $pdo = new PDO($dsn, $this->user , $this->pwd );
            return $pdo;
            
        }catch(PDOException $e){

            print($e->getMessage());
        }
    }
}