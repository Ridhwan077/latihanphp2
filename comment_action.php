<?php
session_start();
include 'config.php';
include_once 'includes/functions.php';
// ini_set('display_errors', 0);
// ini_set('log_errors', 1);
// ini_set('error_log', __DIR__ . '/error_log.txt');

header('Content-Type: application/json');

if (!isset($_SESSION['member_id'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit;
}


$member_id = $_SESSION['member_id'];
$post_id = intval($_POST['post_id']);
$comment_text = trim($_POST['comment_text']);
//$can_delete = ($row['member_id'] == $_SESSION['member_id']);

if ($comment_text === '') {
    echo json_encode(['error' => 'Komentar kosong']);
    exit;
}

// Insert komentar
$stmt = $conn->prepare("INSERT INTO comments (post_id, member_id, comment_text) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $post_id, $member_id, $comment_text);
$stmt->execute();

// Ambil komentar terbaru
$comments = getComments($conn, $post_id); // pastikan getComments sudah JOIN ke members untuk ambil username
echo json_encode($comments);
exit;
