<?php

/**
 * This endpoint is responsable for adding baby name votes.
 */

require __DIR__ . '/../../../common/sanitize.php';

$dbh = require __DIR__ . '/../dependencies/pdo_mysql.php';

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $limit = sanitize($_GET['limit']) ?? 5;
        $query = 'SELECT "ID", "NAME" FROM "BABYNAME_VOTES"';
        $params = [];

        if (isset($_GET['name'])) {
            $name = sanitize($_GET['name']);
            $query .= ' WHERE "NAME" LIKE ?';
            $params[] = $name . '%';
        }

        $query .= ' LIMIT ' . $limit;

        $sth = $dbh->prepare($query);

        foreach ($params as $i => $param) {
            $sth->bindParam($i+1, $param);
        }

        $sth->execute();

        $data = $sth->fetchAll();

        header('HTTP/1.1 200 OK', true, 200);
        header('Content-type: application/vnd.api+json');

        echo json_encode(['data' => $data]);
        break;

    case 'POST':

        break;
}