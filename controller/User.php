<?php
class User
{
    private bool $isLogged = false;

    public function __construct()
    {
        if(!empty($_SESSION['user'])){
            $data = json_decode($_SESSION['user']);
            foreach(get_object_vars($this) as $var=>$value){
                $this->$var = $data->$var;
            }
        }
    }

    private function saveUserToSession(){
        $data = new stdClass();
        foreach(get_object_vars($this) as $var=>$value){
            $data->$var = $value;
        }
        $_SESSION['user'] = json_encode($data);
    }

    public function getInfo():array
    {
        return get_object_vars($this);
    }

    public function login(string $email, string $password):bool
    {
        if($this->isLogged) header('Location: /');
        if($email == "przykladowy@email.com" && $password == "admin123"){
            $this->isLogged = true;
            $this->saveUserToSession();
            if($this->isLogged) header('Location: /');
            return true;
        }
        $this->saveUserToSession();
        return false;
    }

    public function logout():void
    {
        $_SESSION = array();
        header('Location: /');
    }
}