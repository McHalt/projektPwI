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

    private function prepare(string $sql, array $parameters = [], array $values = [], array $dataTypes = []){
        if(!(count($parameters) == count($values) && count($values) == count($dataTypes))){
            return false;
        }
        $sth = $this->pdo->prepare($sql);
        for($i = 0; $i < count($parameters); $i++)
        {
            $sth->bindValue($parameters[$i], $values[$i], $dataTypes[$i]);
        }
        return $sth;
    }

    public function query(string $sql, array $parameters = [], array $values = [], array $dataTypes = [])
    {
        $sth = $this->prepare($sql, $parameters, $values, $dataTypes);
        if(!$sth) return false;
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function execute(string $sql, array $parameters = [], array $values = [], array $dataTypes = [])
    {
        $sth = $this->prepare($sql, $parameters, $values, $dataTypes);
        if(!$sth) return false;
        return $sth->execute();
    }
}