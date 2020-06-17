<?php

error_reporting(E_ALL);

// Load content from external JSON file
$content = json_decode(file_get_contents('projects.json'), true);

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


        <div class="row my-5">

        <?php /*
            Using a foreach loop to show all projects.

            To put 4 cards in a row, the current row needs to
            end and a new one begins when the current index
            is divisible by 3. Not including the case when
            index = 0 since I started the first row above this
            comment.
        */ ?>

        <?php foreach ($content['projects'] as $index => $p): ?>

        <?php if ($index > 0 && $index % 3 === 0): ?>

        </div>

        <div class="row my-5">

        <?php endif ?>

            <div class="col-12 col-sm-6 col-lg-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-tile"><?= sprintf("Project %d: %s", $p['id'], $p['title']) ?></h5>
                        <p class="card-text"><?= $p['description'] ?></p>
                    </div>

                    <div class="card-footer">
                        <a href="<?= $p['url'] ?>" class="btn btn-primary w-100">View</a>
                    </div>
                </div>
            </div>

        <?php endforeach ?>

        </div>
    </div>
</body>
</html>

