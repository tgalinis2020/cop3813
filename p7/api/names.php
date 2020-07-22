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
    
    // Instead of determining when to use the where clause, use "WHERE 1"
    // and use "AND" to add additional conditions when needed.
    $query = <<<QUERY
SELECT a.ID, a.NAME, b.GENDER, b.VOTES
FROM BABYNAMES AS a
JOIN BABYNAME_VOTES AS b
ON a.ID = b.NAME_ID
WHERE 1
QUERY;
    $constraints = '';
    $sort = 'ORDER BY b.VOTES DESC, a.NAME ASC LIMIT ' . $limit;
    $params = [];

    // If gender is provided, no need to make a union
    if (!$union) {
        $constraints .= ' AND b.GENDER = :baby_gender';
        $params[':baby_gender'] = sanitize($_GET['gender']);
    }

    if (isset($_GET['name'])) {
        $constraints .= ' AND a.NAME LIKE :baby_name';
        $params[':baby_name'] = sanitize($_GET['name']) . '%';
    }
    
    $sth = $dbh->prepare($union
        // This lovely mess generates a union of the top $limit boy names
        // and top $limit girl names.
        ? sprintf('(%s)', implode(') UNION ALL (', array_map(function ($gender) use ($query, $constraints, $sort) {
            return sprintf(
                '%s%s AND b.GENDER = "%s" %s',
                $query,
                $constraints,
                $gender,
                $sort
            );
        }, ['M', 'F'])))

        // If no union is required, the query is much simpler.
        : $query . $constraints . ' ' . $sort
    );

    // Binding values to prepared statements mitigates SQL injection.
    foreach ($params as $i => $param) {
        $sth->bindValue($i+1, $param);
    }

    $sth->execute();

    $data = array_map(function ($rec) {
        return [
            'id' => $rec['ID'],
            'name' => $rec['NAME'],
            'gender' => $rec['GENDER'],
            'votes' => (int) $rec['VOTES'],
        ];
    } , $sth->fetchAll());

    header('HTTP/1.1 200 OK', true, 200);
    header('Content-type: application/vnd.api+json');

    echo json_encode(['data' => $data]);
}
