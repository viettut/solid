<?php

use App\Config;
use App\DBManager;
use App\MySQL\MySQLConnection;
require 'vendor/autoload.php';

$dbManager = new DBManager(new MySQLConnection());

$host = $_SERVER['HTTP_HOST'];
$domains = $dbManager->getByDomain($host);
$hit = 0;
if (!empty($domains)) {
    $domain = $domains[0];
    $hit = array_key_exists(Config::HITS_FIELD_NAME, $domain) ? $domain[Config::HITS_FIELD_NAME] : 0;
}

header ('Content-Type: image/png');
$im = @imagecreatetruecolor(190, 40) or die('Cannot Initialize new GD image stream');
$text_color = imagecolorallocate($im, 233, 14, 91);
imagestring($im, 5, 10, 10, sprintf('Number of hits: %d', $hit), $text_color);
imagepng($im);