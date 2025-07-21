<?php
session_start();
require_once 'config/config.php';
require_once 'includes/functions.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$post_id = $_GET['id'];
$post = get_post($conn, $post_id);

if (!$post) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $content = $_POST['comment'];
    add_comment($conn, $post_id, $_SESSION['user_id'], $content);
}

$comments = get_comments($conn, $post_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?></title>
    <link rel="stylesheet" href="./css/css_styles.css">
   
</head>
<body>
    <header>
        <h1><?php echo htmlspecialchars($post['title']); ?></h1>
        <nav>
            <a href="index.php">Home</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </nav>
    </header>

    <main>
        <article>
            <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
            <p>Posted by <?php echo htmlspecialchars($post['username']); ?> on <?php echo $post['created_at']; ?></p>
            <?php if (isset($_SESSION['user_id']) && $post['user_id'] == $_SESSION['user_id']): ?>
                <p>
                    <a href="edit_post.php?id=<?php echo $post['id']; ?>">Edit</a>
                    <a href="delete_post.php?id=<?php echo $post['id']; ?>" class="delete-post">Delete</a>
                </p>
            <?php endif; ?>
        </article>

        <section>
            <h2>Comments</h2>
            <?php foreach ($comments as $comment): ?>
                <div class="comment">
                    <p><?php echo nl2br(htmlspecialchars($comment['content'])); ?></p>
                    <p>By <?php echo htmlspecialchars($comment['username']); ?> on <?php echo $comment['created_at']; ?></p>
                </div>
            <?php endforeach; ?>

            <?php if (isset($_SESSION['user_id'])): ?>
                <form method="post" action="" class="comment-form">
                    <div>
                        <label for="comment">Add Comment:</label>
                        <textarea id="comment" name="comment" required></textarea>
                    </div>
                    <button type="submit">Post Comment</button>
                </form>
            <?php else: ?>
                <p><a href="login.php">Login</a> to post a comment</p>
            <?php endif; ?>
        </section>
    </main>
    <script src="./js/js_scripts.js"></script>
</body>
</html>