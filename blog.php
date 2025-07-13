<?php
session_start();
include 'db.php';

// Get all posts from database
$sql = "SELECT posts.*, users.username FROM posts 
        JOIN users ON posts.user_id = users.id 
        ORDER BY posts.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Blog - All Posts</title>
</head>
<body>
    <h2>All Blog Posts</h2>

    <p>
    <a href="post_create.php">➕ Create New Post</a> |
</p>
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div style="border:1px solid #ccc; padding:10px; margin:10px 0;">
    <h3><?php echo htmlspecialchars($row["title"]); ?></h3>
    <p><?php echo nl2br(htmlspecialchars($row["content"])); ?></p>
    <small>By: <?php echo $row["username"]; ?> | On: <?php echo $row["created_at"]; ?></small><br>

    <?php if (isset($_SESSION["user_id"]) && $_SESSION["user_id"] == $row["user_id"]): ?>
        <a href="post_edit.php?id=<?php echo $row["id"]; ?>">Edit</a> |
        <a href="post_delete.php?id=<?php echo $row["id"]; ?>" onclick="return confirm('Delete this post?')">Delete</a>
    <?php endif; ?>
</div>
            <!-- <div style="border:1px solid #ccc; padding:10px; margin:10px 0;">
                <h3><?php echo htmlspecialchars($row["title"]); ?></h3>
                <p><?php echo nl2br(htmlspecialchars($row["content"])); ?></p>
                <small>By: <?php echo $row["username"]; ?> | On: <?php echo $row["created_at"]; ?></small>
            </div> -->
        <?php endwhile; ?>
    <?php else: ?>
        <p>No posts found.</p>
    <?php endif; ?>

    <p>
    <a href="dashboard.php">← Back to Dashboard</a>
</p>

</body>
</html>
