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

// Delete comments associated with the post
$stmt = $conn->prepare("DELETE FROM comments WHERE post_id = ?");
$stmt->execute([$post_id]);

// Delete the post
$stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
$stmt->execute([$post_id]);

header('Location: index.php');
exit;
?>