<?php

class Config
{
    private string $configFilePath;
    private array $config;

    public function __construct()
    {
        $this->configFilePath = $_SERVER['DOCUMENT_ROOT'] . "/sqladmin.conf";
        $this->loadConfig();
    }

    public function __destruct()
    {
        $this->saveConfig();
    }

    private function loadConfig():void
    {
        foreach(explode(PHP_EOL, file_get_contents($this->configFilePath)) as $field){
            $temp = explode('=', $field);
            $this->config[$temp[0]] = trim($temp[1]);
        }
    }

    private function saveConfig():void
    {
        $fileContent = array();
        foreach($this->config as $key=>$value){
            $fileContent[] = implode('=', [$key, $value]);
        }
        file_put_contents($this->configFilePath, implode(PHP_EOL, $fileContent));
    }

    public function getDbConfig():array
    {
        return ['username' => $this->config['realDbUserName'], 'password' => $this->config['realDbUserPassword']];
    }
}