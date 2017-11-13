<?php


namespace App\MySQL;


use App\DBConnectionInterface;

class MySQLConnection implements DBConnectionInterface
{
    const CREATE_TABLE_COMMAND = "CREATE TABLE IF NOT EXISTS %s(id MEDIUMINT NOT NULL AUTO_INCREMENT, domain VARCHAR (255) NOT NULL, hits INTEGER NOT NULL, unique_users INTEGER NOT NULL, PRIMARY KEY (id))";

    const USERNAME = "viettut";
    const PASSWORD = "12345678";
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
                throw new \Exception('Can not connect to MySQL, check the configuration !');
            }
        }

        return self::$pdo;
    }

    /**
     * @param $tableName
     * @throws \Exception
     */
    public function createTable($tableName)
    {
        $db = self::getConnection();
        try {
            $db->exec(sprintf(self::CREATE_TABLE_COMMAND, $tableName));
        } catch (\PDOException $ex) {
            throw new \Exception('Can not create table, check the command syntax !');
        }
    }

    /**
     * @param $tableName
     * @param array $data
     * @param $field
     * @param $value
     * @return bool
     * @throws \Exception
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

    /**
     * @param $tableName
     * @param $increasedField
     * @param $conditionField
     * @param $conditionValue
     * @return bool
     * @throws \Exception
     */
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

    /**
     * @param $tableName
     * @param array $data
     * @return string
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
        $query = sprintf(self::INSERT_COMMAND, $tableName, implode(',', $columns), implode(',', $paramNames));
        try {
            $stmt = $db->prepare($query);
            $stmt->execute($params);
            return $db->lastInsertId();
        } catch (\PDOException $ex) {
            throw $ex;
        }
    }

    /**
     * @param $tableName
     * @param array $columns
     * @return array
     * @throws \Exception
     */
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

    /**
     * @param $tableName
     * @param $field
     * @param $value
     * @return array
     * @throws \Exception
     */
    public function getByField($tableName, $field, $value)
    {
        $db = self::getConnection();
        $query = sprintf(self::GET_BY_FIELD_COMMAND, $tableName, $field, ":$field");
        try {
            $stmt = $db->prepare($query);
            $stmt->bindValue(":$field", $value);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\PDOException $ex) {
            throw $ex;
        }
    }
}