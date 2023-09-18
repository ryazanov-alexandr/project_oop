<?php

namespace System;

use System\Database\Connection;
use System\Database\QuerySelect;
use System\Database\SelectBuilder;
use System\Exceptions\ExcValidation;
use Rakit\Validation\Validator;
use System\Rules\UniqueRule;

abstract class Model
{
    protected static $instance;
    protected Connection $db;
    protected string $table;
    protected string $pk;
    protected array $validationRules;
    protected Validator $validator;

    public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    protected function __construct()
    {
        $this->db = Connection::getInstance();
        //$this->setValidationRules();
        $this->validator = new Validator();
        $this->validator->addValidator('unique', new UniqueRule($this->db));
    }


    public function all(): array
    {
        return $this->selector()->get();
    }

    public function selector(): QuerySelect
    {
        $builder = new SelectBuilder($this->table);

        return new QuerySelect($this->db, $builder);
    }

    public function get(int $id)
    {
        $result = $this->selector()->where("{$this->pk} = :pk", ['pk' => $id])->get();
        return $result[0] ?? null;
    }

    public function add(array $fields): int
    {
        $rules = $this->rebuildRules($this->validationRules);
        $validation = $this->validator->validate($fields, $rules);

        if ($validation->fails()) {
            throw new ExcValidation('Cant add article', $validation->errors());
        }

        $names = [];
        $masks = [];

        foreach ($fields as $field => $val) {
            $names[] = $field;
            $masks[] = ":$field";
        }

        $names_s = implode(', ', $names);
        $masks_s = implode(', ', $masks);

        $query = "INSERT INTO {$this->table} ($names_s) VALUES ($masks_s)";
        $this->db->query($query, $fields);

        return $this->db->lastInsertId();
    }

    public function remove(int $id): bool
    {
        $result = $this->db->query("DELETE FROM {$this->table} WHERE {$this->pk} = :pk", ['pk' => $id]);
        return $result->rowCount() != 0;
    }

    public function edit(int $id, array $fields): bool
    {
        $rules = $this->rebuildRules($this->validationRules, $id);
        $validation = $this->validator->validate($fields, $rules);

        if ($validation->fails()) {
            throw new ExcValidation('Cant edit article', $validation->errors());
        }

        $pairs = [];

        foreach ($fields as $field => $val) {
            $pairs[] = "$field = :$field";
        }

        $pairsStr = implode(', ', $pairs);
        // $fields["pk"] = "$id";
        $query = "UPDATE {$this->table} SET $pairsStr WHERE {$this->pk} = :{$this->pk}";
        $result = $this->db->query($query, $fields + [$this->pk => $id]);
        return true;
    }

    protected function rebuildRules(array $rules, ?int $pk = null)
    {
        $mask = 'unique';

        foreach ($rules as $field => $rule) {
            if (strpos($rule, $mask) !== false) {
                $updRule = str_replace($mask, "$mask:{$this->table},$field", $rule);
                if($pk !== null) {
                    $updRule .= ",{$this->pk},$pk";
                }
                $rules[$field] = $updRule;
            }
        }

        return $rules;
    }
}