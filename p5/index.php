<?php require __DIR__ . '/index.controller.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Guess a Number</title>
    <link rel="stylesheet" type="text/css"
            href="../vendor/bootstrap-4.5.0-dist/css/bootstrap.min.css">
</head>

<body>
    <main>
        <div class="container my-5">
            <h1 class="mb-4">Guess a Number!</h1>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="../">Portal</a></li>
                <li class="breadcrumb-item active">PHP</li>
            </ul>

            <?php if (!empty($feedback)): ?>
                <div class="alert <?= 'alert-' . $feedback['style'] ?>" role="alert">
                    <strong><?= $feedback['title'] ?></strong> <?= $feedback['message'] ?>
                </div>
            <?php endif ?>

            <?php if ($_SESSION['gameover']): ?>
                <a class="btn btn-primary" href="<?= $_SERVER['PHP_SELF'] ?>">Play Again</a>
            <?php else: ?>
                <form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>">
                    <p>
                        I'm thinking of a number between <?= MIN ?> and <?= MAX ?>.
                        You have <?= MAX_ATTEMPTS - $_SESSION['attempts'] ?> attempts.
                    </p>

                    <?php if (B_SHOW_GENERATED_NUMBER): ?>
                        <p><strong>Spoilers:</strong> it's <?= $_SESSION['number'] ?>.</p>
                    <?php endif ?>

                    <div class="my-2">
                        <label for="guess">Your guess?</label>
                        <input class="<?= implode(' ', $inputClasses) ?>"
                                type="text" id="guess" name="guess" autofocus>
                        <div class="invalid-feedback">
                            Please enter a number between <?= MIN ?> and <?= MAX ?>!
                        </div>
                    </div>

                    <input type="submit" value="Guess" class="btn btn-primary">
                    <a class="btn btn-danger" href="?reset">Reset</a>
                </form>
            <?php endif ?>
        </div>
    </main>

    <footer>
        <div class="container">
            <p class="text-center text-muted">&copy; 2020 Thomas Galinis</p>
        </div>
    </footer>
</body>
</html>
