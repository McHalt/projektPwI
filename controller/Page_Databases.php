<?php

class Page_Databases extends Page
{
    protected string $title = "Bazy danych :: ";
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
                    if($this->Model->deleteDb($args[1])){
                        $this->messages['info'][] = "Usunięto bazę o id $args[1]";
                    }else{
                        $this->messages['errors'][] = "Nie udało się usunąć bazy o id $args[1]";
                    }
                    Tool::redirectToUrl('/databases', $this->getMessages());
            }
        }
    }

    public function getAdditionalVars():array
    {
        $info = parent::getAdditionalVars();
        $info['dbList'] = $this->Model->getDbList();
        return $info;
    }
}