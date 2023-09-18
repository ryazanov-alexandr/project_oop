<?php

namespace Modules\Users\Auth;

use Modules\Users\Models\Sessions;
use Modules\Users\Models\UserModel;
use System\Session;

class Auth {
    const ACTIVE_STATUS = 1;
    public static function getUser() : ?array {
        $token = Session::get('token') ?? $_COOKIE['token'];

        if($token === null) {
            return null;
        }

        $sessionModel = Sessions::getInstance();
        $session = $sessionModel->getByToken($token);

        if($session === null || (int)$session['status'] !== self::ACTIVE_STATUS) {
            if(isset($_COOKIE['token'])) {
                unset($_COOKIE['token']);
                setcookie('token', null, -1);
            }

            return null;
        }

        return UserModel::getInstance()->get($session['id_user']);
    }
}