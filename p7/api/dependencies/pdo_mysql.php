<?php

/**
 * Create a new instance of a PDO object using the MySQL driver.
 *
 * Normally it's bad practice to have credentials stored in source code.
 * Environemnt variables or readonly files are a better place to put them.
 * 
 * I tried several solutions but unfortunately the apache account used to run
 * PHP can't access my files outside of public_html. At first I created a
 * file in my home directory with my username and password. Tried to chmod 
 * it but I guess student accounts aren't allowed to do so.
 * 
 * The next best thing, I guess, would be to store the credentials in their
 * own PHP file but ignore it from source control.
 */

list($username, $password) = require __DIR__ . '/credentials.php';

$dbh = new PDO(
    'mysql:host=lamp.cse.fau.edu;port=3306;charset=utf8;dbname=tgalinis2020;',
    $username,
    $password
);

$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

return $dbh;
