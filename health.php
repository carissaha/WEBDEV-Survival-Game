<?php
if (!isset($_SESSION['health'])) {
    $_SESSION['health'] = 100;
}

function increaseHealth($amount) {
    $_SESSION['health'] += $amount;
    if ($_SESSION['health'] > 100) $_SESSION['health'] = 100;
}

function decreaseHealth($amount) {
    $_SESSION['health'] -= $amount;
    if ($_SESSION['health'] <= 0) {
        $_SESSION['health'] = 0;
        $_SESSION['game_over'] = true;
    }
}

function getHealth() {
    return $_SESSION['health'];
}

function isGameOver() {
    return isset($_SESSION['game_over']) && $_SESSION['game_over'] === true;
}

function resetHealth() {
    $_SESSION['health'] = 100;
    unset($_SESSION['game_over']);
}

function displayHealthBar() {
    $total = 10;
    $filled = floor($_SESSION['health'] / 10);
    $half = ($_SESSION['health'] % 10 >= 5) ? 1 : 0;
    $empty = $total - $filled - $half;

    echo '<div style="font-size: 24px; margin: 10px 0;">';
    for ($i = 0; $i < $filled; $i++) {
        echo '❤️';
    }
    if ($half) {
        echo '💔'; 
    }
    for ($i = 0; $i < $empty; $i++) {
        echo '🖤';
    }
    echo '</div>';
}
?>