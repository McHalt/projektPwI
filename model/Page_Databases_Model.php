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

    public function getDbInfo(int $id)
    {
        $query = 'SELECT name FROM dbs WHERE id = :id';
        $dbName = $this->db->query($query, [':id'], [$id], [PDO::PARAM_INT])[0]['name'];
        $Config = new Config();
        $dbConfig = $Config->getDbConfig();
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=' . $dbName, $dbConfig['username'], $dbConfig['password']);
        } catch (PDOException $e) {
            return;
        }
        $sth = $pdo->prepare("SHOW TABLES");
        $sth->execute();
        $tables = [];
        foreach($sth->fetchAll(PDO::FETCH_ASSOC) as $t)
        {
            $sth2 = $pdo->prepare('DESCRIBE ' . $t['Tables_in_' . $dbName]);
            $sth2->execute();
            $info = ['name' => $t['Tables_in_' . $dbName], 'fields' => []];
            foreach($sth2->fetchAll(PDO::FETCH_ASSOC) as $t2){
                $t3 = [
                      'name'    => $t2['Field']
                    , 'type'    => explode('(', $t2['Type'])[0]
                    , 'length'  => strpos($t2['Type'], '(') ? substr(explode('(', $t2['Type'])[1], 0, -1) : ''
                ];
                $info['fields'][] = $t3;
            }
            $tables[] = $info;
        }
        return ['id' => $id, 'name' => $dbName, 'tables' => $tables];
    }

    public function createDb(string $dbName, array $dbSchema){
        require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/User.php';
        $User = new User;
        $this->db->execute('CREATE DATABASE ' . $dbName);
        $this->db->execute('INSERT INTO dbs (name, created_at, created_by) VALUES (:dbName, :created_at, :created_by)',
        [':dbName', ':created_at', ':created_by'],
        [$dbName, date('Y-m-d'), $User->id],
        [PDO::PARAM_STR, PDO::PARAM_STR, PDO::PARAM_INT]);
        $this->modifyDb($dbName, $dbSchema);
        return true;
    }

    public function modifyDb(string $dbName, array $dbSchema)
    {
        $Config = new Config();
        $dbConfig = $Config->getDbConfig();
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=' . $dbName, $dbConfig['username'], $dbConfig['password']);
        } catch (PDOException $e) {
            return false;
        }

        $dbTableNames = [];
        $tableNames = [];

        $sth = $pdo->prepare("SHOW TABLES");
        $sth->execute();

        foreach($sth->fetchAll(PDO::FETCH_ASSOC) as $item){
            $dbTableNames[] = $item['Tables_in_' . $dbName];
        }

        foreach($dbSchema as $table){
            if(!empty($table['name']))
                $tableNames[] = $table['name'];
        }

        $dbTablesIntersect = array_intersect($dbTableNames, $tableNames);

        foreach($dbTableNames as $tableName){
            if(!in_array($tableName, $dbTablesIntersect)){
                $pdo->exec('DROP TABLE ' . $tableName);
            }
        }

        foreach($tableNames as $tableName){
            if(!in_array($tableName, $dbTablesIntersect)){
                $query = 'CREATE TABLE ' . str_replace(' ', '_', $tableName) . '(';
                foreach($dbSchema[array_search($tableName, $tableNames)] as $key=>$field){
                    if(!is_numeric($key)) continue;
                    $query .= $field['name'] . ' ' . $field['type'];
                    if(!empty($field['length'])){
                        $query .= '(' . $field['length'] . ')';
                    }
                    $query .= ',';
                }
                $query = substr($query, 0, -1);
                $query .= ");";
                $pdo->exec($query);
            }
        }

        foreach($dbTablesIntersect as $tableName){
            $sth = $pdo->prepare('DESCRIBE ' . $tableName);
            $sth->execute();
            $info = ['name' => $tableName, 'fields' => []];
            foreach($sth->fetchAll(PDO::FETCH_ASSOC) as $t2){
                $t3 = [
                    'name'    => $t2['Field']
                    , 'type'    => explode('(', $t2['Type'])[0]
                    , 'length'  => strpos($t2['Type'], '(') ? substr(explode('(', $t2['Type'])[1], 0, -1) : ''
                ];
                $info['fields'][] = $t3;
            }
            $temp = $dbSchema[array_search($tableName, $tableNames)];
            unset($temp['name']);
            if($temp != $info['fields']){
                $pdo->exec('DROP TABLE ' . $tableName);
                $query = 'CREATE TABLE ' . str_replace(' ', '_', $tableName) . '(';
                foreach($dbSchema[array_search($tableName, $tableNames)] as $key=>$field){
                    if(!is_numeric($key)) continue;
                    $query .= $field['name'] . ' ' . $field['type'];
                    if(!empty($field['length'])){
                        $query .= '(' . $field['length'] . ')';
                    }
                    $query .= ',';
                }
                $query = substr($query, 0, -1);
                $query .= ");";
                $pdo->exec($query);
            }
        }

        return true;
    }
}