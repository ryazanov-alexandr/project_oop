<?php

namespace System;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class Template {

    protected static $instance;
    protected array $globalVars = [];
    public Environment $twig;

    public static function getInstance() {
        if(static::$instance === null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function addGlobalVar(string $name, $value) {
        $this->globalVars[$name] = $value;
    }

    public function render($pathToTemplate, $vars = []) :string {
        return $this->twig->render($pathToTemplate, $vars + $this->globalVars);
    }

    public function __construct() {
        $loader = new FilesystemLoader('Modules');
        $this->twig = new Environment($loader, [
            'cache' => 'cache/twig',
            'auto_reload' => true,
            'autoescape' => false,
        ]);
    }


}