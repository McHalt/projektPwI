<?php

class Tool
{
    public static function deleteMessagesFromSession()
    {
        unset($_SESSION['messages']);
    }

    public static function loadMessagesFromSession():array
    {
        if(!isset($_SESSION['messages'])) return [];
        return $_SESSION['messages'];
    }

    public static function saveMessagesToSession(array $messages):void
    {
        $_SESSION['messages'] = $messages;
    }

    public static function redirectToUrl(string $url, array $messages = []){
        self::saveMessagesToSession($messages);
        header('Location: ' . $url);
    }
}