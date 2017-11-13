<?php

use App\DBConnectionInterface;
use App\DBManager;
use App\MySQL\MySQLConnection;
use App\SQLite\SQLiteConnection;

require 'vendor/autoload.php';

$host = 'test.dev';
$dbManager = new DBManager(new SQLiteConnection());
$dbManager->createTable();

$data = array(
    DBConnectionInterface::DOMAIN_FIELD_NAME => $host,
    DBConnectionInterface::HITS_FIELD_NAME => 1,
    DBConnectionInterface::UNIQUE_USER_FIELD_NAME => 1
);

$dbManager->insertNewDomain($data);

$exist = $dbManager->checkDomainExist($host);
var_dump($exist);
$dbManager->increaseHits($host);
$dbManager->increaseUniqueUser($host);
$domain  = $dbManager->getByDomain($host);
var_dump($domain);
