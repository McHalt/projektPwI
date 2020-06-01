<?php

class Page_Users_Model extends Page_Model
{   private array $usersList;

    public function getUsersList():array
    {
        if(!empty($this->usersList)){
            return $this->usersList;
        }
        $result = $this->db->query("SELECT * FROM users");
        $resArr = [];
        foreach ($result as $user) {
            $resArr[]['id'] = $user['id'];
            $resArr[count($resArr)-1]['email'] = $user['email'];
            $resArr[count($resArr)-1]['name'] = $user['name'];
        }
        $this->usersList = $resArr;
        return $this->usersList;
    }

    public function createUser(string $name, string $email, string $password)
    {
        $sql = 'INSERT INTO users (name, email, password) VALUES (:name, :email, :password)';
        $this->db->execute($sql,
            [':name', ':email', ':password'],
            [$name, $email, password_hash($password, PASSWORD_BCRYPT)],
            [PDO::PARAM_STR, PDO::PARAM_STR, PDO::PARAM_STR]
        );
    }

    public function modifyUser(int $id, string $name, string $email, string $password)
    {
        $sql = 'UPDATE users SET name = :name, email = :email' . (empty($password) ? '' : ', password = :password ') . ' WHERE id = :id';
        $arrToMerge = [
            (empty($password) ? [] : [':password']),
            (empty($password) ? [] : [password_hash($password, PASSWORD_BCRYPT)]),
            (empty($password) ? [] : [PDO::PARAM_STR])
        ];
        $this->db->execute($sql,
            array_merge([':name', ':email', ':id'], $arrToMerge[0]),
            array_merge([$name, $email, $id], $arrToMerge[1]),
            array_merge([PDO::PARAM_STR, PDO::PARAM_STR, PDO::PARAM_INT], $arrToMerge[2])
        );
    }

    public function deleteUser(int $id):bool
    {
        $query = 'DELETE FROM users_to_databases WHERE user_id = :id';
        $this->db->execute($query, [':id'], [$id], [PDO::PARAM_INT]);
        $query = 'DELETE FROM users WHERE id = :id';
        return $this->db->execute($query, [':id'], [$id], [PDO::PARAM_INT]);
    }

    public function getUserInfo(int $id){
        $result = $this->db->query('SELECT name, email FROM users WHERE id = ' . $id);
        $user = [];
        foreach($result as $item){
            $user['id'] = $id;
            $user['name'] = $item['name'];
            $user['email'] = $item['email'];
        }
        return $user;
    }

    public function getDbList():array
    {
        $info = parent::getDbList();
        if(!empty($_GET['args'])) {
            if(count(explode('/', $_GET['args'])) > 1) {
                foreach ($info as $key => $db) {
                    $qryRes = $this->db->query(
                        'SELECT * FROM users_to_databases WHERE user_id = :user AND database_id = :db',
                        [':user', ':db'],
                        [explode('/', $_GET['args'])[1], $db['id']],
                        [PDO::PARAM_INT, PDO::PARAM_INT]
                    );
                    $info[$key]['permissions'] = empty($qryRes) ? 0 : 1;
                }
            }
        }
        return $info;
    }

    public function updatePermissions(int $user, array $dbList){
        foreach($dbList as $key=>$db){
            if(empty($db['checked'])){
                $qry = 'DELETE FROM users_to_databases WHERE user_id = :user AND database_id = :db';
                $this->db->execute($qry, [':user', ':db'], [$user, $key], [PDO::PARAM_INT, PDO::PARAM_INT]);
            }else{
                $qry = 'INSERT IGNORE INTO users_to_databases VALUES (:user, :db)';
                $this->db->execute($qry, [':user', ':db'], [$user, $key], [PDO::PARAM_INT, PDO::PARAM_INT]);
            }
        }
    }
}