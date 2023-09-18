<?php

namespace Modules\Users;

use Modules\Users\Controllers\Office;
use Modules\Users\Controllers\UserController;
use System\Contracts\IModule;
use System\Contracts\IRouter;

class UsersModule implements IModule {

    public function registerRoutes(IRouter $router): void {
        $router->addRoute("/^login$/", UserController::class, 'login');
        $router->addRoute("/^office/", Office::class);
    }
}