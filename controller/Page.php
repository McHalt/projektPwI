<?php

require_once "User.php";

abstract class Page
{
    protected string $siteTitle = "SQL Admin";
    protected string $title;
    protected bool $requireLoggedUser = false;
    protected array $messages = [];
    public User $User;

    public function getBasicConfig():array
    {
        return [
              'title' => $this->title . $this->siteTitle
        ];
    }

    public function getMessages():array
    {
        return $this->messages;
    }

    public function __construct()
    {
        $this->User = new User;
        if($this->requireLoggedUser && !$this->User->isLogged){
            header('Location: /');
        }
    }
}