<?php

use Modules\Articles\Controllers\ArticlesController;

spl_autoload_register(function ($name) {
    $path = '../' . str_replace('\\', '/', $name) . '.php';

    if(file_exists($path)) {
        include_once ($path);
    }
});

$c = new ArticlesController();
$c->setEnviroment(['id' => '12'], [], [], []);
$c->item();
$res = $c->render();
echo $res;