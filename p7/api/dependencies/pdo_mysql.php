<?php

/**
 * Create a new instance of a PDO object using the MySQL driver.
 */
$dbh = new PDO('mysql:host=lamp.cse.fau.edu;port=3306;charset=utf8;dbname=tgalinis2020;');

$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

return $dbh;
