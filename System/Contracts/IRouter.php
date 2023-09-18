<?php

namespace System\Contracts;

interface IRouter {
    public function addRoute(string $regExp, string $controllerName, string $controllerMethod = 'index');
    public function resolvePath(string $url) : array;
}