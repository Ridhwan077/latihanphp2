<?php
session_start();
include 'config.php'; // koneksi database
include_once 'includes/functions.php';

if (!isset($_SESSION['member_id'])) {
    header("Location: index.php");
    exit;
}

// Pastikan ada parameter id di URL
if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$post_id = intval($_GET['id']); // hindari SQL injection

// Query untuk mengambil data postingan beserta nama pembuatnya
$sql = "SELECT posts.*, members.username 
        FROM posts 
        JOIN members ON posts.member_id = members.member_id
        WHERE posts.post_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();

// Jika postingan tidak ditemukan
if ($result->num_rows === 0) {
    echo "<p>Postingan tidak ditemukan</p>";
    exit();
}

$post = $result->fetch_assoc();
$imagePath = htmlspecialchars($post['image_path']);
$orientation = 'landscape';

if (file_exists($imagePath)) {
    $size = @getimagesize($imagePath);
    if ($size && isset($size[0], $size[1])) {
        $orientation = ($size[0] < $size[1]) ? 'portrait' : 'landscape';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php include 'includes/head.php'; ?>
  <link rel="stylesheet" href="assets/css/style_viewpost.css?v=1.0">
</head>

<body>
  <?php include 'includes/menu.php'; ?>
  <a href="dashboard.php" class="back-button">← Kembali ke Homepage</a>

  <div class="post">
    <div class="post-header">
    <p class="post-date"><?php echo htmlspecialchars($post['created_at']); ?></p>
    <h2 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h2>
    <p class="post-author">by <?php echo htmlspecialchars($post['username']); ?></p>
    </div>


    <?php if (!empty($post['image_path'])): ?>
      <img src="<?php echo $imagePath; ?>" alt="Post Image" class="post-image <?php echo $orientation; ?>">
    <?php endif; ?>

    <div class="post-content-wrapper">
      <p class="post-content">
        <?php echo nl2br(htmlspecialchars($post['content'])); ?>
      </p>
    </div>

    <!-- Placeholder tombol Like -->
    <button id="like-btn" class="like-btn <?php echo userLiked($conn, $post['post_id'], $_SESSION['member_id']) ? 'liked' : ''; ?>"data-post-id="<?php echo $post['post_id']; ?>">
    ❤️ <span id="like-count"><?php echo getLikeCount($conn, $post['post_id']); ?></span>
    </button>
    <script>
    document.getElementById('like-btn').addEventListener('click', function() {
    const btn = this;
    const postId = this.dataset.postId;

    fetch('like_action.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'post_id=' + postId
    })
    .then(res => res.json())
    .then(data => {
        if (data.error) {
            alert('Anda harus login untuk memberi like.');
            return;
        }

        const count = document.getElementById('like-count');
        count.textContent = data.total;
        btn.classList.toggle('liked', data.liked);
    })
    .catch(err => console.error(err));
    });
    </script>

    <!-- Placeholder komentar -->
    <div class="comments-section">
    <h3>Komentar</h3>
   

    <form id="comment-form">
    <textarea name="comment_text" id="comment-text" placeholder="Tulis komentar..." required></textarea>
    <button type="submit">Kirim</button>
    </form>
    <div id="comments-list">
    <!-- Komentar akan muncul di sini -->
    </div>
  </div>
  <script>
const postId = <?= $post['post_id']; ?>;
const commentsList = document.getElementById('comments-list');
const commentForm = document.getElementById('comment-form');
const commentContent = document.getElementById('comment-text');

// Fungsi render komentar
function renderComments(comments) {
    commentsList.innerHTML = '';

    comments.forEach(c => {
        const div = document.createElement('div');
        div.classList.add('comment');

        // Buat isi komentar
        div.innerHTML = `<strong>${c.username}</strong> (${c.created_at}): 
                         <p>${c.comment_text}</p>`;

        // Jika user bisa menghapus, tambahkan tombol delete
        if(c.can_delete) {
            const delBtn = document.createElement('button');
            delBtn.textContent = 'Hapus';
            delBtn.classList.add('delete-comment-btn');
            delBtn.setAttribute('data-comment-id', c.comment_id);
            div.appendChild(delBtn);

            // Event listener untuk tombol hapus
            delBtn.addEventListener('click', () => {
                if(confirm('Yakin ingin menghapus komentar ini?')) {
                    fetch('comment_delete.php', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                        body: 'comment_id=' + c.comment_id
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            renderComments(data.comments); // render ulang komentar
                        } else {
                            alert(data.error);
                        }
                    })
                    .catch(err => console.error(err));
                }
            });
        }

        commentsList.appendChild(div);
    });
}

// Submit komentar
commentForm.addEventListener('submit', e => {
    e.preventDefault();
    const content = commentContent.value.trim();
    if (!content) return;

    fetch('comment_action.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'post_id=' + postId + '&comment_text=' + encodeURIComponent(commentContent.value)
    })
    .then(res => res.json())
    .then(data => {
        if (data.error) {
            alert(data.error);
            return;
        }
        renderComments(data);
        commentContent.value = '';
    })
    .catch(err => console.error(err));
});

// Polling komentar tiap 0,5 detik
setInterval(() => {
    fetch('comment_fetch.php?post_id=' + postId)
    .then(res => res.json())
    .then(data => renderComments(data))
    .catch(err => console.error(err));
}, 500);
</script>



  </div>

  <?php include 'includes/footer.php'; ?>
</body>
</html>
