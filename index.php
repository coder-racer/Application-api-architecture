<?php

    ini_set("display_errors", true);
    error_reporting(E_ALL);


    $scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    define('HTTP_URL', '/'. substr_replace(trim($_SERVER['REQUEST_URI'], '/'), '', 0, strlen($scriptName)));

    define('ROOT', str_replace('\\', '/', rtrim(dirname(__FILE__), '/')) . "/");
    define('BASE', ROOT . 'base/');
    define('CONTROLLERS', ROOT . 'Controllers/');
    define('MODELS', ROOT . 'Models/');



    require_once "base/autoload.php";
    use Router\Router;

    $request = new Http\Request();
    $response = new Http\Response();

    $response->setHeader('Access-Control-Allow-Origin: *');
    $response->setHeader("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    $response->setHeader('Content-Type: application/json; charset=UTF-8');

    $router = new Router($request->getUrl(), $request->getMethod());

    require_once 'router/routes.php';


    $router->run();

    $response->render();


