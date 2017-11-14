<?php

use App\Config;
use App\DBManager;
use App\SQLite\SQLiteConnection;

require 'vendor/autoload.php';

$host = 'test.dev';
$dbManager = new DBManager(new SQLiteConnection());
$dbManager->createTable();

$data = array(
    Config::DOMAIN_FIELD_NAME => $host,
    Config::HITS_FIELD_NAME => 1,
    Config::UNIQUE_USER_FIELD_NAME => 1
);

$dbManager->insertNewDomain($data);

$exist = $dbManager->checkDomainExist($host);
var_dump($exist);
$dbManager->increaseHits($host);
$dbManager->increaseUniqueUser($host);
$domain  = $dbManager->getByDomain($host);
var_dump($domain);
