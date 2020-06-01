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
                case 'edit':
                    if((empty($args[2]) || $args[2] != 'save') && (empty($args[1]) || $args[1] != 'save')){
                        break;
                    }
                    if($args[1] == 'save'){
                        $this->Model->createDb($_POST['db_name'], $_POST['db_table']);
                    }else if($args[2] == 'save'){
                        $this->Model->modifyDb($_POST['db_name'], $_POST['db_table']);
                    }
                    Tool::redirectToUrl('/databases', $this->getMessages());
            }
        }
    }

    public function getAdditionalVars():array
    {
        $info = parent::getAdditionalVars();
        $info['dbList'] = $this->Model->getDbList();
        if(!empty($_GET['args']))
        {
            $args = explode('/', $_GET['args']);
            switch($args[0])
            {
                case 'show':
                case 'edit':
                    if(strlen($args[1]) != strlen((int) $args[1])) break;
                    $info['db'] = $this->Model->getDbInfo($args[1]);
                    break;
            }
        }
        return $info;
    }
}