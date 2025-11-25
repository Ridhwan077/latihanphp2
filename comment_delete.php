<?php
session_start();
include 'config.php';
include_once 'includes/functions.php';
header('Content-Type: application/json');

if (!isset($_SESSION['member_id'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$member_id = $_SESSION['member_id'];
$comment_id = intval($_POST['comment_id']);

// Cek apakah komentar milik user
$stmt = $conn->prepare("SELECT * FROM comments WHERE comment_id = ? AND member_id = ?");
$stmt->bind_param("ii", $comment_id, $member_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows === 0) {
    echo json_encode(['error' => 'Anda tidak bisa menghapus komentar ini']);
    exit;
}

// Hapus komentar
$stmt = $conn->prepare("DELETE FROM comments WHERE comment_id = ?");
$stmt->bind_param("i", $comment_id);
$stmt->execute();

// Ambil komentar terbaru untuk render ulang
$comments = getComments($conn, $_POST['post_id'] ?? 0); 
echo json_encode(['success' => true, 'comments' => $comments]);
