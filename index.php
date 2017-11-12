<?php
/**
 * Created by PhpStorm.
 * User: giang
 * Date: 11/12/17
 * Time: 9:21 PM
 */

use App\SQLiteConnection;

require 'vendor/autoload.php';

$pdo = (new SQLiteConnection())->connect();
if ($pdo != null)
    echo 'Connected to the SQLite database successfully!';
else
    echo 'Whoops, could not connect to the SQLite database!';