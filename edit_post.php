<?php
session_start();
require_once 'config/config.php';
require_once 'includes/functions.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$post_id = $_GET['id'];
$post = get_post($conn, $post_id);

if (!$post || $post['user_id'] != $_SESSION['user_id']) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    
    if (update_post($conn, $post_id, $title, $content)) {
        header('Location: post.php?id=' . $post_id);
        exit;
    } else {
        $error = "Failed to update post";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <link rel="stylesheet" href="./css/css_styles.css">
    
</head>
<body>
    <header>
        <h1>Edit Post</h1>
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
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
            </div>
            <div>
                <label for="content">Content:</label>
                <textarea id="content" name="content" required><?php echo htmlspecialchars($post['content']); ?></textarea>
            </div>
            <button type="submit">Update Post</button>
        </form>
    </main>
    <script src="./js/js_scripts.js"></script>
</body>
</html>