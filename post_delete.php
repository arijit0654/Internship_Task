<?php
session_start();
include 'db.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$post_id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

// Delete only if the post belongs to the logged-in user
$stmt = $conn->prepare("DELETE FROM posts WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $post_id, $_SESSION["user_id"]);

if ($stmt->execute()) {
    header("Location: blog.php?msg=Post+deleted+successfully");
    exit();
} else {
    echo "Delete failed: " . $stmt->error;
}
?>
