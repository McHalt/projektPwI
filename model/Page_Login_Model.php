<?php

require_once "Page_Model.php";

class Page_Login_Model extends Page_Model
{
    public function checkCredentials(string $email, string $password, array &$messages):bool
    {
        $result = $this->db->query('SELECT id,password FROM users WHERE email = "' . $email . '"');
        if(empty($result)){
            $messages['errors'][] = "Nie ma takiego użytkownika";
            return false;
        }
        if(!password_verify($password, $result[0]['password'])){
            $messages['errors'][] = "Niepoprawne hasło";
            return false;
        }
        $_SESSION['user_id'] = $result[0]['id'];
        $messages['info'][] = "Zalogowano";
        return true;
    }
}