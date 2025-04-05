<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>So Close...</title>
    <link rel="stylesheet" href="part6.css">
</head>
<body>

    <div class="container ending">
        <?php if (isset($_GET['signal']) && $_GET['signal'] == 'one'): ?>
            <audio autoplay loop>
                <source src="audio/stranded-intro.mp3" type="audio/mp3">
            </audio>
            <!-- wrong signal scene -->
            <img id="handbook" src="images/handbook.jpg" alt="handbook">
            <h2>They Didn't Understand</h2>
            <div id="text-box">
                <p>The helicopter circles but flies away - raising one arm made them think you were okay.</p>
                <p>You look at the survival handbook again and realize your mistake... rescue won't be back.</p>
                <div class="choices">
                    <a href="?action=skeleton" class="button">Keep going with your life</a>
                </div>
            </div>

        <?php elseif (isset($_GET['action']) && $_GET['action'] == 'skeleton'): ?>
            <audio autoplay loop>
                <source src="audio/stranded-intro.mp3" type="audio/mp3">
            </audio>
            <!-- skeleton scene -->
            <img id="skelton" src="images/skeleton.gif" alt="skeleton remains">
            <div id="text-box">
                <p>Years pass... your body turns to bones in the wilderness.</p>
                <div class="choices">
                    <a href="homepage.html" class="button">Game Over</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>