<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Game Over</title>
    <link rel="stylesheet" href="part6.css">
</head>
<body>
    <audio autoplay loop>
        <source src="audio/stranded-intro.mp3" type="audio/mp3">
    </audio>

    <div class="container ending">
        <img id="skelton" src="images/skeleton.gif" alt="game over">
        <h2>Time Ran Out</h2>
        <div id="text-box">
            <p>The helicopter flies past without noticing you.</p>
            <p>Years pass... your body turns to bones in the wilderness.</p>
            <div class="choices">
                <a href="reset.php" class="button">Game Over</a>
            </div>
        </div>
    </div>
</body>
</html>