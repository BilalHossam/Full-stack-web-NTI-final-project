<?php
session_start();
require_once 'config/config.php';
require_once 'includes/functions.php';

// Check if user is logged in
$logged_in = isset($_SESSION['user_id']);
$user_name = $logged_in ? get_user_name($conn, $_SESSION['user_id']) : '';

$posts = get_all_posts($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog</title>
    <link rel="stylesheet" href="./css/css_styles.css">
 
</head>
<body>
    <header>
        <h1>Blog</h1>
        <nav>
            <?php if ($logged_in): ?>
                <span>Welcome, <?php echo htmlspecialchars($user_name); ?>!</span>
                <a href="create_post.php">New Post</a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            <?php endif; ?>
        </nav>
    </header>

    <main>
        <h2>Recent Posts</h2>
        <?php foreach ($posts as $post): ?>
            <article>
                <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                <p><?php echo substr(htmlspecialchars($post['content']), 0, 200); ?>...</p>
                <p>Posted by <?php echo htmlspecialchars($post['username']); ?> on <?php echo $post['created_at']; ?></p>
                <a href="post.php?id=<?php echo $post['id']; ?>">Read More</a>
                <?php if ($logged_in && $post['user_id'] == $_SESSION['user_id']): ?>
                    <a href="edit_post.php?id=<?php echo $post['id']; ?>">Edit</a>
                    <a href="delete_post.php?id=<?php echo $post['id']; ?>" class="delete-post">Delete</a>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
    </main>
    <script src="./js/js_scripts.js"></script>
</body>
</html>