<?php

class Page_Logout extends Page
{
    public function __construct()
    {
        parent::__construct();
        $this->User->logout();
    }
}