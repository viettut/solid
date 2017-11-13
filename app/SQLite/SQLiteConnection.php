<?php


namespace App\SQLite;


use App\DBConnectionInterface;
use SQLite3;

class SQLiteConnection implements DBConnectionInterface
{
    const PATH_TO_SQLITE_FILE = 'db/phpsqlite.db';
    const CREATE_TABLE_COMMAND = "CREATE TABLE IF NOT EXISTS %s(id INTEGER PRIMARY KEY, domain VARCHAR (255) NOT NULL, hits INTEGER NOT NULL, unique_users INTEGER NOT NULL)";

    private static $db;

    public static function getConnection()
    {
        if (self::$db == null) {
            self::$db = new SQLite3(self::PATH_TO_SQLITE_FILE);

            if (self::$db == null) {
                throw new \Exception('Can not connect to SQLite DB, check the configuration !');
            }
        }

        return self::$db;
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

    public function update($tableName, array $data, $field, $value, $type = self::TYPE_STRING)
    {
        // TODO: Implement update() method.
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
        // TODO: Implement query() method.
    }

    public function getByField($tableName, $field, $value, $type = self::TYPE_STRING)
    {
        $db = self::getConnection();
        $query = sprintf(self::GET_BY_DOMAIN_COMMAND, $tableName, $field, $value);
        try {
            $result = $db->query($query);
            $domains = [];
            while($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $domains[] = $row;
            }

            return $domains;
        } catch (\PDOException $ex) {
            throw $ex;
        }
    }

    public function increaseFieldBy($tableName, $increasedField, $conditionField, $conditionValue, $type = self::TYPE_STRING)
    {
        // TODO: Implement increaseFieldBy() method.
    }
}