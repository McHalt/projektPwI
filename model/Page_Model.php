<?php

class Page_Model
{
    protected Database $db;
    protected array $dbList;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getDbList():array
    {
        if(!empty($this->dbList)){
            return $this->dbList;
        }
        require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/User.php';
        $User = new User;
        $result = $this->db->query('SELECT * FROM dbs WHERE id IN (SELECT database_id FROM users_to_databases WHERE user_id = ' . $User->id . ')');
        $resArr = [];
        foreach ($result as $db) {
            $resArr[]['id'] = $db['id'];
            $resArr[count($resArr)-1]['name'] = $db['name'];
        }
        $this->dbList = $resArr;
        return $this->dbList;
    }
}