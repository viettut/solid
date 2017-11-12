<?php
/**
 * Created by PhpStorm.
 * User: giang
 * Date: 11/12/17
 * Time: 8:44 PM
 */

header ('Content-Type: image/png');
$im = @imagecreatetruecolor(120, 40)
or die('Cannot Initialize new GD image stream');
$text_color = imagecolorallocate($im, 233, 14, 91);
imagestring($im, 1, 5, 5, sprintf('Number of hits: %d', 25), $text_color);
imagepng($im);
imagedestroy($im);