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

    // Parameters to bind before executing query. The limit will always
    // be present one way or another, so start with that.
    $params = ['limit' => [sanitize((int) ($_GET['limit'] ?? 10)), PDO::PARAM_INT]];
    
    $query  = 'SELECT b.ID, a.NAME, b.GENDER, b.VOTES ';
    $query .= 'FROM BABYNAMES AS a ';
    $query .= 'JOIN BABYNAME_VOTES AS b ';
    $query .= 'ON a.ID = b.NAME_ID WHERE b.GENDER = :baby_gender ';

    if (isset($_GET['name'])) {
        $query .= 'AND a.NAME LIKE :baby_name ';

        // Use a wildcard (%) to select names that are similar to what the
        // user requested. Useful for autocomplete functionality.
        $params['baby_name'] = [trim(sanitize($_GET['name'])) . '%', PDO::PARAM_STR];
    }

    $query .= 'ORDER BY b.VOTES DESC, a.NAME ASC LIMIT :limit';
    $data = [];

    echo $query . PHP_EOL . PHP_EOL;
    
    $sth = $dbh->prepare($query);

    $genders = isset($_GET['gender'])
        ? [strtoupper(trim(sanitize($_GET['gender'])))]
        : ['M', 'F'];

    foreach ($genders as $gender) {
        $params['baby_gender'] = $gender;

        // Binding values to prepared statements mitigates SQL injection.
        foreach ($params as $param => list($value, $type)) {
            $sth->bindValue(':' . $param, $value, $type);
        }
        
        $sth->execute();

        $res = $sth->fetchAll();

        var_dump($res);

        $data = array_merge($data, $res);

    }

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
        
            $data
        )
    ]);
}
