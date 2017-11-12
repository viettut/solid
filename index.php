<?php
/**
 * Created by PhpStorm.
 * User: giang
 * Date: 11/12/17
 * Time: 9:21 PM
 */

namespace App;

class Config {
    /**
     * path to the sqlite file
     */
    const PATH_TO_SQLITE_FILE = 'db/phpsqlite.db';

}

/**
 * SQLite connnection
 */
class SQLiteConnection {
    /**
     * PDO instance
     * @var type
     */
    private $pdo;

    /**
     * return in instance of the PDO object that connects to the SQLite database
     * @return \PDO
     */
    public function connect() {
        if ($this->pdo == null) {
            $this->pdo = new \PDO("sqlite:" . Config::PATH_TO_SQLITE_FILE);
        }
        return $this->pdo;
    }
}

/**
 * SQLite Create Table Demo
 */
class SQLiteCreateTable {

    /**
     * PDO object
     * @var \PDO
     */
    private $pdo;

    /**
     * connect to the SQLite database
     */
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * create tables
     */
    public function createTable() {
        $command ='CREATE TABLE IF NOT EXISTS domains (
                    id INTEGER PRIMARY KEY,
                    domain  VARCHAR (255) NOT NULL,
                    hits  INTEGER NOT NULL,
                    unique_users  INTEGER NOT NULL
                    ';
        
        $this->pdo->exec($command);
    }

    /**
     * get the table list in the database
     */
    public function getTableList() {

        $stmt = $this->pdo->query("SELECT name
                                   FROM sqlite_master
                                   WHERE type = 'table'
                                   ORDER BY name");
        $tables = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $tables[] = $row['name'];
        }

        return $tables;
    }

}

$pdo = (new SQLiteConnection())->connect();
if ($pdo != null)
    echo 'Connected to the SQLite database successfully!';
else
    echo 'Whoops, could not connect to the SQLite database!';