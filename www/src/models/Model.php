<?php

class Model
{
    protected static $tableName = '';
    protected static $columns = [];
    protected $values = [];

    function __construct($arr, $sanitaze = true)
    {
        $this->loadFromArray($arr, $sanitaze);
    }

    public function loadFromArray($arr, $sanitaze = true)
    {
        if ($arr) {
            $conn = Database::getConnection();
            foreach ($arr as $key => $value) {
                $cleanValue = $value;
                if ($sanitaze && isset($cleanValue)) {
                    $cleanValue = strip_tags(trim($cleanValue));
                    $cleanValue = htmlentities($cleanValue, ENT_NOQUOTES);
                    $cleanValue = mysqli_real_escape_string($conn, $cleanValue);
                   
                }
                $this->$key = $value;
            }
        }
        $conn->close();
    }

    public function __get($key)
    {
        return $this->values[$key];
    }

    public function __set($key, $value)
    {
        $this->values[$key] = $value;
    }

    public function getValues() {
        return $this->values;
    }

    public static function get($filters = [], $columns = '*')
    {
        $objects = []; // quero retornar o objeto e não o resultado da linha da query
        $result = static::getResultSetFromSelect($filters, $columns);
        if ($result) {
            $class = get_called_class(); // qual obj chamou a função class (User)
            while ($row = $result->fetch_assoc()) {
                array_push($objects, new $class($row)); // constroi objeto do tipo user
            }
        }
        return $objects;
    }

    public static function getOne($filters = [], $columns = '*')
    {
        $class = get_called_class(); // User
        $result = static::getResultSetFromSelect($filters, $columns);
        return $result ? new $class($result->fetch_assoc()) : null;
    }

    public static function getResultSetFromSelect($filters = [], $columns = '*')
    {
        $sql = "SELECT $columns FROM " . static::$tableName . static::getFilters($filters);
        $result = Database::getResultFromQuery($sql);
        if ($result->num_rows === 0) return null;
        return $result;
    }

    public function insert()
    {
        $sql = "INSERT INTO " . static::$tableName . " ("
            . implode(",", static::$columns) . ") VALUES ( ";
        foreach (static::$columns as $col) {
            $sql .= static::getFormatedValue($this->$col) . ",";
        }
        $sql[strlen($sql) - 1] = ')';
        $id = Database::executeSQL($sql);
        $this->id = $id;
    }

    public function update()
    {
        $sql = "UPDATE " . static::$tableName . " SET ";
        foreach (static::$columns as $col) {
            $sql .= " ${col} = " . static::getFormatedValue($this->$col) . ",";
        }

        $sql[strlen($sql) - 1] = ' ';
        $sql .= " WHERE id = {$this->id};";
        Database::executeSQL($sql);
    }

    public function delete($userToDeleteId)
    {
        $sql = " DELETE FROM " . static::$tableName  . " WHERE id = {$userToDeleteId}";
        Database::executeSQL($sql);
    }

    public static function getCount($filters = [])
    {
        $result = static::getResultSetFromSelect($filters, 'count(*) as count');
        return $result->fetch_assoc()['count'];
    }

    private static function getFilters($filters)
    {
        $sql = '';
        if (count($filters) > 0) {
            $sql .= " WHERE 1 = 1 ";
            foreach ($filters as $column => $value) {
                if ($column == 'raw') {
                    $sql .= " AND {$value}";
                } else {
                    $sql .= " AND ${column} = " . static::getFormatedValue($value);
                }
            }
        }
        return $sql;
    }

    private static function getFormatedValue($value)
    {
        if (is_null($value)) {
            return "null";
        } elseif (gettype($value) == 'string') {
            return "'${value}'";
        } else {
            return $value;
        }
    }
}
