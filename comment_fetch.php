<?php
include 'config.php';
include_once 'includes/functions.php';

header('Content-Type: application/json');

if (!isset($_GET['post_id'])) exit;

$post_id = intval($_GET['post_id']);
// Ambil komentar dari database
$stmt = $conn->prepare("
    SELECT c.comment_id, c.comment_text, c.member_id, c.created_at, m.username
    FROM comments c
    JOIN members m ON c.member_id = m.member_id
    WHERE c.post_id = ?
    ORDER BY c.created_at ASC
");
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();

$comments = [];
while ($row = $result->fetch_assoc()) {
    
    $can_delete = false;
    if (isset($_SESSION['member_id']) && $_SESSION['member_id'] == $row['member_id']) {
        $can_delete = true;
    }

    $comments[] = [
        'comment_id' => $row['comment_id'],
        'comment_text' => $row['comment_text'],
        'username' => $row['username'],
        'created_at' => $row['created_at'],
        'can_delete' => $can_delete
    ];
}

echo json_encode($comments);