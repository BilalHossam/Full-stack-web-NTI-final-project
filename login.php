<?php
session_start();
require_once 'config/config.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $user_id = login_user($conn, $username, $password);
    
    if ($user_id) {
        $_SESSION['user_id'] = $user_id;
        header('Location: index.php');
        exit;
    } else {
        $error = "Invalid credentials";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="./css/css_styles.css">
   
</head>
<body>
    <header>
        <h1>Login</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="register.php">Register</a>
        </nav>
    </header>

    <main>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        
        <form method="post" action="">
            <div>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
    </main>
    <script src="./js/js_scripts.js"></script>
</body>
</html>