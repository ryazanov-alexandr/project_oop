<?php

use Modules\Articles\ArticlesModule;
use Modules\Users\UsersModule;
use System\ModulesDispatcher;
use System\Router;
use System\Template;

include_once('init.php');
include_once ('vendor/autoload.php');

const BASE_URL = '/';

try {
    Template::getInstance()->addGlobalVar('baseUrl', BASE_URL);

    $modules = new ModulesDispatcher();
    $modules->add(new ArticlesModule());
    $modules->add(new UsersModule());
    $router = new Router(BASE_URL);

    $modules->registerRoutes($router);


    $uri = $_SERVER['REQUEST_URI'];

    $activeRoute = $router->resolvePath($uri);

    $c = $activeRoute['controller'];
    $m = $activeRoute['method'];

    $c->$m();
    $html = $c->render();
    echo $html;
} catch (Throwable $e) {
    echo $e->getMessage();
}

