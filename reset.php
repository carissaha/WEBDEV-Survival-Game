<?php
session_start();
unset($_SESSION['part2_stage']);
unset($_SESSION['part3_stage']);
unset($_SESSION['part4_stage']);
unset($_SESSION['part5_stage']);
unset($_SESSION['inventory']);
unset($_SESSION['rescued']);
unset($_SESSION['game_start_time']);
header("Location: homepage.html");
exit;