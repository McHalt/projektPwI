<?php

session_start();

require_once 'vendor/autoload.php';
require_once 'controller/Page.php';

$page = empty($_GET['page']) ? 'home' : $_GET['page'];

$libs = ['css' => ['main.css'], 'js' => []];

$pageControllerPath = 'controller/Page_' . ucfirst($page) . '.php';

if(!file_exists($pageControllerPath)) {
    $pageControllerPath = 'controller/Page_404.php';
    $page = "404";
}

require_once $pageControllerPath;

$page = ucfirst($page);

$pageName = 'Page_' . $page;
$Page = new $pageName;

$config = [
      'lang' => 'pl'
    , 'page' => $page
];

$loader = new \Twig\Loader\FilesystemLoader('view');
$twig = new \Twig\Environment($loader, [
    'cache' => 'cache',
    'debug' => true,
    'auto_reload' => true
]);

$config = array_merge($config, $Page->getBasicConfig());

echo $twig->render('html.twig', [
      'config'  => $config
    , 'libs'    => $libs
    , 'user'    => $Page->User->getInfo()
]);