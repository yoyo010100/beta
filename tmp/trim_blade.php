<?php
$f = 'c:/xampp/htdocs/Beta/resources/views/frontend/products.blade.php';
$c = file_get_contents($f);
$i = strpos($c, '</html>') + 7;
file_put_contents($f, substr($c, 0, $i));
echo "Done. Length: " . $i . "\n";
