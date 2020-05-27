<?php

class Page_Logout extends Page
{
    public function __construct()
    {
        parent::__construct();
        $this->messages['info'][] = "Wylogowano";
        Tool::saveMessagesToSession($this->messages);
        $this->User->logout();
    }
}