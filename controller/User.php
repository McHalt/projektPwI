<?php
class User
{
    public bool $isLogged = false;

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

    public function login():void
    {
        $this->isLogged = true;
        $this->saveUserToSession();
        header('Location: /');
    }

    public function logout():void
    {
        $messages = Tool::loadMessagesFromSession();
        $_SESSION = array();
        Tool::saveMessagesToSession($messages);
        header('Location: /');
    }
}