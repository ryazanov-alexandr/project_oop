<?php

namespace Modules\Users\Controllers;

use Modules\_base\BaseController;
use Modules\Users\Models\Sessions;
use Modules\Users\Models\UserModel;
use System\Session;

class UserController extends BaseController
{
    protected UserModel $model;
    protected Sessions $session;

    public function __construct(){
        parent::__construct();
        $this->model = UserModel::getInstance();
        $this->session = Sessions::getInstance();
    }

    public function login() {
        $this->title = 'Login';
        $error = false;

        if($this->env['server']['REQUEST_METHOD'] == 'POST') {
            $login = trim($this->env['post']['login']);
            $password = trim($this->env['post']['password']);

            $user = $this->model->getByLogin($login);

            if($user === null || !password_verify($password, $user['password'])) {
                $error = true;
            } else {
                $fields = [
                  'id_user' => $user['id_user'],
                  'token' => $this->session->generateToken(),
                ];

                $this->session->add($fields);
                setcookie('token', $fields['token'], time() + 3600 * 24 * 30, BASE_URL);
                Session::set('token', $fields['token']);
                header('Location: ' . BASE_URL . 'office');
                exit();
            }
        }

        $this->content .= $this->view->render('Users/Views/v_login.twig', [
            'error' => $error
        ]);
    }

}