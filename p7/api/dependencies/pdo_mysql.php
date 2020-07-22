<?php

/**
 * Create a new instance of a PDO object using the MySQL driver.
 * 
 * Normally it's bad practice to store credentials directly within source code,
 * so I put them in a hidden file in my home directory.
 */
list($user, $pass) = explode(':', file_get_contents('/home/tgalinis2020/.dblogin'));

$dbh = new PDO(
    'mysql:host=lamp.cse.fau.edu;port=3306;charset=utf8;dbname=tgalinis2020;',
    $user, trim($pass) // trim trailing newline
);

$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

return $dbh;
