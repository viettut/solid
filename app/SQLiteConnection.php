<?php
/**
 * Created by PhpStorm.
 * User: giang
 * Date: 11/12/17
 * Time: 9:57 PM
 */

namespace App;


use PDOExecption;

class SQLiteConnection implements DBConnectionInterface
{
    const PATH_TO_SQLITE_FILE = 'db/phpsqlite.db';

    /**
     * PDO instance
     * @var \PDO
     */
    private static $pdo;

    public static function getConnection()
    {
        if (self::$pdo == null) {
            self::$pdo = new \PDO("sqlite:" . self::PATH_TO_SQLITE_FILE);
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
        } catch (PDOExecption $ex) {
            throw new \Exception('Can not create table, check the command syntax !');
        }
    }

    public function update($tableName, array $columns, array $data)
    {
        // TODO: Implement update() method.
    }

    public function insert($tableName, array $data)
    {
        $db = self::getConnection();
        $query = "INSERT INTO %s(%s) VALUES(%s)";
        $columns = array_keys($data);
//        $paramNames = array_map(function($column) {
//            return ":$column";
//        }, $columns);

        $values = array_values($data);
//        $params = array_combine($paramNames, $values);
        $query = sprintf($query, $tableName, implode(',', $columns), implode(',', $values));
//        $query = 'INSERT INTO domains (domain, hits, unique_users) VALUES (?,?,?)';
        try {
//            $stmt = $db->prepare($query);
//            $stmt->execute($params);
            $db->exec($query);
            return $db->lastInsertId();
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function query($tableName, array $columns)
    {
        $db = self::getConnection();
        $query = sprintf(self::QUERY_TABLE_COMMAND, implode(',', $columns), $tableName);
//        $stmt = $db->query(sprintf(self::QUERY_TABLE_COMMAND, implode(',', $columns), $tableName));
        $stmt = $db->query('SELECT * from domains');
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

    public function checkDomainExist($tableName, $domain)
    {
        $stmt = self::$pdo->query(sprintf(self::GET_BY_DOMAIN_COMMAND, $tableName, $domain));
        $result = $stmt->fetchAll();

        return count($result) > 0;
    }
}