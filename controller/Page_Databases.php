<?php

class Page_Databases extends Page
{
    protected string $title = "Bazy danych :: ";
    protected bool $requireLoggedUser = true;

    public function getAdditionalVars():array
    {
        $info = array();
        $info['dbList'] = $this->Model->getDbList();
        return $info;
    }
}