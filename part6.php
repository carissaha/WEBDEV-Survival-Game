<?php
session_start();
require_once 'engine.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Final Day - Rescue</title>
    <link rel="stylesheet" href="part6.css">
</head>
<body>
    <div class="container">
        <div class="day-indicator">Day 7</div>
        <h1>Survival Challenge: Day 7</h1>

        <?php if (!isset($_GET['action'])): ?>
            <img id="scene" src="images/sky.gif" alt="sky">
            <div id="text-box">
                <p>You lie on the ground, staring at the vast sky above after several days of no signs.</p>
                <div class="choices">
                    <a href="?action=turn" class="button">Turn head</a>
                </div>
            </div>

        <?php elseif ($_GET['action'] == 'turn' && !isset($_GET['signal'])): ?>
            <img id="scene" src="images/helicopter-sky.jpg" alt="helicopter">
            <div id="text-box">
                <p>You see something in the sky. Is it a bird? Superman? It's a HELICOPTER!</p>
                <div class="choices">
                    <a href="?action=stay" class="button">Stay lying down</a>
                    <a href="?action=get_up" class="button">Get up and run toward it</a>
                </div>
            </div>
        
        <?php elseif ($_GET['action'] == 'get_up' && !isset($_GET['signal'])): ?>
           
            <img id="scene" src="images/running.gif" alt="helicopter">
            <div id="text-box">
                <p>You muster all your strength and run toward the helicopter. It's getting closer!</p>
                <p>How will you signal for help?</p>
                <div class="choices">
                    <a href="?action=get_up&signal=one" class="button">Raise one arm</a>
                    <a href="?action=get_up&signal=both" class="button">Raise both arms</a>
                </div>
            </div>

        <?php endif; ?>
    </div>
</body>
</html>