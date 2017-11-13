<?php

use App\DBConnectionInterface;
use App\DBManager;
use App\MySQL\MySQLConnection;

require 'vendor/autoload.php';

$dbManager = new DBManager(new MySQLConnection());
$dbManager->createTable();

$host = $_SERVER['HTTP_HOST'];
if ($dbManager->checkDomainExist($host)) {
    $dbManager->increaseHits($host);
} else {
    $data = array(
        DBConnectionInterface::DOMAIN_FIELD_NAME => $host,
        DBConnectionInterface::HITS_FIELD_NAME => 1,
        DBConnectionInterface::UNIQUE_USER_FIELD_NAME => 1
    );

    $dbManager->insertNewDomain($data);
}

if (!array_key_exists('user_unique_id', $_COOKIE)) {
    //new user -> increase unique_users
    setcookie("user_unique_id", uniqid('user', true), time() + 86400); //cookie expire in one day
    $dbManager->increaseUniqueUser($host);
}

$domains = $dbManager->getAll();
?>

<html>
<head>
    <title>Statistic page</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <h2>Domain statistic</h2>
    <p>The .table-bordered class adds borders to a table:</p>
    <table class="table table-bordered table-hover">
        <thead>
        <tr>
            <th>Id</th>
            <th>Domain</th>
            <th>Hits</th>
            <th>Unique Users</th>
        </tr>
        </thead>
        <tbody>
        <?php
            foreach ($domains as $domain)
            {
                print "<tr><td>" . $domain['id'] . "</td>";
                print "<td>" . $domain['domain'] . "</td>";
                print "<td>" . $domain['hits'] . "</td>";
                print "<td>" . $domain['unique_users'] . "</td></tr>";
            }
        ?>
        </tbody>
    </table>
    <hr/>

    <h2>Banner Ads</h2>
    <img src="/banner.php"/>
</div>
</body>
</html>
