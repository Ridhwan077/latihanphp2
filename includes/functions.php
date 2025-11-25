<?php
// function greet($name) {
//     return "Hello, $name! Welcome to my website.";
// }

function userLiked($post_id, $member_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM likes WHERE member_id = ? AND post_id = ?");
    $stmt->bind_param("ii", $member_id, $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

function getLikeCount($conn, $post_id) {
    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM likes WHERE post_id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['total'];
}
function getComments($conn, $post_id) {
    $stmt = $conn->prepare("
        SELECT c.comment_text, c.created_at, m.username
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
        $comments[] = [
            'comment_text' => $row['comment_text'],
            'username' => $row['username'],
            'created_at' => $row['created_at']
        ];
    }
    return $comments;
}
// function getComments($conn, $post_id) {
//     $stmt = $conn->prepare("
//         SELECT comments.*, members.username 
//         FROM comments 
//         JOIN members ON comments.member_id = members.member_id
//         WHERE post_id = ?
//         ORDER BY created_at ASC
//     ");
//     $stmt->bind_param("i", $post_id);
//     $stmt->execute();
//     return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
// }


?>
