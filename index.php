<?php

$projects = [[
    'title' => 'Home Page',
    'url' => '/p1/index.html',
], [
    'title' => 'Web Page',
    'url' => '/p2/index.html',
]];

?><!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
    <title>Thomas Galinis' Projects</title>
    <link rel="stylesheet" type="text/css"
            href="vendor/bootstrap-4.5.0-dist/css/bootstrap.min.css">
    <script type="text/javascript"
            src="vendor/jquery-3.5.1-dist/jquery-3.5.1.min.js"></script>
    <script type="text/javascript"
            src="vendor/bootstrap-4.5.0-dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container my-5">
        <h1>Thomas Galinis' Projects</h1>

        <hr>

        <ul>
            <?php foreach ($projects as $project): ?>
                <li><a href="<?= __DIR__ . $project['url'] ?>"><?= $project['title'] ?></a></li>
            <?php endforeach ?>
        </ul>
    </div>
</body>
</html>

