<?php
session_start();
include 'db.php';

// Ensure user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$message = '';
$post_id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

// Get the post to edit
$stmt = $conn->prepare("SELECT * FROM posts WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $post_id, $_SESSION["user_id"]);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows != 1) {
    echo "Post not found or unauthorized access.";
    exit();
}

$post = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_title = trim($_POST["title"]);
    $new_content = trim($_POST["content"]);

    $update = $conn->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ? AND user_id = ?");
    $update->bind_param("ssii", $new_title, $new_content, $post_id, $_SESSION["user_id"]);

    if ($update->execute()) {
        $message = "Post updated successfully!";
        $post["title"] = $new_title;
        $post["content"] = $new_content;
    } else {
        $message = "Update failed: " . $update->error;
    }
    $update->close();
}
?>

<!DOCTYPE html>
<html>
<head><title>Edit Post</title></head>
<body>
    <h2>Edit Your Post</h2>
    <p style="color:green;"><?php echo $message; ?></p>

    <form method="post" action="">
        <label>Title:</label><br>
        <input type="text" name="title" value="<?php echo htmlspecialchars($post["title"]); ?>" required><br><br>

        <label>Content:</label><br>
        <textarea name="content" rows="5" cols="40" required><?php echo htmlspecialchars($post["content"]); ?></textarea><br><br>

        <button type="submit">Update Post</button>
    </form>
    <p><a href="blog.php">← Back to Blog</a></p>
</body>
</html>
