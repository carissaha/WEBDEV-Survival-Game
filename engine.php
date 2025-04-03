<?php
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'stay') {
        header("Location: gave_up.php");
        exit();
    }
    
    if (isset($_GET['signal'])) {
        if ($_GET['signal'] == 'one') {
            if ($_GET['action'] == 'get_up') {
                header("Location: missed.php?signal=one");
                exit();
            }
        } elseif ($_GET['signal'] == 'both') {
            header("Location: rescued.php");
            exit();
        }
    }

    if ($_GET['action'] == 'skeleton') {
        header("Location: missed.php?action=skeleton");
        exit();
    }
}
?>