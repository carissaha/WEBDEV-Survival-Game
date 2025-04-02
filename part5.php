<?php
include('health.php');
if(isset($_POST['reset'])) {
    $_SESSION['part5_stage'] = 'day3_start';
    resetHealth();
}

if(!isset($_SESSION['part5_stage'])) {
    $_SESSION['part5_stage'] = 'day3_start';
}

if(isset($_POST['action'])) {
    $action = $_POST['action'];
    
    if($_SESSION['part5_stage'] == 'day3_start' && $action == 'start_journey') {
        if(isset($_SESSION['helped_copilot']) && $_SESSION['helped_copilot'] === true) {
            $_SESSION['part5_stage'] = 'forest_journey';
        }else {
            $chance = rand(1, 2);
            if($chance == 1) {
                $_SESSION['part5_stage'] = 'forest_journey';
            }else {
                $_SESSION['part5_stage'] = 'lost_in_forest';
                decreaseHealth(10);
                if(isGameOver()) {
                    $_SESSION['part5_stage'] = 'game_over';
                }
            }
        }
    }
    
    elseif($_SESSION['part5_stage'] == 'lost_in_forest' && $action == 'find_way') {
        $_SESSION['part5_stage'] = 'forest_journey';
    }
    
    elseif($_SESSION['part5_stage'] == 'forest_journey' && $action == 'continue') {
        $_SESSION['part5_stage'] = 'bear_encounter';
    }
    
    elseif($_SESSION['part5_stage'] == 'bear_encounter') {
        if($action == 'stay_still') {
            if(isset($_SESSION['helped_copilot']) && $_SESSION['helped_copilot'] === true) {
                $chance = rand(1, 4); 
                if($chance <= 3) {
                    $_SESSION['part5_stage'] = 'bear_consequence';
                    $_SESSION['choice_made'] = 'stay_still_success';
                }else {
                    decreaseHealth(30);
                    $_SESSION['part5_stage'] = 'bear_consequence';
                    $_SESSION['choice_made'] = 'stay_still_failure';
                    
                    if(isGameOver()) {
                        $_SESSION['part5_stage'] = 'game_over';
                    }
                }
            } else {
                $chance = rand(1, 2); 
                if($chance == 1) {
                    $_SESSION['part5_stage'] = 'bear_consequence';
                    $_SESSION['choice_made'] = 'stay_still_success';
                } else {
                    decreaseHealth(40);
                    $_SESSION['part5_stage'] = 'bear_consequence';
                    $_SESSION['choice_made'] = 'stay_still_failure';
                    
                    if(isGameOver()) {
                        $_SESSION['part5_stage'] = 'game_over';
                    }
                }
            }
        } elseif($action == 'scare_bear') {
            if(isset($_SESSION['helped_copilot']) && $_SESSION['helped_copilot'] === true) {
                $_SESSION['part5_stage'] = 'bear_consequence';
                $_SESSION['choice_made'] = 'scare_bear_success';
            } else {
                $chance = rand(1, 5); 
                if($chance <= 4) {
                    $_SESSION['part5_stage'] = 'bear_consequence';
                    $_SESSION['choice_made'] = 'scare_bear_success';
                }else {
                    decreaseHealth(35);
                    $_SESSION['part5_stage'] = 'bear_consequence';
                    $_SESSION['choice_made'] = 'scare_bear_failure';
                    
                    if(isGameOver()) {
                        $_SESSION['part5_stage'] = 'game_over';
                    }
                }
            }
        } elseif($action == 'run_away') {
            if(isset($_SESSION['helped_copilot']) && $_SESSION['helped_copilot'] === true) {
                decreaseHealth(45); 
            }else {
                decreaseHealth(60); 
            }
            
            $_SESSION['part5_stage'] = 'bear_consequence';
            $_SESSION['choice_made'] = 'run_away_failure';
            
            if(isGameOver()) {
                $_SESSION['part5_stage'] = 'game_over';
            }
        }
    }
    
    elseif($_SESSION['part5_stage'] == 'bear_consequence' && $action == 'continue') {
        if($_SESSION['choice_made'] == 'stay_still_failure' || $_SESSION['choice_made'] == 'scare_bear_failure' || $_SESSION['choice_made'] == 'run_away_failure') {
            $_SESSION['part5_stage'] = 'bear_injury';
        } else {
            $_SESSION['part5_stage'] = 'mysterious_box';
        }
    }
    
    elseif($_SESSION['part5_stage'] == 'bear_injury' && $action == 'continue') {
        if(isset($_SESSION['helped_copilot']) && $_SESSION['helped_copilot'] === true) {
            increaseHealth(20);
        }else {
            $chance = rand(1, 3);
            if($chance == 1) {
                decreaseHealth(20); 
                
                if(isGameOver()) {
                    $_SESSION['part5_stage'] = 'game_over';
                    return; 
                }
            }
        }
        
        $_SESSION['part5_stage'] = 'mysterious_box';
    }
    
    elseif($_SESSION['part5_stage'] == 'mysterious_box') {
        if($action == 'open_box') {
            decreaseHealth(50);
            $_SESSION['part5_stage'] = 'box_consequence';
            $_SESSION['choice_made'] = 'opened_box';
            
            if(isGameOver()) {
                $_SESSION['part5_stage'] = 'game_over';
            }
        }elseif($action == 'leave_box') {
            $_SESSION['part5_stage'] = 'box_consequence';
            $_SESSION['choice_made'] = 'left_box';
        }
    }
    
    elseif($_SESSION['part5_stage'] == 'box_consequence' && $action == 'continue') {
        $_SESSION['part5_stage'] = 'reach_rescue_point';
    }
    
    elseif($_SESSION['part5_stage'] == 'reach_rescue_point' && $action == 'continue') {
        header("Location: part6.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survival Adventure - Day 3</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #1a1a1a;
            color: #e6e6e6;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #2a2a2a;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.7);
            position: relative;
            overflow: hidden;
        }
        h1 {
            color: #ff9933;
            text-align: center;
            margin-bottom: 20px;
            font-size: 2.2em;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }
        h2 {
            color: #66ccff;
            margin-top: 20px;
            font-size: 1.8em;
            border-bottom: 2px solid #444;
            padding-bottom: 10px;
        }
        p {
            font-size: 1.1em;
            margin-bottom: 20px;
        }
        
        .choice-btn {
            display: block;
            width: 100%;
            background-color: #334455;
            color: white;
            padding: 15px 20px;
            margin: 15px 0;
            border-radius: 8px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 1.1em;
            text-align: left;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0,0,0,0.2);
            position: relative;
            overflow: hidden;
        }
        .choice-btn:hover {
            background-color: #4a6380;
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(0,0,0,0.3);
        }
        .choice-btn:after {
            content: "→";
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.4em;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .choice-btn:hover:after {
            opacity: 1;
        }
        
        .scene-img {
            width: 100%;
            max-height: 350px;
            object-fit: cover;
            border-radius: 10px;
            margin: 15px 0;
            box-shadow: 0 5px 15px rgba(0,0,0,0.5);
            transition: transform 0.3s ease;
        }
        .scene-img:hover {
            transform: scale(1.02);
        }
        
        .consequence-box {
            background-color: #2c3e50;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            border-left: 5px solid;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            animation: fadeIn 0.5s ease;
        }
        .consequence-box h3 {
            margin-top: 0;
            color: #f0f0f0;
        }
        .positive {
            border-color: #2ecc71;
        }
        .negative {
            border-color: #e74c3c;
        }
        .neutral {
            border-color: #3498db;
        }
        
        .health-change {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
            margin: 5px 0;
        }
        .health-up {
            background-color: #2ecc71;
            color: #fff;
        }
        .health-down {
            background-color: #e74c3c;
            color: #fff;
        }
        
        .pilot-hint {
            background-color: #3d5a80;
            border-radius: 10px;
            padding: 15px;
            margin: 15px 0;
            border-left: 5px solid #ffcc00;
            font-style: italic;
            position: relative;
            animation: fadeIn 0.8s ease;
        }
        .pilot-hint:before {
            content: """;
            font-size: 3em;
            color: #ffcc00;
            position: absolute;
            left: 10px;
            top: -10px;
        }
        
        .reset-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #c33;
            color: white;
            padding: 10px 20px;
            border-radius: 30px;
            cursor: pointer;
            border: none;
            font-weight: bold;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
            transition: all 0.3s ease;
            z-index: 100;
        }
        .reset-btn:hover {
            background-color: #e33;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.4);
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        @keyframes fadeInLeft {
            from { opacity: 0; transform: translateX(-50px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        /* Game over style */
        .game-over {
            text-align: center;
            padding: 40px 20px;
            animation: fadeIn 1s ease;
        }
        .game-over h2 {
            color: #ff4d4d;
            font-size: 2.5em;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
            border: none;
            animation: pulse 2s infinite;
        }
        .restart-btn {
            display: inline-block;
            padding: 15px 30px;
            margin-top: 30px;
            background-color: #ff6b6b;
            color: white;
            font-size: 1.2em;
            border-radius: 30px;
            text-decoration: none;
            transition: all 0.3s ease;
            animation: fadeInLeft 1s ease 0.5s both;
        }
        .restart-btn:hover {
            background-color: #ff4d4d;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="container">
        <?php displayHealthBar(); ?>
                
        <h1>Survival Challenge: Day 3</h1>
        
        <?php if($_SESSION['part5_stage'] == 'day3_start'): ?>
            <h2>A New Dawn of Hope</h2>
            <p>Sunlight pours through the wooden wall cracks as you awake inside the cabin. The restful sleep you had last night left you feeling both refreshed and determined. Today could be the day of rescue.</p>
            
            <img src="images/morningcabin.gif" class="scene-img">
            
            <p>The map from the handbook shows the location of a ranger station on a hilltop several miles away. According to the notes, it's a designated rescue point with radio equipment and supplies.</p>
            
            <?php if(isset($_SESSION['helped_copilot']) && $_SESSION['helped_copilot'] === true): ?>
                <p>The co-pilot studies the map carefully. "During my flight training I learned about this terrain so I recognize it now". "Sticking to the ridgeline gives us a clear path around dense underbrush to reach our destination faster" He said.</p>
            <?php else: ?>
                <p>You study the map carefully. The journey ahead looks challenging, with several possible routes through the mountainous terrain.</p>
            <?php endif; ?>
            
            <p>You gather your limited supplies, including some leftover berries and water. The journey ahead will take most of the day, but if you can reach the station, you might finally be rescued.</p>
            <form method="post">
                <button type="submit" name="action" value="start_journey" class="choice-btn">Begin the journey to the ranger station</button>
            </form>
            
        <?php elseif($_SESSION['part5_stage'] == 'lost_in_forest'): ?>
            <h2>Off the Beaten Path</h2>
            <div class="consequence-box negative">
                <h3>You've taken a wrong turn</h3>
                <p>As your journey progresses through several hours you start to feel anxious when you notice that the landmarks around you don't correspond to the map you've been following.</p>
                <p>A wave of disappointment washes over you because you've been traveling in the opposite direction. You spent valuable time and energy on this wrong path and must now return to locate the correct route.</p>
                <img src="images/wilderness.gif"  class="scene-img">
                <span class="health-change health-down">Health decreased by 10%</span>
            </div>
            
            <p>After consulting the map and carefully studying your surroundings, you identify a distant ridge that looks familiar. With renewed determination, you adjust your course and push forward, hoping to get back on track.</p>
            <form method="post">
                <button type="submit" name="action" value="find_way" class="choice-btn">Find your way back to the correct path</button>
            </form>
            
        <?php elseif($_SESSION['part5_stage'] == 'forest_journey'): ?>
            <h2>Through the Wilderness</h2>
            <p>Following the map from the handbook, you make steady progress through the dense forest. The terrain becomes increasingly steep as you navigate toward the hilltop ranger station.</p>
            
            <img src="images/denseforest.gif"class="scene-img">
            
            <?php if(isset($_SESSION['helped_copilot']) && $_SESSION['helped_copilot'] === true): ?>
                <p>The co-pilot leads the way with confidence, finding the easiest paths through the rough terrain.</p>
                <p>"We're making good time," he says, checking the sun's position. "If we keep this pace, we should reach the station well before sunset." The journey is still challenging, but his guidance makes it considerably easier.</p>
            <?php else: ?>
                <p>Multiple times you come across barriers which require you to retreat and search for different paths. Progress remains steady even though the journey continues to be slow and tough.</p>
            <?php endif; ?>
            
            <p>You pause to rest briefly and consume berries from your previous day's collection just before midday. Your current position on the map shows you have surpassed the halfway mark to your goal.</p>
            <form method="post">
                <button type="submit" name="action" value="continue" class="choice-btn">Continue your journey</button>
            </form>
            
        <?php elseif($_SESSION['part5_stage'] == 'bear_encounter'): ?>
            <h2>Unexpected Danger</h2>
            <p>You stop dead in your tracks when you turn the corner along the trail. A large black bear investigates a fallen log for insects on the ground just thirty feet away. The wind has started to blow toward you but the bear remains unaware.</p>
            
            <img src="images/bear.gif"  class="scene-img">
            
            <?php if(isset($_SESSION['helped_copilot']) && $_SESSION['helped_copilot'] === true): ?>
                <div class="pilot-hint">
                    <p>The co-pilot takes hold of your arm with care while whispering urgently about the black bear nearby: "Their typical behavior is to steer clear of confrontation yet we must not flee or play dead. Our most effective strategy involves making loud noises while appearing larger. I'll help you."</p>
                </div>
            <?php endif; ?>
            
            <p>Your heart races while you think through your possible courses of action. The bear will spot you shortly - you must make your decision fast.</p>
            <form method="post">
                <button type="submit" name="action" value="stay_still" class="choice-btn">1️⃣ Stay perfectly still and hope it leaves (<?php echo (isset($_SESSION['helped_copilot']) && $_SESSION['helped_copilot'] === true) ? '75%' : '50%'; ?> chance of success)</button>
                <button type="submit" name="action" value="scare_bear" class="choice-btn">2️⃣ Try to scare it away by making noise and looking big</button>
                <button type="submit" name="action" value="run_away" class="choice-btn">3️⃣ Turn and run away as fast as possible</button>
            </form>
            
        <?php elseif($_SESSION['part5_stage'] == 'bear_consequence'): ?>
            <h2>Bear Encounter Outcome</h2>
            
            <?php if($_SESSION['choice_made'] == 'stay_still_success'): ?>
                <div class="consequence-box positive">
                    <h3>The bear moved on</h3>
                    <p>You maintain complete stillness with minimal breath as the bear continues its search for food. After enduring what seemed like endless time it moves off into the thickets without ever seeing you.</p>
                    <p>When you know the bear is gone you release a shaky breath then proceed carefully along your way while intentionally making noise to prevent alerting other animals.</p>
                    <img src="images/bearpass.gif" class="scene-img">
                </div>
            <?php elseif($_SESSION['choice_made'] == 'stay_still_failure'): ?>
                <div class="consequence-box negative">
                    <h3>The bear noticed you</h3>
                    <p>You stay motionless yet the wind changes direction transporting your scent to the bear. It suddenly looks up, spotting you immediately. As soon as you realize your danger you feel the bear charge at you because it recognizes you as a threat. </p>
                    
                    <?php if(isset($_SESSION['helped_copilot']) && $_SESSION['helped_copilot'] === true): ?>
                        <p>The co-pilot reacts instantly, pushing you aside and grabbing a fallen branch. He swings it defensively while shouting loudly, diverting some of the bear's attention away from you. While you still sustain injuries in the brief but terrifying attack, his intervention likely saved you from more serious harm.</p>
                        <p>Eventually, the bear loses interest and wanders off, leaving you both injured but alive.</p>
                        <span class="health-change health-down">Health decreased by 30%</span>
                    <?php else: ?>
                        <p>The attack is brief but terrifying. You roll into a ball while the bear lashes out with its strong claws. The claws of the bear rip through your clothing before cutting deeply into your skin causing extreme pain. </p>
                        <p>The bear loses interest and wanders away which leaves you with serious injuries and a shaken state...</p>
                        <span class="health-change health-down">Health decreased by 40%</span>
                    <?php endif; ?>
                    
                    <img src="images/bearchase.gif"  class="scene-img">
                </div>
            <?php elseif($_SESSION['choice_made'] == 'scare_bear_success'): ?>
                <div class="consequence-box positive">
                    <h3>The bear retreated</h3>
                    <?php if(isset($_SESSION['helped_copilot']) && $_SESSION['helped_copilot'] === true): ?>
                        <p>The co-pilot takes charge and both of you stand tall while waving your arms and yelling. When the co-pilot strikes a tree trunk with a branch it creates more noise.</p>
                        <p>"Hey bear! Get out of here!" you yell together, creating an intimidating presence.</p>
                    <?php else: ?>
                        <p>You recall your wilderness training instructions as you stand up straight and wave your arms while shouting at the top of your lungs. You gather a branch close by and hit it against the tree trunk to produce additional noise.</p>
                        <p>"Hey bear! Get out of here!" Your loud shouts combined with your attempts to look bigger intimidate the bear.</p>
                    <?php endif; ?>
                    <p>Startled by your noise and movement the bear stands on its hind legs before deciding to retreat without further interactionf.</p>
                    <img src="images/bearrun.gif" class="scene-img">
                </div>
            <?php elseif($_SESSION['choice_made'] == 'scare_bear_failure'): ?>
                <div class="consequence-box negative">
                    <h3>The bear stood its ground</h3>
                    <p>You attempt to intimidate the bear by puffing yourself up while yelling and flailing your arms in the air. The bear sees your movements as an aggressive act and decides to attack.</p>
                    <p>The attack occurs so fast that you struggle to respond. The bear uses its powerful claws to swipe at you after knocking you down. Its powerful claws ripping through your clothing causes severe pain.</p>
                    <p>The bear retreats from the fearful encounter and wanders off until you are left injured and shaking on the forest floor.</p>
                    <img src="images/bearchase.gif"class="scene-img">
                    <p><span class="health-change health-down">Health decreased by 35%</span></p>
                </div>
            <?php elseif($_SESSION['choice_made'] == 'run_away_failure'): ?>
                <div class="consequence-box negative">
                    <h3>The bear chased you</h3>
                    <?php if(isset($_SESSION['helped_copilot']) && $_SESSION['helped_copilot'] === true): ?>
                        <p>Suddenly panic consumes you and your instincts drive you to escape. The co-pilot shouts, "No! Don't run!" but it's too late. The bear starts charging when it sees the sudden movement because its predatory instincts kick in.</p>
                        <p> The co-pilot emerges right before the bear knocks you down while waving a large branch and shouting. The distraction from his shouting lets you escape but you feel the bear's claws on your back before you do. The co-pilot successfully fends off the bear but both of you sustain injuries during the confrontation.</p>
                        <img src="images/bearchasee.jpg" class="scene-img">
                        <p><span class="health-change health-down">Health decreased by 45%</span></p>
                    <?php else: ?>
                        <p>Panic consumes you and you sprint away with maximum speed. The bear's predatory instinct is activated by the sudden movement which causes it to charge directly towards you.</p>
                        <p>The bear gains speed rapidly to catch up with you and forces you onto the ground. The attack is violent and terrifying. The bear attacks with claws and teeth but you defend yourself by curling into a protective ball. The bear eventually loses interest after what feels like a very long time and leaves you with serious injuries.</p>
                        <img src="images/bearchase.gif" class="scene-img">
                        <p><span class="health-change health-down">Health decreased by 60%</span></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <form method="post">
                <button type="submit" name="action" value="continue" class="choice-btn">Continue your journey</button>
            </form>
            
        <?php elseif($_SESSION['part5_stage'] == 'bear_injury'): ?>
            <h2>Tending to Injuries</h2>
            <p>After the bear attack, you're in rough shape. Blood seeps through your torn clothing from multiple lacerations. Your body aches from the impact of being knocked to the ground.</p>
            
            <?php if(isset($_SESSION['helped_copilot']) && $_SESSION['helped_copilot'] === true): ?>
                <div class="consequence-box positive">
                    <h3>Expert first aid</h3>
                    <p>The co-pilot offers assistance while ripping his shirt into strips to bandages you. "You saved my life before. Now it's my turn." </p>
                    <p>By applying what he learned in survival training he assists in cleaning and binding your injuries.</p>
                    <img src="images/helptreat.jpg" class="scene-img">
                    <p><span class="health-change health-up">Health increased by 20%</span> - The proper medical treatment has made a significant difference.</p>
                </div>
            <?php else: ?>
                <div class="consequence-box negative">
                    <h3>Struggling alone</h3>
                    <p>Your limited first aid abilities force you to self-treat your injuries while struggling to manage the situation. You clean deep wounds with water yet you lack any disinfectant solution.</p>
                    <img src="images/selftreat.jpg" class="scene-img">
                    
                    <?php if(rand(1, 3) == 1): ?>
                        <p>As you continue your journey, the largest wound on your shoulder begins to feel hot and increasingly painful. You notice red streaks extending from the injury - signs of infection setting in.</p>
                        <p><span class="health-change health-down">Health decreased by 10%</span> - One of your wounds has become infected.</p>
                    <?php else: ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <form method="post">
                <button type="submit" name="action" value="continue" class="choice-btn">Continue your journey</button>
            </form>
            
        <?php elseif($_SESSION['part5_stage'] == 'mysterious_box'): ?>
            <h2>A Strange Discovery</h2>
            <p>You resume your journey along the trail to reach the rescue point after surviving the bear encounter. During your journey through the thick forest you discover an odd weathered wooden box positioned among the roots of an old tree.</p>
            
            <img src="images/box.jpg" class="scene-img">
            
            <p>Despite its aged appearance due to moss and dirt, an ornate metal clasp on the box reflects dappled sunlight. The box appears deliberately hidden in the wilderness by someone from the past.</p>
            
            <?php if(isset($_SESSION['helped_copilot']) && $_SESSION['helped_copilot'] === true): ?>
                <div class="pilot-hint">
                    <p>The co-pilot studies the box with suspicion. "I don't like the look of that," he says quietly. "Could be an old hunter's trap, or worse". "We should leave it alone - we're so close to rescue now." he said.</p>
                </div>
            <?php endif; ?>
            
            <p>The contents of the box remain a mystery to you. The box might have useful supplies or equipment that could help you but it appears strange and possibly dangerous.</p>
            <form method="post">
                <button type="submit" name="action" value="open_box" class="choice-btn">Open the mysterious box</button>
                <button type="submit" name="action" value="leave_box" class="choice-btn">Leave the box undisturbed and continue</button>
            </form>

        <?php elseif($_SESSION['part5_stage'] == 'box_consequence'): ?>
            <h2>The Box's Secret</h2>
            
            <?php if($_SESSION['choice_made'] == 'opened_box'): ?>
                <div class="consequence-box negative">
                    <h3>A Painful Surprise</h3>
                    <p>Your curiosity gets the better of you. You drop to your knees and slowly open the box with care. A sudden mechanical click follows when the lid begins to creak open. A cloud of ancient dust and spores bursts forth from the box and covers your face before you have time to respond.</p>
                    
                    <?php if(isset($_SESSION['helped_copilot']) && $_SESSION['helped_copilot'] === true): ?>
                        <p>The co-pilot quickly pulls you away from the cloud, covering your mouth and nose with a cloth. "Try not to breathe it in," he warns, helping you move upwind of the dust. "Let's get you to fresh air and rinse your eyes with water."</p>
                        <p>His quick reaction helps limit your exposure, but you still feel the effects of whatever was in that box.</p>
                    <?php else: ?>
                        <p>
                        You stumble away from the box while desperately attempting to flee the encompassing dust cloud. You gasp for air as your lungs scorch with each breath while your vision becomes distorted because your eyes keep watering.</p>
                        <p>The severe symptoms fade away after several minutes but leave you weak and disoriented.</p>
                    <?php endif; ?>
                    
                    <img src="images/facecover.gif" class="scene-img">
                    <span class="health-change health-down">Health decreased by 50%</span>
                </div>
            <?php else: ?>
                <div class="consequence-box positive">
                    <h3>Wisdom in Caution</h3>
                    <?php if(isset($_SESSION['helped_copilot']) && $_SESSION['helped_copilot'] === true): ?>
                        <p>"Good call," the co-pilot nods approvingly. "That box could have been anything - an animal's nest, an old trap, or just filled with rot and disease. Not worth the risk when we're so close to rescue."</p>
                    <?php else: ?>
                        <p>The box gives me an uneasy feeling. Your survival instincts have protected you until now and they advise you to avoid the strange object.</p>
                    <?php endif; ?>
                    
                    <p>The main goal should always be to ensure your safety rather than to explore your curiosity.</p>
                    
                    <img src="images/nope.gif" class="scene-img">
                </div>
            <?php endif; ?>
            
            <p>With that strange encounter behind you, you continue on your journey. According to your calculations, the rescue point should be just ahead.</p>
            <form method="post">
                <button type="submit" name="action" value="continue" class="choice-btn">Continue to the rescue point</button>
            </form>

        <?php elseif($_SESSION['part5_stage'] == 'reach_rescue_point'): ?>
            <h2>The Final Stretch</h2>
            <p>According to the handbook map, this elevated clearing is the designated rescue point. It's easily visible from the air and marked on search and rescue maps. This is where you need to signal for help.</p>
            
            <img src="images/view.jpg" class="scene-img">
            
            <?php if(isset($_SESSION['helped_copilot']) && $_SESSION['helped_copilot'] === true): ?>
                <p>The co-pilot surveys the area with a professional eye. "This is perfect," he says. "Clear line of sight in all directions, no tall trees to block visibility. Search aircraft should be able to spot signals from here easily."</p>
                <p>He points to various features of the landscape, helping you get oriented. "There's the valley we came through... and over there must be the crash site. We've come a long way."</p>
            <?php else: ?>
                <p>You pause to relax while you examine the immense wilderness surrounding you. The elevated position allows you to view the distance you've managed to cover since your crash. Your determination and skill helped you reach the rescue point through all the challenges you faced.</p>
            <?php endif; ?>
            
            <p>As the sun begins to set, you prepare for your final night in the wilderness. Tomorrow, you'll attempt to signal for rescue.</p>
            <form method="post">
                <button type="submit" name="action" value="continue" class="choice-btn">Continue to the final chapter</button>
            </form>
            
        <?php elseif($_SESSION['part5_stage'] == 'game_over'): ?>
            <div class="game-over">
                <h2>Game Over</h2>
                <p>Your injuries were too severe to continue. Despite your best efforts, you collapse on the forest trail, unable to go any further. Your vision dims as consciousness fades away...</p>
                <img src="images/gameover.gif"  class="scene-img">
                <p>Your survival adventure has come to an end.</p>
                <form method="post">
                    <button type="submit" name="reset" value="true" class="restart-btn">Try Again</button>
                </form>
            </div>
        <?php endif; ?>
        
    </div>
    
    <form method="post">
        <button type="submit" name="reset" value="true" class="reset-btn">Reset Game</button>
    </form>
</body>
</html>