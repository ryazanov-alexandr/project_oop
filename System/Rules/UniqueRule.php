<?php

namespace System\Rules;

use PDO;
use Rakit\Validation\Rule;
use System\Database\Connection;

class UniqueRule extends Rule
{

    protected $message = ":attribute :value уже используется";

    protected $fillableParams = ['table', 'column', 'pk', 'id'];

    protected Connection $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function check($value): bool
    {
        // make sure required parameters exists
        $this->requireParameters(['table', 'column']);

        // getting parameters
        $column = $this->parameter('column');
        $table = $this->parameter('table');
        $pk = $this->parameter('pk');
        $id = $this->parameter('id');

        // do query
        $queryStr = "SELECT COUNT(*) FROM `{$table}` WHERE `{$column}` = :value";
        $queryVars = ['value' => $value];

        if($pk !== null && $id !== null) {
            $queryStr .= " AND $pk <> :id";
            $queryVars += ['id' => $id];
        }

        $result = $this->db->query($queryStr, $queryVars);
        $count = (int)$result->fetchColumn();

        return $count === 0;
    }
}