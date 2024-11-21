<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>El-Gato</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/fa4b6e3008.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<?php
include "../connection.php";
session_start(); // Memulai session

$username = $_POST['username'];
$password = $_POST['password'];

// Menggunakan prepared statement untuk mencegah SQL injection
$query = "SELECT * FROM user WHERE Username = ?";
$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Mengecek apakah ada data yang ditemukan
if (mysqli_num_rows($result) > 0) {
    // Mengambil data pengguna
    $user = mysqli_fetch_assoc($result);

    // Verifikasi password dengan password_hash
    if (password_verify($password, $user['Password'])) {
        // Menyimpan UserID dalam session
        $_SESSION['user_id'] = $user['UserID']; // Menyimpan UserID ke session
        header("Location: ../Home/"); // Arahkan ke halaman utama
        exit();
    } else {
        // Jika password tidak cocok
        echo '<script>
                Swal.fire({
                    title: "Terjadi kesalahan",
                    text: "Username atau password salah. Silahkan coba lagi.",
                    icon: "error",
                    confirmButtonText: "Kembali",
                }).then(function() {
                    window.location = "../Login/";
                });
            </script>';
    }
} else {
    // Jika username tidak ditemukan
    echo '<script>
            Swal.fire({
                title: "Terjadi kesalahan",
                text: "Username tidak ditemukan.",
                icon: "error",
                confirmButtonText: "Kembali",
            }).then(function() {
                window.location = "../Login/";
            });
        </script>';
}

mysqli_close($connect);
?>

</body>
</html>
