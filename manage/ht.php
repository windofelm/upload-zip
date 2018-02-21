<?php

$fileName = "../site/.htaccess";
//$htaccess = file($fileName);
//$stringContent = "";
//echo '<pre>';
//foreach ($htaccess as $line) {
//    echo $line;
//    $stringContent .= $line;
//}
//echo '</pre>';

$stringContent = "
RewriteEngine On \n
RewriteRule ^language-system-test-x/public/(.*)$ /$1 [R=301,NC,L] \n";

file_put_contents($fileName, $stringContent);

