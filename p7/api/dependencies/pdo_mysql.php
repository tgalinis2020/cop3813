<?php

/**
 * This immediately invoked function returns a new instance of a database
 * connection via PDO using the MySQL driver.
 * 
 * Normally it's bad practice to store credentials directly within source code,
 * so I put them in a hidden file in my home directory.
 */
return (function () {
    list($username, $password) = explode(
        '\n',
        file_get_contents('/home/tgalinis2020/.mysql_login')
    );

    $dbh = new PDO(
        'mysql:host=lamp.cse.fau.edu;port=3306;charset=utf8;dbname=baby_names_db;',
        $username, $password
    );

    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    return $dbh;
})();