<?php

/**
 * Create a new instance of a PDO object using the MySQL driver.
 *
 * Normally it's bad practice to have credentials stored in source code,
 * but it should be harmless given this is an academic project.
 */
$dbh = new PDO(
    'mysql:host=lamp.cse.fau.edu;port=3306;charset=utf8;dbname=tgalinis2020;',
    'tgalinis2020',
    'c2o74ZGyoo'
);

$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

return $dbh;
