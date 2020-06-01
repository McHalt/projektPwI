<?php

class Page_Sql_Model extends Page_Model
{
    public function executeQuery($query)
    {
        $Config = new Config();
        $dbConfig = $Config->getDbConfig();
        $db = $this->db->query('SELECT name FROM dbs WHERE id = :id', [':id'], [$_GET['args'][0]], [PDO::PARAM_INT]);
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=' . $db[0]['name'], $dbConfig['username'], $dbConfig['password']);
        } catch (PDOException $e) {
            var_dump($e->getMessage());
            return;
        }
        $sth = $pdo->prepare($query);
        $sth->execute();
        return $sth->errorInfo();
    }
}