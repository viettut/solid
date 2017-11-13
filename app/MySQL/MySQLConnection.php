<?php


namespace App\MySQL;


use App\DBConnectionInterface;

class MySQLConnection implements DBConnectionInterface
{
    const USERNAME = "tagcadedev";
    const PASSWORD = "tagcadedev";
    const HOST     = "localhost";
    const DB       = "solid";

    /**
     * PDO instance
     * @var \PDO
     */
    private static $pdo;

    /**
     * @return \PDO
     * @throws \Exception
     */
    public static function getConnection()
    {
        if (self::$pdo == null) {
            $username = self::USERNAME;
            $password = self::PASSWORD;
            $host = self::HOST;
            $db = self::DB;
            self::$pdo = new \PDO("mysql:dbname=$db;host=$host", $username, $password);
            if (self::$pdo == null) {
                throw new \Exception('Can not connect to SQLite DB, check the configuration !');
            }
        }

        return self::$pdo;
    }

    public function createTable($tableName)
    {
        $db = self::getConnection();
        try {
            $db->exec(sprintf(self::CREATE_TABLE_COMMAND, $tableName));
        } catch (\PDOException $ex) {
            throw new \Exception('Can not create table, check the command syntax !');
        }
    }

    public function update($tableName, array $data, $field, $value, $type = DBConnectionInterface::TYPE_STRING)
    {
        $db = self::getConnection();
        $columns = array_keys($data);
        $paramNames = array_map(function($column) {
            return ":$column";
        }, $columns);
        $updates = array_map(function($column) {
            return "$column = :$column";
        }, $columns);
        $values = array_values($data);
        $params = array_combine($paramNames, $values);
        $query = sprintf('UPDATE %s SET %s WHERE %s = %s', $tableName, implode(',', $updates), $field, $value);
        try {
            $stmt = $db->prepare($query);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            // execute the update statement
            return $stmt->execute();
        } catch (\PDOException $ex) {
            throw $ex;
        }
    }

    public function increaseFieldBy($tableName, $increasedField, $conditionField, $conditionValue, $type = DBConnectionInterface::TYPE_STRING)
    {
        $db = self::getConnection();
        if ($type == DBConnectionInterface::TYPE_STRING) {
            $conditionValue = "'$conditionValue'";
        }
        $query = "UPDATE $tableName SET $increasedField = $increasedField + 1 WHERE $conditionField = $conditionValue";
        try {
            $stmt = $db->prepare($query);
            // execute the update statement
            return $stmt->execute();
        } catch (\PDOException $ex) {
            throw $ex;
        }
    }


    public function insert($tableName, array $data)
    {
        $db = self::getConnection();
        $columns = array_keys($data);
        $paramNames = array_map(function($column) {
            return ":$column";
        }, $columns);

        $values = array_values($data);
        $params = array_combine($paramNames, $values);
        $query = sprintf(self::INSERT_COMMAND, $tableName, implode(',', $columns), implode(',', $paramNames));
        try {
            $stmt = $db->prepare($query);
            $stmt->execute($params);
            return $db->lastInsertId();
        } catch (\PDOException $ex) {
            throw $ex;
        }
    }

    public function query($tableName, array $columns)
    {
        $db = self::getConnection();
        $stmt = $db->query(sprintf(self::QUERY_TABLE_COMMAND, implode(',', $columns), $tableName));

        $domains = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $domain = [];
            foreach ($columns as $column) {
                $domain[$column] = $row[$column];
            }
            $domains[] = $domain;
        }

        return $domains;
    }

    public function getByField($tableName, $field, $value, $type = DBConnectionInterface::TYPE_STRING)
    {
        $db = self::getConnection();
        $query = sprintf(self::GET_BY_DOMAIN_COMMAND, $tableName, $field, $value);
        try {
            $stmt = $db->query($query);
            return $stmt->fetchAll();
        } catch (\PDOException $ex) {
            throw $ex;
        }
    }
}