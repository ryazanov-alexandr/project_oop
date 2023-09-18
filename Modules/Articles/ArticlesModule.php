<?php

namespace Modules\Articles;

use Modules\Articles\Controllers\ArticlesController;
use System\Contracts\IModule;
use System\Contracts\IRouter;

class ArticlesModule implements IModule {

    public function registerRoutes(IRouter $router): void {
        $i = '[1-9]+\d*';
        $map = [1 => 'id'];
        $router->addRoute('/^$/', ArticlesController::class);
        $router->addRoute("/^article\/($i)$/", ArticlesController::class, 'item', $map);
        $router->addRoute('/^article\/add$/', ArticlesController::class, 'add');
        $router->addRoute("/^article\/delete$/", ArticlesController::class, 'remove', $map);
        $router->addRoute("/^article\/edit\/($i)$/", ArticlesController::class, 'edit', $map);

        $router->addRoute("/^article\/edit\/($i)$/", ArticlesController::class, 'edit', $map);
    }
}