<?php


namespace App\SQLite;


use App\Config;
use App\DBConnectionInterface;
use SQLite3;

class SQLiteConnection implements DBConnectionInterface
{
    /**
     * @var SQLite3
     */
    private static $db;

    /**
     * @return SQLite3
     * @throws \Exception
     */
    public static function getConnection()
    {
        if (self::$db == null) {
            self::$db = new SQLite3(Config::SQLITE3_DB_FILE);

            if (self::$db == null) {
                throw new \Exception('Can not connect to SQLite DB, check the configuration !');
            }
        }

        return self::$db;
    }

    /**
     * @param $tableName
     * @throws \Exception
     */
    public function createTable($tableName)
    {
        $db = self::getConnection();
        try {
            $db->exec(sprintf(Config::SQLITE3_CREATE_TABLE_COMMAND, $tableName));
        } catch (\PDOException $ex) {
            throw new \Exception('Can not create table, check the command syntax !');
        }
    }

    /**
     * @param $tableName
     * @param array $data
     * @param $field
     * @param $value
     */
    public function update($tableName, array $data, $field, $value)
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
        $query = sprintf(Config::UPDATE_COMMAND, $tableName, implode(',', $updates), $field, $value);
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

    /**
     * @param $tableName
     * @param array $data
     * @throws \Exception
     */
    public function insert($tableName, array $data)
    {
        $db = self::getConnection();
        $columns = array_keys($data);
        $paramNames = array_map(function($column) {
            return ":$column";
        }, $columns);

        $values = array_values($data);
        $params = array_combine($paramNames, $values);
        $query = sprintf(Config::INSERT_COMMAND, $tableName, implode(',', $columns), implode(',', $paramNames));
        try {
            $stmt = $db->prepare($query);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();
        } catch (\PDOException $ex) {
            throw $ex;
        }
    }

    public function query($tableName, array $columns)
    {
        $db = self::getConnection();
        $stmt = $db->query(sprintf(Config::QUERY_TABLE_COMMAND, implode(',', $columns), $tableName));

        $domains = [];
        while ($row = $stmt->fetchArray(SQLITE3_ASSOC)) {
            $domain = [];
            foreach ($columns as $column) {
                $domain[$column] = $row[$column];
            }
            $domains[] = $domain;
        }

        return $domains;
    }

    public function getByField($tableName, $field, $value)
    {
        $db = self::getConnection();
        $query = sprintf(Config::GET_BY_FIELD_COMMAND, $tableName, $field, ":$field");
        try {
            $stmt = $db->prepare($query);
            $stmt->bindValue(":$field", $value);
            $result = $stmt->execute();
            $domains = [];
            while($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $domains[] = $row;
            }

            return $domains;
        } catch (\PDOException $ex) {
            throw $ex;
        }
    }

    public function increaseFieldBy($tableName, $increasedField, $conditionField, $conditionValue)
    {
        $db = self::getConnection();

        $query = "UPDATE $tableName SET $increasedField = $increasedField + 1 WHERE $conditionField = :$conditionField";
        try {
            $stmt = $db->prepare($query);
            $stmt->bindValue(":$conditionField", $conditionValue);

            // execute the update statement
            return $stmt->execute();
        } catch (\PDOException $ex) {
            throw $ex;
        }
    }
}