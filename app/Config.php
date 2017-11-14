<?php
/**
 * Created by PhpStorm.
 * User: giang
 * Date: 11/13/17
 * Time: 10:18 PM
 */

namespace App;


class Config
{
    const MYSQL_USERNAME = 'tagcadedev';
    const MYSQL_PASSWORD = 'tagcadedev';
    const MYSQL_DB = 'solid';
    const MYSQL_HOST = 'localhost';
    const MYSQL_CREATE_TABLE_COMMAND = "CREATE TABLE IF NOT EXISTS %s(id MEDIUMINT NOT NULL AUTO_INCREMENT, domain VARCHAR (255) NOT NULL, hits INTEGER NOT NULL, unique_users INTEGER NOT NULL, PRIMARY KEY (id))";
    
    const SQLITE3_DB_FILE = 'db/phpsqlite.db';
    const SQLITE3_CREATE_TABLE_COMMAND = "CREATE TABLE IF NOT EXISTS %s(id INTEGER PRIMARY KEY, domain VARCHAR (255) NOT NULL, hits INTEGER NOT NULL, unique_users INTEGER NOT NULL)";

    const DOMAIN_TABLE_NAME = 'domains';
    const DOMAIN_FIELD_NAME = 'domain';
    const HITS_FIELD_NAME = 'hits';
    const UNIQUE_USER_FIELD_NAME = 'unique_users';

    const QUERY_TABLE_COMMAND = "SELECT %s FROM %s";
    const GET_BY_FIELD_COMMAND = 'SELECT * FROM %s WHERE %s = %s';
    const INSERT_COMMAND = 'INSERT INTO %s (%s) VALUES (%s)';
    const UPDATE_COMMAND = 'UPDATE %s SET %s WHERE %s = %s';
}