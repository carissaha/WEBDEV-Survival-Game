<?php
session_start();
if (!isset($_SESSION['users'])) {
    $_SESSION['users'] = array();
    
    $_SESSION['users']['test'] = array(
        'password' => 'password',
        'name' => 'Test User'
    );
}

$error = '';
$success = '';

if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $name = trim($_POST['name']);
    
    if (empty($username) || empty($password) || empty($name)) {
        $error = "All fields are required!";
    }elseif (isset($_SESSION['users'][$username])) {
        $error = "Username already exists!";
    } else {
        $_SESSION['users'][$username] = array(
            'password' => $password,
            'name' => $name
        );
        $success = "Registration successful! You can now log in.";
        
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['user_name'] = $name;
        
        header('Location: part1.html');
        exit;
    }
}

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    if(isset($_SESSION['users'][$username]) && $_SESSION['users'][$username]['password'] === $password) {
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['user_name'] = $_SESSION['users'][$username]['name'];
        
        header('Location: part1.html');
        exit;
    }else {
        $error = "Invalid username or password!";
    }
}

if (isset($_GET['logout'])) {
    unset($_SESSION['logged_in']);
    unset($_SESSION['username']);
    unset($_SESSION['user_name']);
    unset($_SESSION['part2_stage']);
    unset($_SESSION['part5_stage']);
    unset($_SESSION['rescued']);
    unset($_SESSION['inventory']);
    unset($_SESSION['game_start_time']);

    $success = "You have been logged out.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STRANDED - Login</title>
    <link rel="stylesheet" href="part2and3.css">
    <style>
        .login-container {
            max-width: 500px;
            margin: 50px auto;
            background-color: #2a2a2a;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.7);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #66ccff;
        }
        
        .form-group input {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #444;
            background-color: #333;
            color: #fff;
            font-size: 16px;
        }
        
        .error-message {
            background-color: #e74c3c;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .success-message {
            background-color: #2ecc71;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .game-title {
            text-align: center;
            color: #ff9933;
            margin-bottom: 30px;
            font-size: 2.5em;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }
        
        .section {
            border-top: 2px solid #444;
            padding-top: 20px;
            margin-top: 20px;
        }
        
        .section-title {
            color: #66ccff;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1 class="game-title">STRANDED</h1>
        
        <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="success-message"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <div>
            <h2 class="section-title">Login</h2>
            <form method="post">
                <div class="form-group">
                    <label for="login-username">Username</label>
                    <input type="text" id="login-username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="login-password">Password</label>
                    <input type="password" id="login-password" name="password" required>
                </div>
                
                <button type="submit" name="login" class="choice-btn">Login & Start Game</button>
            </form>
        </div>
        
        <div class="section">
            <h2 class="section-title">New Player? Register Here</h2>
            <form method="post">
                <div class="form-group">
                    <label for="reg-username">Username</label>
                    <input type="text" id="reg-username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="reg-password">Password</label>
                    <input type="password" id="reg-password" name="password" required>
                </div>
                
                <div class="form-group">
                    <label for="reg-name">Display Name</label>
                    <input type="text" id="reg-name" name="name" required>
                </div>
                
                <button type="submit" name="register" class="choice-btn">Register & Start Game</button>
            </form>
        </div>
    </div>
</body>
</html>