<?php

class Page_Databases_Model extends Page_Model
{
    public function getDbList():array
    {
        $result = $this->db->query("SELECT DISTINCT * FROM dbs");
        $resArr = [];
        foreach ($result as $db) {
            $resArr[]['id'] = $db['id'];
            $resArr[count($resArr)-1]['name'] = $db['name'];
        }
        return $resArr;
    }
}