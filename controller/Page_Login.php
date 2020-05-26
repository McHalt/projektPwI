<?php

class Page_Login extends Page
{
    protected string $title = "Logowanie :: ";

    public function __construct()
    {
        parent::__construct();
        if($this->User->isLogged) header('Location: /');
        if(!empty($_POST['loginFormSent'])){
            $this->User->login(htmlspecialchars($_POST['email']), htmlspecialchars($_POST['password']));
        }
    }
}