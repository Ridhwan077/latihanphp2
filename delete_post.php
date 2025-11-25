<?php
session_start();
include 'config.php';

if (!isset($_SESSION['member_id'])) {
    die("Akses ditolak. Anda harus login.");
}

if (!isset($_GET['id'])) {
    die("Post ID tidak ditemukan.");
}

$post_id = intval($_GET['id']);
$member_id = $_SESSION['member_id'];

// 🔒 Verifikasi bahwa user adalah pemilik post
$stmt = $conn->prepare("SELECT * FROM posts WHERE post_id = ? AND member_id = ?");
$stmt->bind_param("ii", $post_id, $member_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Jika bukan pemilik, tampilkan pesan error
    die("Anda tidak memiliki izin untuk menghapus postingan ini.");
}

//  Hapus post
$stmt = $conn->prepare("DELETE FROM posts WHERE post_id = ? AND member_id = ?");
$stmt->bind_param("ii", $post_id, $member_id);

if ($stmt->execute()) {
    // Jika ada file gambar terkait, hapus juga dari folder uploads
    $oldPost = $result->fetch_assoc();
    if (!empty($oldPost['image_path'])) {
        $filePath = $oldPost['image_path'];
        if (file_exists($filePath)) unlink($filePath);
    }

    header("Location: profile.php?msg=deleted");
    exit;
} else {
    echo "Gagal menghapus postingan.";
}
?>
