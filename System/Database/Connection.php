<?php

namespace System\Database;

use PDO;
use PDOStatement;

class Connection {
    protected $db;
    protected static $instance;

    public static function getInstance() {
        if(static::$instance === null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    protected function __construct() {
        $this->db = new PDO('mysql:host=localhost;dbname=test', 'root', '');
    }

    public function select(string $query, array $params = []) : ?array {
        return $this->query($query, $params)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function query(string $query, array $params = []) : PDOStatement {
        $query = $this->db->prepare($query);
        $query->execute($params);

        return $query;
    }

    public function lastInsertId() : int {
        return (int)$this->db->lastInsertId();
    }
}