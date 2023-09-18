<?php

namespace Modules\Users\Controllers;

use Modules\_base\BaseController;
class Office extends BaseController {
    public function __construct() {
        parent::__construct();
        $this->checkLogin();
    }

    public function index() {
        $this->title = 'Office';
        $this->content = 'closed page';
    }

}