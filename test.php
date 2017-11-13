<?php

use App\DBConnectionInterface;
use App\DBManager;
use App\MySQL\MySQLConnection;
use App\SQLite\SQLiteConnection;

require 'vendor/autoload.php';

$dbManager = new DBManager(new SQLiteConnection());
$dbManager->createTable();

$data = array(
    DBConnectionInterface::DOMAIN_FIELD_NAME => 'test.dev',
    DBConnectionInterface::HITS_FIELD_NAME => 1,
    DBConnectionInterface::UNIQUE_USER_FIELD_NAME => 1
);

//$dbManager->insertNewDomain($data);

$domain  = $dbManager->getByDomain('test.dev');
var_dump($domain);

