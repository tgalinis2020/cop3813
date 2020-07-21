<?php

require __DIR__ . '/common/constants.php';

// Load content from external JSON file
$content = json_decode(file_get_contents(__DIR__ . '/projects.json'), true);

?><!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
    <title>Thomas Galinis' Projects</title>
    <link rel="stylesheet" type="text/css"
            href="<?= BASEDIR . '/vendor/bootstrap-4.5.0-dist/css/bootstrap.min.css' ?>">
    <link rel="stylesheet" type="text/css"
            href="<?= BASEDIR . '/p8/styles/main.css' ?>">
</head>

<body>
    <header>
        <img class="header__background"
                src="<?= BASEDIR . '/p8/assets/jquery-minified.png' ?>"
                alt="Picture of code">

        <div class="header__foreground container">
            <h1>Thomas Galinis' Projects</h1>
            <h5>Check out what I've worked on.</h5>
        </div>
    </header>

    <main>
        <div class="container my-5">
            <div class="row">
                <?php foreach ($content['projects'] as $p): ?>
                    <div class="col-12 col-md-6 col-lg-4 col-xl-3 my-2">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-tile"><?= $p['title'] ?></h5>
                                <h6 class="card-subtitle mb-2">Project <?= $p['id'] ?></h6>
                                <p class="card-text"><?= $p['description'] ?></p>
                            </div>

                            <div class="card-footer">
                                <a href="<?= BASEDIR . $p['url'] ?>"
                                        class="btn w-100">View Page</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
    </main>


    <footer>
        <div class="container">
            <p class="text-center text-muted">&copy; 2020 Thomas Galinis</p>
        </div>
    </footer>
</body>
</html>

