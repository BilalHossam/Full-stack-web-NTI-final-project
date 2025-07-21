<?php
function register_user($conn, $username, $password) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    return $stmt->execute([$username, $hashed_password]);
}

function login_user($conn, $username, $password) {
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        return $user['id'];
    }
    return false;
}

function get_all_posts($conn) {
    $stmt = $conn->query("SELECT posts.id, posts.title, posts.content, posts.created_at, users.username, posts.user_id 
                         FROM posts JOIN users ON posts.user_id = users.id 
                         ORDER BY posts.created_at DESC");
    return $stmt->fetchAll();
}

function get_post($conn, $id) {
    $stmt = $conn->prepare("SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.id WHERE posts.id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function get_user_name($conn, $user_id) {
    $stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetchColumn();
}

function add_post($conn, $title, $content, $user_id) {
    $stmt = $conn->prepare("INSERT INTO posts (title, content, user_id) VALUES (?, ?, ?)");
    return $stmt->execute([$title, $content, $user_id]);
}

function update_post($conn, $id, $title, $content) {
    $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
    return $stmt->execute([$title, $content, $id]);
}

function add_comment($conn, $post_id, $user_id, $content) {
    $stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)");
    return $stmt->execute([$post_id, $user_id, $content]);
}

function get_comments($conn, $post_id) {
    $stmt = $conn->prepare("SELECT comments.*, users.username FROM comments JOIN users ON comments.user_id = users.id 
                           WHERE comments.post_id = ? ORDER BY comments.created_at DESC");
    $stmt->execute([$post_id]);
    return $stmt->fetchAll();
}
?>