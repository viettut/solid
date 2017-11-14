<?php


namespace App;


interface DBConnectionInterface
{
//    const DOMAIN_TABLE_NAME = 'domains';
//    const DOMAIN_FIELD_NAME = 'domain';
//    const HITS_FIELD_NAME = 'hits';
//    const UNIQUE_USER_FIELD_NAME = 'unique_users';
//
//    const QUERY_TABLE_COMMAND = "SELECT %s FROM %s";
//    const GET_BY_FIELD_COMMAND = 'SELECT * FROM %s WHERE %s = %s';
//    const INSERT_COMMAND = 'INSERT INTO %s (%s) VALUES (%s)';
    
    /**
     * @return \PDO|\SQLite3
     */
    public static function getConnection();

    /**
     * @param $tableName
     * @return mixed
     */
    public function createTable($tableName);

    /**
     * @param $tableName
     * @param array $data
     * @param $field
     * @param $value
     * @return mixed
     */
    public function update($tableName, array $data, $field, $value);

    /**
     * @param $tableName
     * @param array $data
     * @return mixed
     */
    public function insert($tableName, array $data);

    /**
     * @param $tableName
     * @param array $columns
     * @return mixed
     */
    public function query($tableName, array $columns);

    /**
     * @param $tableName
     * @param $field
     * @param $value
     * @return mixed
     */
    public function getByField($tableName, $field, $value);

    /**
     * @param $tableName
     * @param $increasedField
     * @param $conditionField
     * @param $conditionValue
     * @return mixed
     */
    public function increaseFieldBy($tableName, $increasedField, $conditionField, $conditionValue);
}