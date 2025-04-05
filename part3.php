<?php
include('health.php');

if(isset($_POST['reset'])) {
    $_SESSION['part3_stage'] = 'find_shelter';
    resetHealth();
}

if(!isset($_SESSION['part3_stage'])) {
    $_SESSION['part3_stage'] = 'find_shelter';
}

if(isset($_POST['action'])) {
    $action = $_POST['action'];

    // If the player is in the 'find_shelter' stage
    if ($_SESSION['part3_stage'] == 'find_shelter') {
        if ($action == 'stay_near_wreckage') {
            decreaseHealth(20); // Health decreases if staying near the wreckage
            $_SESSION['part3_stage'] = 'shelter_consequence';
            $_SESSION['choice_made'] = 'stay_near_wreckage';
        } elseif ($action == 'explore_nearby') {
            $_SESSION['part3_stage'] = 'shelter_consequence';
            $_SESSION['choice_made'] = 'explore_nearby';
        }
    }
    // If the player is in the 'shelter_consequence' stage
    elseif ($_SESSION['part3_stage'] == 'shelter_consequence' && $action == 'continue') {
        $_SESSION['part3_stage'] = 'part4_start';
    }
    elseif($_SESSION['part3_stage'] == 'part4_start' && $action == 'part4_start') {
        header("Location: part4.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Day 1</title>
    <link rel="stylesheet" href="part2and3.css">
</head>
<body>

    <div class="container">
        <?php displayHealthBar(); ?>
        <div class="day-indicator">Day 1</div>
        <h1>Find Shelter</h1>

        <?php if ($_SESSION['part3_stage'] == 'find_shelter'): ?>
            <audio autoplay loop>
                <source src="audio/wind.mp3" type="audio/mp3">
                        
            </audio>

            <h2>Night is Approaching...</h2>
            <p>It's getting cold. You must find shelter.</p>
            <img src="images/nightapproaching.gif" class="scene-img">
            <form method="post">
                <button type="submit" name="action" value="stay_near_wreckage" class="choice-btn">Stay near the wreckage</button>
                <button type="submit" name="action" value="explore_nearby" class="choice-btn">Explore nearby</button>
            </form>

        <?php elseif ($_SESSION['part3_stage'] == 'shelter_consequence'): ?>


            <h2>You're Safe for Now</h2>
            <?php if ($_SESSION['choice_made'] == 'stay_near_wreckage'): ?>
                <audio autoplay loop>
                    <source src="audio/stranded-intro.mp3" type="audio/mp3">
                </audio>
                <div class="consequence-box negative">
                    <h3>You stayed near the wreckage</h3>
                    <p>The cold was unbearable, and you didn't sleep well. Your health decreased due to the exposure to the cold night.</p>
                    <img src="images/coldnight.gif" class="scene-img">
                    <p><strong>Health decreased by 20%</strong></p>
                </div>
                
            <?php elseif ($_SESSION['choice_made'] == 'explore_nearby'): ?>
                <audio autoplay loop>
                    <source src="audio/stranded-intro.mp3" type="audio/mp3">
                </audio>
                <div class="consequence-box positive">
                    <h3>You explored and found shelter</h3>
                    <p>You explored the surroundings and found a cave to shelter in. The night was much warmer, and you managed to rest well, keeping your health intact.</p>
                    <img src="images/cave.gif" class="scene-img">
                    <p><strong>Health remains unchanged</strong></p>
                </div>
            <?php endif; ?>

            <form method="post" action="part4.php">
                <input type="hidden" name="from_part3" value="true">
                <input type="hidden" name="current_health" value="<?php echo $_SESSION['health']; ?>">

                <button type="submit" name="action" value="continue" class="choice-btn">Sleep</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>