<?php

class Page_Sql extends Page
{
    protected string $title = "Wykonaj SQL :: ";
    protected bool $requireLoggedUser = true;

    public function __construct()
    {
        parent::__construct();
        if(!empty($_GET['args']))
        {
            $args = explode('/', $_GET['args']);
            if(!empty($args[1])){
                switch($args[1])
                {
                    case 'exec':
                        $res = $this->Model->executeQuery($_POST['query']);
                        if($res[2]){
                            $this->messages['errors'][] = $res[2];
                        }else{
                            $this->messages['info'][] = "Pomyślnie wykonano zapytanie";
                        }
                    Tool::redirectToUrl('/sql/' . $args[0], $this->getMessages());
                }
            }
        }
    }

    public function getAdditionalVars(): array
    {
        $info = parent::getAdditionalVars();
        $info['dbList'] = $this->Model->getDbList();
        return $info;
    }
}