<?php

namespace Modules\Articles\Controllers;

use Modules\_base\BaseController;
use Modules\Articles\Models\ArticleModel;
use System\Exceptions\Exc404;
use System\Exceptions\ExcAccess;
use System\Exceptions\ExcValidation;

class ArticlesController extends BaseController {
	protected ArticleModel $model;

	public function __construct(){
        parent::__construct();
        $this->model = ArticleModel::getInstance();
	}

	public function index(){
        $articles = $this->model->all();

		$this->title = 'Home page';
		$this->content = $this->view->render('Articles/Views/v_all.twig', [
            'articles' => $articles,
        ]);
	}

	public function item(){
		$this->title = 'Article page';
		$id = (int)$this->env['params']['id'];

		$article = $this->model->get($id);
        if($article === null) {
            throw new Exc404('article not fou');
        }
        $this->content = $this->view->render('Articles/Views/v_item.twig', [
            'article' => $article,
        ]);
    }

    public function add() {
        $this->checkLogin();
        $this->title = 'Article add';
        $fields = [];
        $errors = [];
        if($this->env['server']['REQUEST_METHOD'] == 'POST') {
            try {
                $fields = [
                    'title' => $this->env['post']['title'],
                    'content' => $this->env['post']['content'],
                    'id_user' => $this->user['id_user'],
                ];
                $id = $this->model->add($fields);
                var_dump($this->env['post']['title']);

                header("Location: " . BASE_URL . "article/$id");
                exit();
            }
            catch (ExcValidation $e) {
                $bag = $e->getBag();
                $errors = $bag->firstOfAll();
            }
        }
        $this->content .= $this->view->render('Articles/Views/v_add.twig', [
            'fields' => $fields,
            'errors' => $errors,
        ]);

    }
    public function remove() {
        $this->checkLogin();
        if($this->env['server']['REQUEST_METHOD'] == 'POST') {
            $id = $this->env['post']['id_article'];
            if($this->model->remove($id)) {
                header("Location: " . BASE_URL);
                exit();
            } else {
                echo '<script>alert("Ошибка! Не удалось удалить статью")</script>';
            }
        }
    }

    public function edit() {
        $this->checkLogin();

        $errors = [];
        $id = $this->env['params']['id'];

        $article = $this->model->get($id);
        if($article === null) {
            throw new Exc404('article not found');
        }
        if($this->user['id_user'] !== $article['id_user']) {
            throw new ExcAccess('403 Forbidden ');
        }
        $fields = [
            'title' => $article['title'],
            'content' => $article['content'],
            'id_user' => $article['id_user'],
        ];


        if($this->env['server']['REQUEST_METHOD'] == 'POST') {
            try {
                $fields = [
                    'title' => $this->env['post']['title'],
                    'content' => $this->env['post']['content'],
                    'id_user' => $this->env['post']['id_user'],
                ];
                $this->model->edit($id, $fields);
                header('Location: ' . BASE_URL . "article/$id");
                exit();
            } catch (ExcValidation $e) {
                $bag = $e->getBag();
                $errors = $bag->firstOfAll();
            }
        }

        $this->content .= $this->view->render('Articles/Views/v_edit.twig', [
            'fields' => $fields,
            'errors' => $errors,
        ]);

    }
}