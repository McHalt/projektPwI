<?php

abstract class Page
{
    protected string $siteTitle = "SQL Admin";
    protected string $title;
    protected array $Messages = [];

    public function getBasicConfig():array
    {
        return [
              'title' => $this->title . $this->siteTitle
        ];
    }
}