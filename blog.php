<?php
session_start();
include 'db.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$username = $_SESSION["username"];

// ✅ Fetch only posts created by the logged-in user
$sql = "SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Blog</title>
</head>
<body>
    <h2>🗂 My Blog</h2>

    <p>
        <a href="post_create.php">➕ Create New Post</a> |
        <a href="dashboard.php">← Back to Dashboard</a>
    </p>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
                <h3><?php echo htmlspecialchars($row["title"]); ?></h3>
                <p><?php echo nl2br(htmlspecialchars($row["content"])); ?></p>
                <small>By: <?php echo htmlspecialchars($username); ?> | On: <?php echo $row["created_at"]; ?></small><br>
                <a href="post_edit.php?id=<?php echo $row["id"]; ?>">Edit</a> |
                <a href="post_delete.php?id=<?php echo $row["id"]; ?>" onclick="return confirm('Delete this post?')">Delete</a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>You haven’t posted anything yet.</p>
    <?php endif; ?>
</body>
</html>
