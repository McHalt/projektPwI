<?php

class Page_Databases_Model extends Page_Model
{
    private array $dbList;

    public function getDbList():array
    {
        if(!empty($this->dbList)){
            return $this->dbList;
        }
        $result = $this->db->query("SELECT DISTINCT * FROM dbs"); //DO POPRAWY!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        $resArr = [];
        foreach ($result as $db) {
            $resArr[]['id'] = $db['id'];
            $resArr[count($resArr)-1]['name'] = $db['name'];
        }
        $this->dbList = $resArr;
        return $this->dbList;
    }

    public function deleteDb(int $id):bool
    {
        $query = "DELETE FROM dbs WHERE id = :id";
        return $this->db->execute($query, [':id'], [$id], [PDO::PARAM_INT]);
    }

    public function getDb(int $id){
        $result = $this->db->query('SELECT * FROM dbs WHERE id = :id', [':id'], [$id], [PDO::PARAM_INT]);
    }
}