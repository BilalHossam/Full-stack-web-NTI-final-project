<?php
session_start();
require_once 'config/config.php';
require_once 'includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    
    if (add_post($conn, $title, $content, $_SESSION['user_id'])) {
        header('Location: index.php');
        exit;
    } else {
        $error = "Failed to create post";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
    <link rel="stylesheet" href="./css/css_styles.css">
  
</head>
<body>
    <header>
        <h1>Create New Post</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        
        <form method="post" action="">
            <div>
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div>
                <label for="content">Content:</label>
                <textarea id="content" name="content" required></textarea>
            </div>
            <button type="submit">Create Post</button>
        </form>
    </main>
    <script src="./js/js_scripts.js"></script>
</body>
</html>