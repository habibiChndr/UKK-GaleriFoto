<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>El-Gato - Detail</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/fa4b6e3008.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-200 font-sans">
<?php
    session_start();

    // Memeriksa apakah pengguna sudah login
    if (!isset($_SESSION['user_id'])) {
        echo '<script>
        Swal.fire({
            title: "Terjadi kesalahan",
            text: "Login gagal, silahkan coba lagi.",
            icon: "warning",
            confirmButtonText: "Kembali",
        }).then(function() {
            window.location = "../Login/";
        });
        </script>';
        exit();
    }

    include "../connection.php";
    
    // Get user ID from session
    $id = $_SESSION['user_id'];
    
    // Query untuk mengambil data pengguna
    $data = mysqli_query($connect, "SELECT * FROM user WHERE UserID='$id'");

    // Loop untuk mengambil data pengguna
    while($output = mysqli_fetch_array($data)) {
        // Memeriksa gambar profil
        $profile_picture = empty($output['ProfilePicture']) ? '../Asset/user.png' : "../Profile/ProfilePicture/{$output['ProfilePicture']}";
    }
?>

<div class="container mx-auto mt-5">
    <?php
        // Menangani URL untuk melihat detail berdasarkan parameter
        if (isset($_GET['post'])) {
            // Tampilkan detail postingan
            include 'post.php';
        } elseif (isset($_GET['album'])) {
            // Tampilkan detail album
            include 'album.php';
        } elseif (isset($_GET['user'])) {
            // Tampilkan detail user
            include 'user.php';
        } else {
            echo "<p class='text-center text-xl text-red-500'>Halaman tidak ditemukan!</p>";
        }
    ?>
</div>

</body>
</html>
