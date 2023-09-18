<?php

namespace Modules\Articles\Models;
use System\Model;

class ArticleModel extends Model {
    protected static $instance;
    protected string $table = 'oop_articles_index';
    protected string $pk = 'id_article';

    protected array $validationRules = [
        'title' => 'required|min:6|max:20|unique',
        'content' => 'required|min:20',
        'id_user' => 'required|numeric',
    ];

}