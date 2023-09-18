<?php

namespace Modules\Users\Models;

use System\Model;

class UserModel extends Model
{
    protected static $instance;
    protected string $table = 'oop_users_index';
    protected string $pk = 'id_user';

    protected array $validationRules = [];

    public function getByLogin(string $login) : ?array {
        $result = $this->selector()->where('login = :login', ['login' => $login])->get();

        return $result[0] ?? null;
    }
}