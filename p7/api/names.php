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
    $limit = sanitize((int) ($_GET['limit'] ?? 10));
    
    $query  = 'SELECT b.ID, a.NAME, b.GENDER, b.VOTES ';
    $query .= 'FROM BABYNAMES AS a ';
    $query .= 'JOIN BABYNAME_VOTES AS b ';

    // Instead of determining when to use the where clause, use "WHERE 1"
    // and use "AND" to add additional conditions when needed.
    $query .= 'ON a.ID = b.NAME_ID WHERE 1';

    $sorting = 'ORDER BY b.VOTES DESC, a.NAME ASC LIMIT :limit';
    $params = ['limit' => [$limit, PDO::PARAM_INT]];
    $constraints = '';

    if (isset($_GET['name'])) {
        $constraints .= ' AND a.NAME LIKE :baby_name';

        // Use a wildcard (%) to select names that are similar to what the
        // user requested. Useful for autocomplete functionality.
        $params['baby_name'] = [trim(sanitize($_GET['name'])) . '%', PDO::PARAM_STR];
    }

    // Build the final query based on query parameters.
    if ($union) {
        $query = sprintf('(%s)', implode(
            ') UNION ALL (',

            array_map(
                function ($gender) use ($query, $constraints, $sorting) {
                    return sprintf(
                        '%s%s AND b.GENDER = "%s" %s',
                        $query,
                        $constraints,
                        $gender,
                        $sorting
                    );
                },

                ['M', 'F']
            )
        ));
    } else {
        $constraints .= ' AND b.GENDER = :baby_gender';
        $params['baby_gender'] = [trim(sanitize($_GET['gender'])), PDO::PARAM_STR];

        $query .= $constraints . ' ' . $sorting;
    }
    
    $sth = $dbh->prepare($query);

    // Binding values to prepared statements mitigates SQL injection.
    foreach ($params as $param => list($value, $type)) {
        echo '<p>[debug] binding param "' . $param . '" of type "' . $type . '" with value "'. $value . '"</p>' . PHP_EOL;
        $sth->bindValue(':' . $param, $value, $type);
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
