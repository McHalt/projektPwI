<?php

class Page_Users extends Page
{
    protected string $title = "Użytkownicy :: ";
    protected bool $requireLoggedUser = true;

    public function __construct()
    {
        parent::__construct();
        if(!empty($_GET['args']))
        {
            $args = explode('/', $_GET['args']);
            switch($args[0])
            {
                case 'delete':
                    if($args[1] == $this->User->id){
                        $this->messages['errors'][] = "Nie można usunąć siebie";
                    }else if($this->Model->deleteUser($args[1])){
                        $this->messages['info'][] = "Usunięto użytkownika o id $args[1]";
                    }else{
                        $this->messages['errors'][] = "Nie udało się usunąć użytkownika o id $args[1]";
                    }
                    Tool::redirectToUrl('/users', $this->getMessages());
                case 'edit':
                    if((empty($args[2]) || $args[2] != 'save') && (empty($args[1]) || $args[1] != 'save')){
                        break;
                    }
                    if($args[1] == 'save'){
                        $this->Model->createUser($_POST['name'], $_POST['email'], $_POST['password']);
                    }else if($args[2] == 'save'){
                        $this->Model->modifyUser($args[1], $_POST['name'], $_POST['email'], $_POST['password']);
                    }
                    Tool::redirectToUrl('/users', $this->getMessages());
                case 'permissions':
                    if((empty($args[2]) || $args[2] != 'save')){
                        break;
                    }
                    if($args[2] == 'save'){
                        $this->Model->updatePermissions($args[1], $_POST['db']);
                    }
                    Tool::redirectToUrl('/users', $this->getMessages());
            }
        }
    }

    public function getAdditionalVars(): array
    {
        $info = parent::getAdditionalVars();
        $info['usersList'] = $this->Model->getUsersList();
        if(!empty($_GET['args']))
        {
            $args = explode('/', $_GET['args']);
            switch($args[0])
            {
                case 'permissions':
                case 'edit':
                    if(strlen($args[1]) != strlen((int) $args[1])) break;
                    $info['user'] = $this->Model->getUserInfo($args[1]);
                    break;
            }
        }
        $info['dbList'] = $this->Model->getDbList();
        return $info;
    }
}