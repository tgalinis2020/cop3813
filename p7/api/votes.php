<?php

/**
 * This endpoint is responsable for adding baby name votes.
 */

require __DIR__ . '/../../common/sanitize.php';

$dbh = require __DIR__ . '/dependencies/pdo_mysql.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sth = false;

    if (isset($_POST['name'], $_POST['gender'])) {
        $query  = 'UPDATE BABYNAME_VOTES SET VOTES = VOTES+1 WHERE NAME_ID = (';
        $query .= 'SELECT ID FROM BABYNAMES WHERE NAME = :baby_name';
        $query .= ') AND GENDER = :baby_gender';

        $sth = $dbh->prepare($query);
        $sth->bindValue(':baby_name', sanitize(ucfirst($_POST['name'])));
        $sth->bindValue(':baby_gender', sanitize(strtoupper($_POST['gender'])));
        $sth->execute();
    }

    header('Content-type: application/vnd.api+json');

    if ($sth && $sth->rowCount() == 1) {
        header('HTTP/1.1 201 Created', true, 201);
    } else {
        header('HTTP/1.1 406 Not Acceptable', true, 206);
    }
}

