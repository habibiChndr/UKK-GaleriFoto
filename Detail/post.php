<?php
include "../connection.php";

if (!isset($_SESSION['user_id'])) {
    echo "Silakan login untuk melanjutkan.";
    exit;
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['post'])) {
    $post_id = $_GET['post'];

    // Query untuk detail postingan
    $query = "
        SELECT f.*, u.NamaLengkap, u.Username, u.ProfilePicture
        FROM foto f
        JOIN user u ON f.UserID = u.UserID
        WHERE f.FotoID = '$post_id'";
    $result = mysqli_query($connect, $query);
    $post = mysqli_fetch_array($result);

    if ($post) {
        $profile_picture = empty($post['ProfilePicture'])
            ? '../Asset/user.png'
            : "../Profile/ProfilePicture/{$post['ProfilePicture']}";

        // Cek status like
        $check_like = mysqli_query($connect, "SELECT * FROM likefoto WHERE FotoID='$post_id' AND UserID='$user_id'");
        $like_status = mysqli_num_rows($check_like) > 0;

        // Hitung jumlah like
        $like_count_result = mysqli_query($connect, "SELECT COUNT(*) AS total_likes FROM likefoto WHERE FotoID='$post_id'");
        $like_count = mysqli_fetch_assoc($like_count_result)['total_likes'] ?? 0;

        // Tambahkan like jika tombol diklik
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['like'])) {
            if (!$like_status) {
                $insert_like = mysqli_query($connect, "INSERT INTO likefoto (FotoID, UserID, TanggalLike) VALUES ('$post_id', '$user_id', NOW())");
            }
            header("Location: ?post=$post_id");
            exit;
        }

        // Tambahkan komentar jika tombol komentar diklik
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
            $comment_text = mysqli_real_escape_string($connect, $_POST['comment_text']);
            if (!empty($comment_text)) {
                $insert_comment = mysqli_query($connect, "INSERT INTO komentarfoto (FotoID, UserID, IsiKomentar, TanggalKomentar) VALUES ('$post_id', '$user_id', '$comment_text', NOW())");
            }
            header("Location: ?post=$post_id");
            exit;
        }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Post</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/fa4b6e3008.js" crossorigin="anonymous"></script>
</head>
<body class="bg-gray-200 font-sans flex items-center justify-center min-h-screen">
    <!-- Balik -->
    <a href="javascript:history.back()" class="bg-gray-100 p-2 rounded-lg">
      <i class="fa-solid fa-angle-left my-auto mr-2"></i> Kembali
    </a>

    <div class="bg-white shadow-lg rounded-lg flex w-4/5 h-4/5 overflow-hidden mx-auto">
        <!-- Kolom Kiri: Gambar -->
        <div class="w-3/5 flex flex-col">
            <a href="../Detail/?user=<?php echo htmlspecialchars($post['UserID']); ?>" class="flex items-center p-4 border-b">
                <img src="<?php echo $profile_picture; ?>" alt="Profile Picture" class="w-10 h-10 rounded-full">
                <div class="ml-3">
                    <h2 class="text-lg font-bold"><?php echo htmlspecialchars($post['NamaLengkap']); ?></h2>
                    <p class="text-sm text-gray-500">@<?php echo htmlspecialchars($post['Username']); ?></p>
                </div>
            </a>
            <div class="flex-grow flex items-center justify-center">
                <img src="../Uploads/<?php echo htmlspecialchars($post['LokasiFile']); ?>" alt="Foto Postingan" class="max-h-full max-w-full">
            </div>
            <div class="mt-5 mx-5">
                <h1 class="text-xl"><?php echo htmlspecialchars($post['JudulFoto']); ?></h1><hr class="mb-2">
                <p class="text-sm"><?php echo htmlspecialchars($post['DeskripsiFoto']); ?></p>
            </div>
            <div class="p-4 flex justify-between items-center">
                <form method="POST">
                    <button type="submit" name="like" class="<?php echo $like_status ? 'text-blue-500' : 'text-gray-500'; ?> flex items-center">
                        <i class="fa-solid fa-thumbs-up mr-2"></i>
                        <?php echo $like_status ? $like_count : 'Like'; ?>
                    </button>
                </form>
            </div>
        </div>

        <!-- Kolom Kanan: Komentar -->
        <div class="w-2/5 bg-gray-50 p-4 overflow-y-auto">
            <form method="POST" class="mb-4">
                <textarea name="comment_text" rows="3" class="w-full p-2 border rounded resize-none" placeholder="Tulis komentar..."></textarea>
                <button type="submit" name="comment" class="mt-2 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                    Kirim Komentar
                </button>
            </form>
            <h3 class="text-lg font-bold mb-3">Komentar</h3>
            <?php
            $comments_result = mysqli_query($connect, "
                SELECT k.*, u.NamaLengkap, u.ProfilePicture
                FROM komentarfoto k
                JOIN user u ON k.UserID = u.UserID
                WHERE k.FotoID = '$post_id'
                ORDER BY k.TanggalKomentar DESC");

            if (mysqli_num_rows($comments_result) > 0) {
                while ($comment = mysqli_fetch_assoc($comments_result)) {
                    $comment_profile_picture = empty($comment['ProfilePicture'])
                        ? '../Asset/user.png'
                        : "../Profile/ProfilePicture/{$comment['ProfilePicture']}";

                    // Tambahkan teks (Pembuat) jika UserID komentar sama dengan UserID pembuat post
                    $is_pembuat = $comment['UserID'] == $post['UserID'] ? " (Pembuat)" : "";

                    echo "<div class='mb-4 flex'>";
                    echo "<img src='$comment_profile_picture' alt='Profile Picture' class='w-10 h-10 rounded-full mr-3'>";
                    echo "<div>";
                    echo "<a href='../Detail/?user={$comment['UserID']}' class='text-sm text-gray-700 font-bold'>{$comment['NamaLengkap']}<span class='text-green-500'>$is_pembuat</span></a>";
                    echo "<p class='text-gray-600'>{$comment['IsiKomentar']}</p>";
                    echo "<p class='text-xs text-gray-400'>" . date('d M Y', strtotime($comment['TanggalKomentar'])) . "</p>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<p class='text-gray-500'>Belum ada komentar.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
<?php
    } else {
        echo "Postingan tidak ditemukan.";
    }
} else {
    echo "Parameter post tidak ditemukan.";
}
?>
