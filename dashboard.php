<?php
session_start();
include 'db.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION["username"];

// Fetch all posts from all users
$sql = "SELECT posts.*, users.username 
        FROM posts 
        JOIN users ON posts.user_id = users.id 
        ORDER BY posts.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head><title>Dashboard</title></head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
    <p>This is your dashboard.</p>

    <p>
        <a href="post_create.php">➕ Create New Post</a> |
        <a href="blog.php">🗂 My Blog</a> |
        <a href="logout.php">🚪 Logout</a>
    </p>

    <h2>📋 All Blog Posts</h2>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
                <h3><?php echo htmlspecialchars($row["title"]); ?></h3>
                <p><?php echo nl2br(htmlspecialchars($row["content"])); ?></p>
                <small>By: <?php echo htmlspecialchars($row["username"]); ?> | On: <?php echo $row["created_at"]; ?></small>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No posts found.</p>
    <?php endif; ?>
</body>
</html>
