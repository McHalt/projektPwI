<?php

require_once "Page_Model.php";

class Page_Login_Model extends Page_Model
{
    public function checkCredentials(string $email, string $password, array &$messages):bool
    {
        $result = $this->db->query('SELECT password FROM users WHERE email = "' . $email . '"');
        if(!$result->rowCount()){
            $messages['errors'][] = "Nie ma takiego użytkownika";
            return false;
        }
        if(!password_verify($password, $result->fetch()['password'])){
            $messages['errors'][] = "Niepoprawne hasło";
            return false;
        }
        $messages['info'][] = "Zalogowano";
        return true;
    }
}