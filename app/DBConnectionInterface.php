<?php


namespace App;


interface DBConnectionInterface
{
    const TYPE_STRING = 1;
    const TYPE_NUMBER = 2;

    const DOMAIN_TABLE_NAME = 'domains';
    const DOMAIN_FIELD_NAME = 'domain';
    const HITS_FIELD_NAME = 'hits';
    const UNIQUE_USER_FIELD_NAME = 'unique_users';
    const CREATE_TABLE_COMMAND = "CREATE TABLE IF NOT EXISTS %s(id MEDIUMINT NOT NULL AUTO_INCREMENT, domain VARCHAR (255) NOT NULL, hits INTEGER NOT NULL, unique_users INTEGER NOT NULL, PRIMARY KEY (id))";
    const QUERY_TABLE_COMMAND = "SELECT %s FROM %s";
    const GET_BY_DOMAIN_COMMAND = 'SELECT * FROM %s WHERE %s = "%s"';
    const INSERT_COMMAND = 'INSERT INTO %s (%s) VALUES (%s)';
    /**
     * @return \PDO
     */
    public static function getConnection();

    public function createTable($tableName);

    public function update($tableName, array $data, $field, $value, $type = self::TYPE_STRING);

    public function insert($tableName, array $data);

    public function query($tableName, array $columns);

    public function getByField($tableName, $field, $value, $type = self::TYPE_STRING);

    public function increaseFieldBy($tableName, $increasedField, $conditionField, $conditionValue, $type = self::TYPE_STRING);
}