<?php

/**
 * This endpoint is responsable for getting baby names from the database.
 */

require __DIR__ . '/../../common/sanitize.php';

$dbh = require __DIR__ . '/dependencies/pdo_mysql.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // If both genders are required, use a union to fetch the top $limit
    // baby names for both boys and girls.
    $union = !isset($_GET['gender']);
    $limit = sanitize($_GET['limit'] ?? 10);
    
    $query  = 'SELECT a.ID, a.NAME, b.GENDER, b.VOTES ';
    $query .= 'FROM BABYNAMES AS a ';
    $query .= 'JOIN BABYNAME_VOTES AS b ';

    // Instead of determining when to use the where clause, use "WHERE 1"
    // and use "AND" to add additional conditions when needed.
    $query .= 'ON a.ID = b.NAME_ID WHERE 1';

    $constraints = '';
    $sort = 'ORDER BY b.VOTES DESC, a.NAME ASC LIMIT ' . $limit;
    $params = [];

    if (isset($_GET['name'])) {
        $constraints .= ' AND a.NAME LIKE :baby_name';
        $params['baby_name'] = trim(sanitize($_GET['name'])) . '%';
    }

    // Build the final query based on query parameters.
    if ($union) {
        $query = sprintf('(%s)', implode(
            ') UNION ALL (',

            array_map(
                function ($gender) use ($query, $constraints, $sort) {
                    return sprintf(
                        '%s%s AND b.GENDER = "%s" %s',
                        $query,
                        $constraints,
                        $gender,
                        $sort
                    );
                },

                ['M', 'F']
            )
        ));
    } else {
        $constraints .= ' AND b.GENDER = :baby_gender';
        $params['baby_gender'] = trim(sanitize($_GET['gender']));

        $query .= $constraints . ' ' . $sort;
    }
    
    $sth = $dbh->prepare($query);

    // Binding values to prepared statements mitigates SQL injection.
    foreach ($params as $param => $value) {
        $sth->bindValue(':' . $param, $value);
    }

    $sth->execute();

    header('HTTP/1.1 200 OK', true, 200);
    header('Content-type: application/vnd.api+json');

    echo json_encode([
        'data' => array_map(
            function ($rec) {
                return [
                    'id' => $rec['ID'],
                    'name' => $rec['NAME'],
                    'gender' => $rec['GENDER'],
                    'votes' => (int) $rec['VOTES'],
                ];
            },
        
            $sth->fetchAll()
        )
    ]);
}
