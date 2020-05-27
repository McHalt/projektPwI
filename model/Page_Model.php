<?php

class Page_Model
{
    protected Database $db;

    public function __construct()
    {
        $this->db = new Database();
    }
}