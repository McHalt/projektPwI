<?php

class Page_Login extends Page
{
    protected string $title = "Logowanie :: ";

    public function __construct()
    {
        if(!empty($_POST['loginFormSent'])){
            require_once "User.php";
            $User = new User;
            $User->login(htmlspecialchars($_POST['email']), htmlspecialchars($_POST['password']));
        }
    }
}