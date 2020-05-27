<?php

class Page_Login extends Page
{
    protected string $title = "Logowanie :: ";

    public function __construct()
    {
        parent::__construct();
        if($this->User->isLogged){
            $this->messages['info'][] = "Już jesteś zalogowany";
            Tool::redirectToUrl("/", $this->messages);
        }
        if(!empty($_POST['loginFormSent'])){
            if($this->Model->checkCredentials(htmlspecialchars($_POST['email']), htmlspecialchars($_POST['password']), $this->messages))
            {
                Tool::saveMessagesToSession($this->getMessages());
                $this->User->login();
            }
        }
    }
}