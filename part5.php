<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

include('health.php');
include('inventorypt23.php');

if(!isset($_SESSION['part5_stage'])) {
    $_SESSION['part5_stage'] = 'day3_start';
}

if(isset($_POST['action'])) {
    $action = $_POST['action'];
    
    if($_SESSION['part5_stage'] == 'day3_start' && $action == 'start_journey') {
        $_SESSION['part5_stage'] = 'forest_journey';
    }
    
    elseif($_SESSION['part5_stage'] == 'forest_journey' && $action == 'continue') {
        $_SESSION['part5_stage'] = 'bear_encounter';
    }
    
    elseif($_SESSION['part5_stage'] == 'bear_encounter') {
        if($action == 'stay_still') {
            $chance = rand(1, 4); 
            if($chance <= 3) {
                $_SESSION['part5_stage'] = 'bear_consequence';
                $_SESSION['choice_made'] = 'stay_still_success';
            } else {
                decreaseHealth(30);
                if(isGameOver()){
                    $_SESSION['part5_stage'] = 'game_over';
                    header("Location: part5.php");
                    exit;
                }
                $_SESSION['part5_stage'] = 'bear_consequence';
                $_SESSION['choice_made'] = 'stay_still_failure';
            }
        }
        elseif($action == 'scare_bear') {
            $_SESSION['part5_stage'] = 'bear_consequence';
            $_SESSION['choice_made'] = 'scare_bear_success';
        }
        elseif($action == 'run_away') {
            decreaseHealth(35);
            if(isGameOver()){
                $_SESSION['part5_stage'] = 'game_over';
                header("Location: part5.php");
                exit;
            } 
            $_SESSION['part5_stage'] = 'bear_consequence';
            $_SESSION['choice_made'] = 'run_away_failure';
        }
    }
    
    elseif($_SESSION['part5_stage'] == 'bear_consequence' && $action == 'continue') {
        // Only if a failure occurred, move to the injury stage.
        if($_SESSION['choice_made'] == 'stay_still_failure' || $_SESSION['choice_made'] == 'run_away_failure') {
            $_SESSION['part5_stage'] = 'bear_injury';
        } else {
            $_SESSION['part5_stage'] = 'mysterious_box';
        }
    }
    
    elseif($_SESSION['part5_stage'] == 'bear_injury' && $action == 'continue') {
        increaseHealth(20);
        $_SESSION['part5_stage'] = 'mysterious_box';
    }
    
    elseif($_SESSION['part5_stage'] == 'mysterious_box') {
        if($action == 'open_box') {
            decreaseHealth(40);
            if(isGameOver()){
                $_SESSION['part5_stage'] = 'game_over';
                header("Location: part5.php");
                exit;
            }
            $_SESSION['part5_stage'] = 'box_consequence';
            $_SESSION['choice_made'] = 'opened_box';
        }
        elseif($action == 'leave_box') {
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
  <link rel="stylesheet" href="part2and3.css">
  <style>
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
          content: "\"";
          font-size: 3em;
          color: #ffcc00;
          position: absolute;
          left: 10px;
          top: -10px;
      }
  </style>
</head>
<body>

  <div class="container">
      <?php displayHealthBar(); ?>
      <?php displayInventory(); ?>
      <div class="day-indicator">Day 3</div>

      <h1>Survival Challenge: Day 3</h1>
      
      <?php if($_SESSION['part5_stage'] == 'day3_start'): ?>
            <audio autoplay loop>
                    <source src="audio/birds.mp3" type="audio/mp3">
            </audio>

          <h2>A New Dawn of Hope</h2>
          <p>Sunlight pours through the wooden wall cracks as you awake inside the cabin. The restful sleep you had last night left you feeling both refreshed and determined. Today could be the day of rescue.</p>
          
          <img src="images/morningcabin.gif" class="scene-img">
          
          <p>The map from the handbook shows the location of a ranger station on a hilltop several miles away. According to the notes, it's a designated rescue point with radio equipment and supplies.</p>
          
          <p>The co-pilot studies the map carefully. "During my flight training I learned about this terrain so I recognize it now. Sticking to the ridgeline gives us a clear path around dense underbrush to reach our destination faster," he says.</p>
          
          <p>You gather your limited supplies, including some leftover berries and water. The journey ahead will take most of the day, but if you can reach the station, you might finally be rescued.</p>
          <form method="post">
              <button type="submit" name="action" value="start_journey" class="choice-btn">Begin the journey to the ranger station</button>
          </form>
          
      <?php elseif($_SESSION['part5_stage'] == 'forest_journey'): ?>
            <audio autoplay loop>
                    <source src="audio/trees.mp3" type="audio/mp3">
            </audio>
          <h2>Through the Wilderness</h2>
          <p>Following the map from the handbook, you make steady progress through the dense forest. The terrain becomes increasingly steep as you navigate toward the hilltop ranger station.</p>
          
          <img src="images/denseforest.gif" class="scene-img">
          
          <p>The co-pilot leads the way with confidence, finding the easiest paths through the rough terrain.</p>
          <p>"We're making good time," he remarks, checking the sun's position. "If we keep this pace, we should reach the station well before sunset."</p>
          
          <p>You pause briefly to rest and consume some berries from your previous day's collection. According to your map, you have passed the halfway mark.</p>
          <form method="post">
              <button type="submit" name="action" value="continue" class="choice-btn">Continue your journey</button>
          </form>
          
      <?php elseif($_SESSION['part5_stage'] == 'bear_encounter'): ?>
            <audio autoplay loop>
                <source src="audio/stranded-intro.mp3" type="audio/mp3">
            </audio>
          <h2>Unexpected Danger</h2>
          <p>You stop dead in your tracks when you turn a corner along the trail. A large black bear is investigating a fallen log just thirty feet away. The wind is blowing toward you, but the bear remains unaware—for now.</p>
          
          <img src="images/bear.gif"  class="scene-img">
          
          <div class="pilot-hint">
              <p>The co-pilot grabs your arm and whispers urgently: "Bears usually avoid confrontation. Do not run or play dead. Make yourself seem big and loud. I'll help you if you stay calm."</p>
          </div>
          
          <p>Your heart races as you consider your options. You must decide quickly before the bear notices you.</p>
          <form method="post">
              <button type="submit" name="action" value="stay_still" class="choice-btn">1️⃣ Stay perfectly still and hope it leaves (75% chance of success)</button>
              <button type="submit" name="action" value="scare_bear" class="choice-btn">2️⃣ Try to scare it away by making noise and looking big</button>
              <button type="submit" name="action" value="run_away" class="choice-btn">3️⃣ Turn and run away as fast as possible</button>
          </form>
          
      <?php elseif($_SESSION['part5_stage'] == 'bear_consequence'): ?>
          <h2>Bear Encounter Outcome</h2>
          
          <?php if($_SESSION['choice_made'] == 'stay_still_success'): ?>
              <div class="consequence-box positive">
                  <h3>The bear moved on</h3>
                  <p>You remain utterly still, holding your breath as the bear ambles away. Once it is out of sight, you exhale slowly and proceed with extreme caution.</p>
                  <img src="images/bearpass.gif" class="scene-img">
              </div>
          <?php elseif($_SESSION['choice_made'] == 'stay_still_failure'): ?>
            <audio autoplay loop>
                <source src="audio/stranded-intro.mp3" type="audio/mp3">
            </audio>
              <div class="consequence-box negative">
                  <h3>The bear noticed you</h3>
                  <p>Your stillness wasn't enough. The bear catches your scent and charges. The co-pilot rushes in with a branch to distract it, but you sustain injuries during the frantic encounter.</p>
                  <p><span class="health-change health-down">Health decreased by 30%</span></p>
                  <img src="images/bearchase.gif" class="scene-img">
              </div>
          <?php elseif($_SESSION['choice_made'] == 'scare_bear_success'): ?>
              <div class="consequence-box positive">
                  <h3>The bear retreated</h3>
                  <p>The co-pilot and you shout and wave your arms. Startled, the bear rears up on its hind legs and then retreats, leaving you unharmed.</p>
                  <img src="images/bearrun.gif" class="scene-img">
              </div>
          <?php elseif($_SESSION['choice_made'] == 'run_away_failure'): ?>
              <div class="consequence-box negative">
                  <h3>The bear chased you</h3>
                  <p>Panic overtakes you as you run. The bear gives chase and, although the co-pilot manages to distract it momentarily, you suffer severe injuries during the escape.</p>
                  <p><span class="health-change health-down">Health decreased by 45%</span></p>
                  <img src="images/bearchasee.jpg" class="scene-img">
              </div>
          <?php endif; ?>
          
          <form method="post">
              <button type="submit" name="action" value="continue" class="choice-btn">Continue your journey</button>
          </form>
          
      <?php elseif($_SESSION['part5_stage'] == 'bear_injury'): ?>
        <audio autoplay loop>
            <source src="audio/stranded-intro.mp3" type="audio/mp3">
        </audio>
          <h2>Tending to Injuries</h2>
          <p>The bear attack leaves you battered and bleeding. Your vision blurs as you try to gather strength.</p>
          
          <div class="consequence-box positive">
              <h3>Expert first aid</h3>
              <p>The co-pilot tears his shirt into strips and quickly bandages your wounds. "You saved me once; now let me help you," he says as he tends to you.</p>
              <img src="images/helptreat.jpg" class="scene-img">
              <p><span class="health-change health-up">Health increased by 20%</span> - The timely first aid eases your pain considerably.</p>
          </div>
          
          <form method="post">
              <button type="submit" name="action" value="continue" class="choice-btn">Continue your journey</button>
          </form>
          
      <?php elseif($_SESSION['part5_stage'] == 'mysterious_box'): ?>
        <audio autoplay loop>
                <source src="audio/background.mp3" type="audio/mp3">
        </audio>
          <h2>A Strange Discovery</h2>
          <p>After surviving the bear encounter, you continue along the trail. Deep in the forest, you come upon an old, weathered wooden box hidden among tree roots.</p>
          
          <img src="images/box.jpg" class="scene-img">
          
          <p>Its ornate metal clasp gleams in the dappled sunlight. Although the co-pilot advises caution, your curiosity pulls you toward it.</p>
          <form method="post">
              <button type="submit" name="action" value="open_box" class="choice-btn">Open the mysterious box</button>
              <button type="submit" name="action" value="leave_box" class="choice-btn">Leave the box undisturbed and continue</button>
          </form>
      
      <?php elseif($_SESSION['part5_stage'] == 'box_consequence'): ?>
          <h2>The Box's Secret</h2>
          
          <?php if($_SESSION['choice_made'] == 'opened_box'): ?>
            <audio autoplay loop>
                <source src="audio/stranded-intro.mp3" type="audio/mp3">
            </audio>
              <div class="consequence-box negative">
                  <h3>A Painful Surprise</h3>
                  <p>Your curiosity gets the better of you. As you slowly open the box, a cloud of ancient dust and spores bursts forth, catching you off guard.</p>
                  <p>The co-pilot quickly covers your mouth and helps you move into fresh air, but not before you inhale some of the toxic cloud.</p>
                  <img src="images/facecover.gif" class="scene-img">
                  <span class="health-change health-down">Health decreased by 50%</span>
              </div>
          <?php else: ?>
              <div class="consequence-box positive">
                  <h3>Wisdom in Caution</h3>
                  <p>You decide not to risk it. The co-pilot nods in approval. "Sometimes it's best to leave mysteries unsolved," he remarks as you continue on.</p>
                  <img src="images/nope.gif" class="scene-img">
              </div>
          <?php endif; ?>
          
          <p>With the strange encounter behind you, you press on. According to your map, the rescue point is just ahead.</p>
          <form method="post">
              <button type="submit" name="action" value="continue" class="choice-btn">Continue to the rescue point</button>
          </form>
      
      <?php elseif($_SESSION['part5_stage'] == 'reach_rescue_point'): ?>
          <h2>The Final Stretch</h2>
          <p>The clearing ahead matches the location marked on your map. This elevated area is the designated rescue point—open and visible to search aircraft.</p>
          
          <img src="images/view.jpg" class="scene-img">
          
          <p>The co-pilot surveys the area. "This is perfect—clear in every direction. Tomorrow, we'll signal for help and finally get rescued."</p>
          <form method="post">
              <button type="submit" name="action" value="continue" class="choice-btn">Continue to the final chapter</button>
          </form>
      
      <?php elseif($_SESSION['part5_stage'] == 'game_over'): ?>
          <div class="game-over">
              <h2>Game Over</h2>
              <p>Your injuries were too severe to continue. Despite your best efforts, you collapse on the forest trail, and darkness claims you...</p>
              <img src="images/gameover.gif" class="scene-img">
              <p>Your survival adventure has come to an end.</p>
              <form method="post" action="reset.php">
                  <button type="submit" class="restart-btn">Try Again</button>
              </form>
          </div>
      <?php endif; ?>
      
  </div>
  
</body>
</html>