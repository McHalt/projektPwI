<?php

class Database
{
    private PDO $pdo;

    public function __construct()
    {
        $Config = new Config;
        $dbConfig = $Config->getDbConfig();

        try{
            $this->pdo = new PDO('mysql:host=localhost;dbname=sqladmin', $dbConfig['username'], $dbConfig['password']);
        }catch(PDOException $e){
            return;
        }

    }

    public function query($sql):PDOStatement
    {
        return $this->pdo->query($sql);
    }
}