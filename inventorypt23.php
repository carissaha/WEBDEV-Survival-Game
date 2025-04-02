<?php
session_start(); // Start the session to access $_SESSION

// Initialize inventory
if (!isset($_SESSION['inventory'])) {
    $_SESSION['inventory'] = []; // Set up an empty inventory array on first visit
}

// Function to display the inventory
function displayInventory() {
    // Check if the inventory has any items
    if (empty($_SESSION['inventory'])) {
        echo "<p>Your inventory is empty.</p>";
    } else {
        echo "<h2>Inventory</h2>";
        echo "<div class='inventory-container'>";
        
        // Loop through each item in the inventory
        foreach ($_SESSION['inventory'] as $item) {
            echo "<span class='inventory-item'>$item</span>";
        }
        
        echo "</div>";
    }
}

// Function to add an item to the inventory
function addItemToInventory($item) {
    if (!in_array($item, $_SESSION['inventory'])) {
        $_SESSION['inventory'][] = $item; // Add the item if it's not already in the inventory
    }
}

// Function to remove an item from the inventory
function removeItemFromInventory($item) {
    if (($key = array_search($item, $_SESSION['inventory'])) !== false) {
        unset($_SESSION['inventory'][$key]); // Remove item from inventory
        $_SESSION['inventory'] = array_values($_SESSION['inventory']); // Reindex the array
    }
}

// Function to reset the inventory (in case of a reset action)
function resetInventory() {
    $_SESSION['inventory'] = []; // Reset the inventory
}

// Action handlers for inventory updates
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'take_med_kit') {
        addItemToInventory("Med Kit");
    } elseif ($_POST['action'] == 'use_med_kit') {
        removeItemFromInventory("Med Kit"); // Remove med kit when used
    }
}
?>
