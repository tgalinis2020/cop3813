<?php

// Some useful constants
const B_SHOW_GENERATED_NUMBER = false;
const MAX_ATTEMPTS = 5;
const MIN = 1;
const MAX = 10;

// Show the user a message depending on their input
$feedback = null;

// Use PHP sessions to keep track of the random number and number of attempts
session_start();

// Reset the game if the session has not been set or is reset explicitly.
if (!isset($_SESSION['gameover']) || $_SESSION['gameover'] === true || isset($_GET['reset'])) {
    $_SESSION['number'] = rand(MIN, MAX);
    $_SESSION['attempts'] = 0;
    $_SESSION['gameover'] = false;

} elseif ($_SERVER["REQUEST_METHOD"] === "POST") {
    $_SESSION['attempts'] += 1;
    $randNum = $_SESSION['number'];
    $guess = (int) $_POST["guess"];
    $tooLow = $guess < $randNum;
    $tooHigh = $guess > $randNum;
        
    if ($tooLow || $tooHigh) {
        $feedback = ['style' => 'danger', 'title' => '', 'message' => ''];

        if ($_SESSION['attempts'] === MAX_ATTEMPTS) {
            $_SESSION['gameover'] = true;
            $feedback['title'] = 'You are out of attempts!';
            $feedback['message'] = sprintf(
                'I was thinking of %d. Better luck next time!',
                $_SESSION['number']
            );
        } else {
            if ($tooLow) {
                $feedback['title'] = 'Too low!';
                $feedback['message'] = 'Try a larger number.';
            } else {
                $feedback['title'] = 'Too high!';
                $feedback['message'] = 'Try a smaller number.';
            }
            
        }
    } else {
        $_SESSION['gameover'] = true;

        $feedback = [
            'style' => 'success',
            'title' => 'Well done!',
            'message' => 'You got it!',
        ];
    }
}

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Guess a Number</title>
    <link rel="stylesheet" type="text/css"
            href="../vendor/bootstrap-4.5.0-dist/css/bootstrap.min.css">
    <script type="text/javascript"
            src="../vendor/jquery-3.5.1-dist/jquery-3.5.1.min.js"></script>
    <script type="text/javascript"
            src="../vendor/bootstrap-4.5.0-dist/js/bootstrap.min.js"></script>
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
                <div class="alert alert-<?= $feedback['style'] ?>" role="alert">
                    <strong><?= $feedback['title'] ?></strong> <?= $feedback['message'] ?>

                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif ?>

            <?php if ($_SESSION['gameover']): ?>
                <a class="btn btn-primary" href="index.php">Play Again</a>
            <?php else: ?>
                <form method="POST" action="index.php">
                    <p>
                        I'm thinking of a number between <?= MIN ?> and <?= MAX ?>.
                        You have <?= MAX_ATTEMPTS - $_SESSION['attempts'] ?> attempts.
                    </p>

                    <?php if (B_SHOW_GENERATED_NUMBER): ?>
                        <p><strong>Spoilers:</strong> it's <?= $_SESSION['number'] ?>.</p>
                    <?php endif ?>

                    <p>
                        <label for="guess">Your guess?</label>
                        <input class="form-control" type="number"
                                id="guess" name="guess"
                                min="<?= MIN ?>" max="<?= MAX ?>" autofocus>
                    </p>

                    <input type="submit" name="submit" value="Guess" class="btn btn-primary">
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