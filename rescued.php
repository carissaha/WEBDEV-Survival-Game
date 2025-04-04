<?php 
session_start(); 

if(isset($_SESSION['game_start_time'])) {
    $completionTime = time() - $_SESSION['game_start_time'];
    
    if(isset($_SESSION['logged_in'])) {
        $username = $_SESSION['username'];
        $displayName = $_SESSION['user_name'];
        
        if(!isset($_SESSION['leaderboard'])) {
            $_SESSION['leaderboard'] = array();
        }
        
        $_SESSION['leaderboard'][] = array(
            'username' => $username,
            'name' => $displayName,
            'time' => $completionTime,
            'date' => date('Y-m-d')
        );
        
        usort($_SESSION['leaderboard'], function($a, $b) {
            return $a['time'] - $b['time'];
        });
        
        if(count($_SESSION['leaderboard']) > 10) {
            array_splice($_SESSION['leaderboard'], 10);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rescued!</title>
    <link rel="stylesheet" href="part6.css">
    <style>
        .leaderboard {
            background-color: #2a2a2a;
            border-radius: 10px;
            padding: 15px;
            margin: 20px auto;
            max-width: 90%;
            box-shadow: 0 5px 15px rgba(0,0,0,0.7);
        }

        .leaderboard h2 {
            color: #ff9933;
            text-align: center;
            margin-bottom: 15px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }

        .leaderboard table {
            width: 100%;
            border-collapse: collapse;
            color: white;
        }

        .leaderboard th {
            background-color: #334455;
            color: white;
            padding: 10px 8px;
            text-align: left;
            border-bottom: 2px solid #444;
        }

        .leaderboard td {
            padding: 10px 8px;
            border-bottom: 1px solid #444;
        }
        
        .your-rank {
            background-color: #3d5a80;
        }
        
        .your-time {
            background-color: #334455;
            padding: 10px;
            margin: 15px 0;
            border-radius: 5px;
            border-left: 5px solid #ffd700;
            color: white;
            text-align: center;
        }
        
        .your-time h3 {
            color: #ffd700; 
            margin-top: 0;
        }
    </style>
</head>
<body>
    <div class="container ending">
        <img id="scene" src="images/rescued.gif" alt="rescue">
        <h2>You're Saved!</h2>
        <div id="text-box">
            <p>The helicopter recognizes your distress signal and lands nearby.</p>
            <p>As you board, you see civilization in the distance. You made it!</p>
            
            <?php if(isset($_SESSION['game_start_time'])): ?>
            <div class="your-time">
                <h3>Your Completion Time</h3>
                <p><?php 
                    $time = time() - $_SESSION['game_start_time'];
                    echo floor($time/60).' minutes '.($time % 60).' seconds'; 
                ?></p>
            </div>
            <?php endif; ?>
            
            <?php if(isset($_SESSION['leaderboard']) && !empty($_SESSION['leaderboard'])): ?>
            <div class="leaderboard">
                <h2>Fastest Survival Times</h2>
                <table>
                    <tr>
                        <th>Rank</th>
                        <th>Player</th>
                        <th>Time</th>
                        <th>Date</th>
                    </tr>
                    <?php 
                    foreach($_SESSION['leaderboard'] as $index => $entry): 
                        $rowClass = '';
                        if (isset($_SESSION['username'], $_SESSION['leaderboard'][$index]['time'], $_SESSION['game_start_time'])) {
                            $currentTime = time() - $_SESSION['game_start_time'];
                            if ($_SESSION['username'] === $entry['username'] && $entry['time'] === $currentTime) {
                            $rowClass = 'your-rank';
                             }
                        }
                        ?>
                        <tr class="<?php echo $rowClass; ?>">
                            <td><?php echo ($index + 1); ?></td>
                            <td><?php echo htmlspecialchars($entry['name']); ?></td>
                            <td><?php echo floor($entry['time']/60).'m '.($entry['time'] % 60).'s'; ?></td>
                            <td><?php echo $entry['date']; ?></td>
                        </tr>
                    <?php 
                    endforeach;
                    ?>
                </table>
            </div>
            <?php endif; ?>
            
            <div class="choices">
            <a href="reset.php" class="choice-btn">Play Again</a>              
            <?php if(isset($_SESSION['logged_in'])): ?>
                <a href="login.php?logout=1" class="button">Logout</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>