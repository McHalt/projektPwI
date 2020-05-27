<?php

require_once "User.php";

require_once $_SERVER['DOCUMENT_ROOT'] . "/model/Page_Model.php";

abstract class Page
{
    protected string $siteTitle = "SQL Admin";
    protected string $title = "";
    protected bool $requireLoggedUser = false;
    protected array $messages = ['errors' => array(), 'info' => array()];
    public User $User;
    protected Page_Model $Model;

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
        $modelClassname = get_class($this) . '_Model';
        if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/model/' . $modelClassname . '.php')){
            require_once $_SERVER['DOCUMENT_ROOT'] . '/model/' . $modelClassname . '.php';
            $this->Model = new $modelClassname;
        }
        $this->User = new User;
        $this->messages = Tool::loadMessagesFromSession();
        Tool::deleteMessagesFromSession();
        if($this->requireLoggedUser && !$this->User->isLogged){
            $this->messages['errors'][] = "Ta akcja wymaga zalogowania";
            Tool::redirectToUrl("/", $this->getMessages());
        }
    }

    function getAdditionalVars():array
    {
        return [];
    }
}