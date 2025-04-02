<?php
include('inventorypt23.php');

if(isset($_POST['reset'])) {
    $_SESSION['part2_stage'] = 'day1_start'; 
    resetInventory();
}

if(!isset($_SESSION['part2_stage'])) {
    $_SESSION['part2_stage'] = 'day1_start';
}

if(isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action == 'look_in_compartment') {
        $_SESSION['part2_stage'] = 'compartment_search';
    } elseif ($action == 'take_med_kit') {
        $_SESSION['part2_stage'] = 'med_kit_taken';
    } elseif ($action == 'use_med_kit') {
        $_SESSION['part2_stage'] = 'copilot_healed';
    } elseif ($action == 'continue_story') {
        $_SESSION['part2_stage'] = 'end_of_part2';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Day 1</title>
    <link rel="stylesheet" href="part2and3.css">
</head>
<body>
    <div class="container">
        <?php displayInventory(); ?>
        <div class="day-indicator">Day 1</div>
        <h1>You Crashed</h1>
        
        <?php if($_SESSION['part2_stage'] == 'day1_start'): ?>
            <h2>Your Copilot is Injured</h2>
            <p>After a sudden crash landing, your copilot has been injured. It's up to you to help them.</p>
            
            <img src="images/crash.png" class="scene-img">
            
            <p>You notice a compartment in the back of the wreckage. Maybe it holds something useful. What will you do next?</p>
            
            <form method="post">
                <input type="hidden" name="action" value="look_in_compartment">
                <button type="submit" class="choice-btn">Look in the compartment</button>
            </form>

            
        <?php elseif ($_SESSION['part2_stage'] == 'compartment_search'): ?>
            <h2>Searching the Compartment</h2>
            <p>After looking through the compartment, you find a med kit. It's exactly what you need!</p>
            
            <img src="images/medkit.png" class="scene-img">

            <form method="post">
                <input type="hidden" name="action" value="take_med_kit">
                <button type="submit" class="choice-btn">Take the med kit</button>
            </form>

            
        <?php elseif ($_SESSION['part2_stage'] == 'med_kit_taken'): ?>
            <h2>Using the Med Kit</h2>
            <p>You equip the med kit to your inventory. Now, you must use it to heal your copilot.</p>
            
            <img src="images/treat.png" class="scene-img">

            <form method="post">
                <input type="hidden" name="action" value="use_med_kit">
                <button type="submit" class="choice-btn">Use the med kit on your copilot</button>
            </form>
            
            
        <?php elseif ($_SESSION['part2_stage'] == 'copilot_healed'): ?>
            <h2>Your Copilot is Healed</h2>
            <p>After using the med kit, your copilot is now healed and able to assist you. You must now plan your next move to survive in the wilderness.</p>
            
            <img src="images/pt2finalimg.png" class="scene-img">

            <form action="part3.php" method="post">
                <button type="submit" class="choice-btn">Continue your journey</button>
            </form>

            
            
        <?php endif; ?>
    </div>
</body>
</html>
