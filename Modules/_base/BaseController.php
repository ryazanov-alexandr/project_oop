<?php

namespace Modules\_base;

use Modules\Users\Auth\Auth;
use System\Contracts\IController;
use System\Exceptions\Exc404;
use System\Exceptions\ExcAccess;
use System\Template;

class BaseController implements IController{
    protected string $title = '';
    protected string $content = '';
    protected array $env = [];
    protected ?array $user = [];
    protected Template $view;

    public function __construct() {
        $this->view = Template::getInstance();
        $this->user = Auth::getUser();
    }

    public function setEnviroment(array $urlParams, array $get, array $post, array $server) : void{
        $this->env['params'] = $urlParams;
        $this->env['get'] = $get;
        $this->env['post'] = $post;
        $this->env['server'] = $server;
    }

    public function render() : string{
        return $this->view->render('_base/v_main.twig', [
            'title' => $this->title,
            'content' => $this->content,
        ]);
    }

    public function __call(string $name, array $arguments) {
        throw new Exc404("controller has not action = $name");
    }

    public function checkLogin() {
        if($this->user === null) {
            throw new ExcAccess('not auth');
        }
    }
}