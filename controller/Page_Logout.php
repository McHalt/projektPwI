<?php

class Page_Logout extends Page
{
    public function __construct()
    {
        require_once "User.php";
        $User = new User;
        $User->logout();
    }
}