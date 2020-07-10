<?php

// Don't allow users to run this script directly
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
    header('Location: index.php');
    die;
}

// Some useful constants
const B_SHOW_GENERATED_NUMBER = false;
const MAX_ATTEMPTS = 5;
const MIN = 1;
const MAX = 10;

// Show the user a message depending on their input
$feedback = null;

// Form validity status
$valid = true;

$inputClasses = ['form-control'];

// Use PHP sessions to keep track of the random number and number of attempts
session_start();

// Reset the game if the session has not been set, if the game is over or is reset explicitly
if (!isset($_SESSION['gameover']) || $_SESSION['gameover'] || isset($_GET['reset'])) {
    $_SESSION['number'] = rand(MIN, MAX);
    $_SESSION['attempts'] = 0;
    $_SESSION['gameover'] = false;

} elseif ($_SERVER["REQUEST_METHOD"] === "POST") {
    $guess = (int) $_POST["guess"];

    if (empty($_POST['guess']) || $guess < MIN || $guess > MAX) {
        $valid = false;
        $inputClasses[] = 'is-invalid';

    } else {
        $valid = true;
        $_SESSION['attempts'] += 1;
        $randNum = $_SESSION['number'];

        // Spaceship operator returns...
        //   -1 if guess < randNum
        //    0 if guess === randNum
        //    1 if guess > randNum
        $lowOrHigh = $guess <=> $randNum;
            
        if ($lowOrHigh === 0) {
            $_SESSION['gameover'] = true;

            $feedback = [
                'style' => 'success',
                'title' => 'Well done!',
                'message' => 'You got it!',
            ];

        } else {
            $feedback = ['style' => 'danger'];

            if ($_SESSION['attempts'] === MAX_ATTEMPTS) {
                $_SESSION['gameover'] = true;
                $feedback['title'] = 'You are out of attempts!';
                $feedback['message'] = sprintf(
                    'I was thinking of %d. Better luck next time!',
                    $_SESSION['number']
                );
                
            } else {
                if ($lowOrHigh === -1) {
                    $feedback['title'] = 'Too low!';
                    $feedback['message'] = 'Try a larger number.';
                } else {
                    $feedback['title'] = 'Too high!';
                    $feedback['message'] = 'Try a smaller number.';
                }
            }
        }
    }
}
