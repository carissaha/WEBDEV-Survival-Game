<?php
include('health.php');
include('inventorypt23.php');

if(!isset($_SESSION['part4_visited'])) {
    resetInventory();
    $_SESSION['part4_visited'] = true;
}
if(isset($_POST['reset'])) {
    $_SESSION['part4_stage'] = 'day2_start';
    resetHealth();
    resetInventory();
}
elseif(isset($_POST['from_part3']) && $_POST['from_part3'] == 'true') {
    $_SESSION['part4_stage'] = 'day2_start';
    if(isset($_POST['current_health'])) {
        $_SESSION['health'] = $_POST['current_health'];
    }
    resetInventory();
}

if(isset($_POST['action'])) {
    $action = $_POST['action'];
    if($_SESSION['part4_stage'] == 'day2_start' && $action == 'start_water_search') {
        $_SESSION['part4_stage'] = 'water_choice';
    }
    if($_SESSION['part4_stage'] == 'water_choice') {
        if($action == 'drink_stream') {
            decreaseHealth(20);
            $_SESSION['part4_stage'] = 'water_consequence';
            $_SESSION['choice_made'] = 'drink_stream';
        }elseif($action == 'boil_water') {
            increaseHealth(10);
            $_SESSION['part4_stage'] = 'water_consequence';
            $_SESSION['choice_made'] = 'boil_water';
        }
    }
    
    elseif($_SESSION['part4_stage'] == 'water_consequence' && $action == 'continue') {
        $_SESSION['part4_stage'] = 'bridge';
    }
    
    elseif($_SESSION['part4_stage'] == 'bridge') {
        if($action == 'cross_carefully') {
            $chance = rand(1, 2);
            if($chance == 1) {
                $_SESSION['part4_stage'] = 'bridge_consequence';
                $_SESSION['choice_made'] = 'cross_success';
            }else {
                decreaseHealth(30);
                $_SESSION['part4_stage'] = 'bridge_consequence';
                $_SESSION['choice_made'] = 'cross_failure';
            }
        } elseif($action == 'find_another_way') {
            $_SESSION['part4_stage'] = 'bridge_consequence';
            $_SESSION['choice_made'] = 'find_another_way';
        }elseif($action == 'jump_across') {
            decreaseHealth(50);
            $_SESSION['part4_stage'] = 'bridge_consequence';
            $_SESSION['choice_made'] = 'jump_across';
        }
        
        if(isGameOver()) {
            $_SESSION['part4_stage'] = 'game_over';
        }
    }
    
    elseif($_SESSION['part4_stage'] == 'bridge_consequence' && $action == 'continue') {
        if($_SESSION['choice_made'] == 'cross_failure' || $_SESSION['choice_made'] == 'jump_across') {
            $_SESSION['part4_stage'] = 'bridge_failure';
        } else if($_SESSION['choice_made'] == 'find_another_way') {
            $_SESSION['part4_stage'] = 'path_choice';
        } else {
            $_SESSION['part4_stage'] = 'cabin';
        }
    }
    
    elseif($_SESSION['part4_stage'] == 'path_choice') {
        if($action == 'go_right') {
            $_SESSION['part4_stage'] = 'right_path';
        } elseif($action == 'go_left') {
            decreaseHealth(15);
            $_SESSION['part4_stage'] = 'lost_path';
            
            if(isGameOver()) {
                $_SESSION['part4_stage'] = 'game_over';
            }
        }
    }
    
    elseif($_SESSION['part4_stage'] == 'right_path' && $action == 'continue') {
        $_SESSION['part4_stage'] = 'cabin';
    }
    
    elseif($_SESSION['part4_stage'] == 'lost_path' && $action == 'continue') {
        $_SESSION['part4_stage'] = 'cabin';
    }
    
    elseif($_SESSION['part4_stage'] == 'bridge_failure' && $action == 'continue') {
        $_SESSION['part4_stage'] = 'cabin';
    }
    
    elseif($_SESSION['part4_stage'] == 'cabin' && $action == 'explore') {
        $_SESSION['part4_stage'] = 'food_choice';
    }
    
    elseif($_SESSION['part4_stage'] == 'cabin' && $action == 'examine_handbook') {
        $_SESSION['part4_stage'] = 'handbook';
    }
    
    elseif($_SESSION['part4_stage'] == 'handbook' && $action == 'take_handbook') {
        if(!in_array('Survival Handbook', $_SESSION['inventory'])) {
            addItemToInventory('Survival Handbook');
        }
        $_SESSION['part4_stage'] = 'cabin';
    }
    
    elseif($_SESSION['part4_stage'] == 'food_choice') {
        if($action == 'purple_berries') {
            increaseHealth(15);
            $_SESSION['part4_stage'] = 'berries_consequence';
            $_SESSION['choice_made'] = 'purple_berries';
        }elseif($action == 'yellow_berries') {
            decreaseHealth(25);
            $_SESSION['part4_stage'] = 'berries_consequence';
            $_SESSION['choice_made'] = 'yellow_berries';
            
            if(isGameOver()) {
                $_SESSION['part4_stage'] = 'game_over';
            }
        }
    }
    
    elseif($_SESSION['part4_stage'] == 'berries_consequence' && $action == 'continue') {
        $_SESSION['part4_stage'] = 'sleep';
    }
    
    elseif($_SESSION['part4_stage'] == 'sleep' && $action == 'sleep') {
        header("Location: part5.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survival Adventure - Day 2</title>
    <link rel="stylesheet" href="part2and3.css">
</head>
<body>
    <div class="container">
        <?php displayHealthBar(); ?>
        <?php displayInventory(); ?>
        <div class="day-indicator">Day 2</div>

                
        <h1>Survival Challenge: Day 2</h1>
        
        <?php if($_SESSION['part4_stage'] == 'day2_start'): ?>
            <h2>A New Day Begin</h2>
            <p>After a sleepless night you find yourself awakening inside the cave. Sunlight streams through the cave entrance during the morning to create elongated shadows against the stone walls. You feel a dryness in your throat while your stomach produces growling sounds because of hunger. </p>
            
            <img src="images/dawn.gif" class="scene-img">
            
            <p>The survival training you learned pushes you to remember that your first action must be to find water. Your survival in this wilderness will be short without water.</p>
            <form method="post">
                <input type="hidden" name="action" value="start_water_search">
                <button type="submit" class="choice-btn">Begin the search for water</button>
            </form>            
        <?php elseif($_SESSION['part4_stage'] == 'water_choice'): ?>
            <h2>Finding Water</h2>
            <p>After an hour plus of hiking in dense jungle - dodging vines plus climbing above large, twisted roots - you hear the sure sound of flowing water. It draws you and you push past a screen of ferns to see a clear brook move through the thick undergrowth.</p>
            
            <img src="images/stream.gif" class="scene-img">
            
            <p>Parched and weary the clear water seems good. Your survival instruction takes over - you recall the risk of parasites besides germs even in clear streams. Now a difficult choice exists: drink it at risk or purify it first?</p>
            <form method="post">
                <button type="submit" name="action" value="drink_stream" class="choice-btn">1️⃣ Drink directly from the stream - The water looks clean enough</button>
                <button type="submit" name="action" value="boil_water" class="choice-btn">2️⃣ Purify it first before drink it.</button>
            </form>
            
        <?php elseif($_SESSION['part4_stage'] == 'water_consequence'): ?>
            <h2>Water Decision</h2>
            
            <?php if($_SESSION['choice_made'] == 'drink_stream'): ?>
                <div class="consequence-box negative">
                    <h3>You drank directly from the stream</h3>
                    <p>The cold water is great and stops your thirst. You feel new and strong. But soon your stomach cramps a lot - the water held bad germs or bugs. Your body cannot beat these. You are less strong - your body fights the problem.</p>
                    <img src="images/poisoned.gif" class="scene-img">
                    <p><strong>Health decreased by 20%</strong></p>
                </div>
            <?php else: ?>
                <div class="consequence-box positive">
                    <h3>You purified the water first</h3>
                    <p>You collect wood and utilize the survival kit - the aim is to create a small fire. After a water boil for a few minutes, you let the liquid cool prior to drinking. The added work benefits you - the water is now safe, also cool, in addition to your body gets hydration well, resulting in greater energy.</p>
                    <img src="images/boiled.gif"  class="scene-img">
                    <p><strong>Health increased by 10%</strong></p>
                </div>
            <?php endif; ?>
            
            <form method="post">
                <button type="submit" name="action" value="continue" class="choice-btn">Continue your journey</button>
            </form>
            
        <?php elseif($_SESSION['part4_stage'] == 'bridge'): ?>
            <h2>The Rickety Bridge</h2>
            <p>After you quench your thirst, you proceed along the stream. After a mile or so, the land shifts a lot - the stream becomes a rapid river and it goes through a narrow ravine.</p>
            <p>A rope bridge crosses the ravine. It appears neglected for years - several boards vanished and the ropes show wear.</p>
            
            <img src="images/bridge.gif" class="scene-img">
            
            <p>To keep going toward the rescue site, you must cross this river. For miles in each direction, the ravine continues - but what action will you take?</p>
            <form method="post">
                <button type="submit" name="action" value="cross_carefully" class="choice-btn">1️⃣ Carefully cross the bridge (50/50 chance of success)</button>
                <button type="submit" name="action" value="find_another_way" class="choice-btn">2️⃣ Look for another way around </button>
                <button type="submit" name="action" value="jump_across" class="choice-btn">3️⃣ The gap doesn't look that wide - try to jump across</button>
            </form>
            
        <?php elseif($_SESSION['part4_stage'] == 'bridge_consequence'): ?>
            <h2>Bridge Decision</h2>
            
            <?php if($_SESSION['choice_made'] == 'cross_success'): ?>
                <div class="consequence-box positive">
                    <h3>You successfully crossed the bridge</h3>
                    <p>You test each plank prior to your steps across the bridge. Despite some creaks and sways - actions that quicken your heartbeat - the bridge supports your mass and you reach the other side unharmed. Your careful methods give results.</p>
                    <img src="images/bridge_success.gif" class="scene-img">
                </div>
            <?php elseif($_SESSION['choice_made'] == 'cross_failure'): ?>
                <div class="consequence-box negative">
                    <h3>The bridge collapsed!</h3>
                    <p>A loud CRACK! echoes as you reach the middle. The bridge collapses under you - the world blurs as you fall into the cold river. The current possesses force, slams you against rocks but you seize a fallen tree. You drag yourself to land. Bruises appear - also, you shake, in addition to water drenches you.</p>
                    <img src="images/bridge_fail.gif" class="scene-img">
                    <p><strong>Health decreased by 30%</strong></p>
                </div>
            <?php elseif($_SESSION['choice_made'] == 'find_another_way'): ?>
                <div class="consequence-box neutral">
                    <h3>You found another way around</h3>
                    <p>You judge the bridge possesses too much risk. After following the river, you discover a narrow region where trees lie as a natural pathway. The day is spent but you arrive on the far bank. This wariness cost you duration - it stopped possible harm.</p>
                    <img src="images/otherway.png" class="scene-img">
                </div>
            <?php elseif($_SESSION['choice_made'] == 'jump_across'): ?>
                <div class="consequence-box negative">
                    <h3>You tried to jump across!</h3>
                    <p>You retreat to secure momentum also jump toward the far bank. But you overestimated your ability. You fall into the swift river. The water sends you hard against rocks, then carries you down the waterway. You pull yourself to the river's edge, injured besides hurt.</p>
                    <img src="images/jump_fail.gif" class="scene-img">
                    <p><strong>Health decreased by 50%</strong></p>
                </div>
            <?php endif; ?>
            
            <form method="post">
                <button type="submit" name="action" value="continue" class="choice-btn">Continue your journey</button>
            </form>
            
        <?php elseif($_SESSION['part4_stage'] == 'path_choice'): ?>
            <h2>Lost in the Wilderness</h2>
            <p>Choosing the safe path around the bridge used much daylight. As evening arrives, the woods get dark - you see you departed from your intended way. Between the trees shadows become long - and strange noises occur in the forest.</p>
            
            <img src="images/twoway.jpg" class="scene-img">
            
            <p>The path splits ahead. One route goes right, the other stays left. The weak light does not allow a distant view of the trails. You must select one soon - total dark will come.</p>
            <form method="post">
                <button type="submit" name="action" value="go_right" class="choice-btn">1️⃣ Take the path to the right - It might be safer</button>
                <button type="submit" name="action" value="go_left" class="choice-btn">2️⃣ Take the path to the left - It might be a shortcut</button>
            </form>
            
        <?php elseif($_SESSION['part4_stage'] == 'right_path'): ?>
            <h2>The Right Choice</h2>
            <div class="consequence-box positive">
                <h3>You found the right path!</h3>
                <p>The trees gradually become less dense after half an hour of careful movement until you see a small clearing in front of you. You can't help but feel hopeful when you see the small wooden cabin tucked among the trees as you approach.</p>
                <img src="images/faraway_cabin.jpg" class="scene-img">
                <p>You approach the cabin carefully, watching for any signs of current inhabitants. The windows are dark, but the structure seems intact. This could be the shelter you desperately need.</p>
            </div>
            
            <form method="post">
                <button type="submit" name="action" value="continue" class="choice-btn">Approach the cabin</button>
            </form>
            
        <?php elseif($_SESSION['part4_stage'] == 'lost_path'): ?>
            <h2>Into the Unknown</h2>
            <div class="consequence-box negative">
                <h3>You chose the wrong path</h3>
                <p>The left path quickly narrows and becomes overgrown. You push through tangled vines and dense undergrowth, scratching your arms and face. As night falls completely, panic sets in. You're lost, tired, and the forest seems to close in around you.</p>
                <img src="images/lostforest.gif" class="scene-img">
                <p><strong>Health decreased by 15%</strong></p>
            </div>
            
            <p>When hope is almost gone, you see a small light among the trees. You have more resolve - you move ahead toward the light.</p>
            <form method="post">
                <button type="submit" name="action" value="continue" class="choice-btn">Continue toward the light</button>
            </form>
            
        <?php elseif($_SESSION['part4_stage'] == 'bridge_failure'): ?>
            <h2>Recovery</h2>
            <p>You are wet hurt next to tired after you fell. You rest to breathe easily and see what injuries you got. No injury seems deadly but you feel damaged.</p>
            <p>You walk along the riverbank for a while. Then you locate a spot where you can cross without danger. This deviation takes up important time but you continue the trip.</p>
            <form method="post">
                <button type="submit" name="action" value="continue" class="choice-btn">Continue your journey</button>
            </form>
            
        <?php elseif($_SESSION['part4_stage'] == 'cabin'): ?>
            <h2>A Hidden Refuge</h2>
            <p>As you approach you see a tiny wooden cabin which appears deserted yet remains structurally intact.</p>
            
            <img src="images/cabin.gif" class="scene-img">
            
            <p>The basic furniture inside the cabin is covered with dust. A survival handbook on a small desk shows details about local plants and animals, especially safe-to-eat berries. The handbook features a map which highlights the nearest ranger station where you can get rescued.</p>
            <p>Your stomach growls loudly, reminding you that you haven't eaten all day. You should look for food before nightfall.</p>
            <form method="post">
                <button type="submit" name="action" value="explore" class="choice-btn">Explore around the cabin for food</button>
                <button type="submit" name="action" value="examine_handbook" class="choice-btn">Examine the survival handbook</button>
            </form>
            
        <?php elseif($_SESSION['part4_stage'] == 'handbook'): ?>
            <h2>Survival Handbook</h2>
            <p>You open the survival handbook and examine its contents.</p>
            
            <img src="images/handbook.png" class="scene-img">
            
            <p>The handbook contains valuable information about local edible berries, a map to the ranger station, and proper rescue signals. This will be extremely useful for your survival.</p>
            <form method="post">
                <button type="submit" name="action" value="take_handbook" class="choice-btn">Take the handbook and return to the cabin</button>
            </form>
            
        <?php elseif($_SESSION['part4_stage'] == 'food_choice'): ?>
            <h2>Foraging for Food</h2>
            <p>You search the area around the cabin and discover several berry bushes growing in a small clearing nearby. According to the handbook, some berries in this region are nutritious while others can be toxic.</p>
            
            <img src="images/berries.jpg" class="scene-img">
            
            <p>Your hunger is becoming painful. The handbook has some information, but the pages describing yellow berries are partially damaged by water stains. Which berries will you eat?</p>
            <form method="post">
                <button type="submit" name="action" value="purple_berries" class="choice-btn">1️⃣ Eat the purple berries - The handbook clearly identifies them as safe and nutritious</button>
                <button type="submit" name="action" value="yellow_berries" class="choice-btn">2️⃣ Try the red berries - They look riper and more filling than the purple ones</button>
            </form>
            
        <?php elseif($_SESSION['part4_stage'] == 'berries_consequence'): ?>
            <h2>Berry Decision</h2>
            
            <?php if($_SESSION['choice_made'] == 'purple_berries'): ?>
                <div class="consequence-box positive">
                    <h3>You ate the purple berries</h3>
                    <p>The purple berries taste sweet with a hint of tartness. They're surprisingly filling, and you feel your strength returning as you gather more to take with you. The handbook was right - these berries contain natural sugars and nutrients that help restore your energy.</p>
                    <img src="images/correctberries.gif" class="scene-img">
                    <p><strong>Health increased by 15%</strong></p>
                </div>
            <?php else: ?>
                <div class="consequence-box negative">
                    <h3>You ate the yellow berries</h3>
                    <p>Soon after eating the yellow berries, your stomach cramps painfully. You become dizzy and nauseous - the berries contain toxins that your body is struggling to process. You manage to make yourself vomit, reducing the poison's effects, but you're left weakened and shaky.</p>
                    <img src="images/wrongberries.gif" class="scene-img">
                    <p><strong>Health decreased by 25%</strong></p>
                </div>
            <?php endif; ?>
            
            <form method="post">
                <button type="submit" name="action" value="continue" class="choice-btn">Return to the cabin</button>
            </form>
            
        <?php elseif($_SESSION['part4_stage'] == 'sleep'): ?>
            <h2>Night Falls</h2>
            <p>The forest becomes dark as you make your way back to the cabin.</p>
            
            <img src="images/nightcabin.gif" class="scene-img">
            
            <p>You will travel to the ranger station which is indicated on the handbook map tomorrow. The distance scale points to a one-day journey to reach this location. With luck, you'll find rescue there.</p>
            <p>For now, you need to rest and recover your strength for the challenges that lie ahead.</p>
            <form method="post">
                <button type="submit" name="action" value="sleep" class="choice-btn">Sleep until morning</button>
            </form>
            
            <?php elseif($_SESSION['part4_stage'] == 'game_over'): ?>
            <div class="game-over">
                <h2>Game Over</h2>
                <p>Your injuries were too severe to continue. Weakened and unable to go on, you collapse in the wilderness. Your vision fades as darkness closes in...</p>
                <img src="images/game_over.jpg" class="scene-img">
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