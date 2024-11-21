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

// Mendapatkan input dari form
$namaLengkap = $_POST['namaLengkap'];
$email = $_POST['email'];
$username = $_POST['username'];
$password = $_POST['password'];
$alamat = $_POST['alamat'];

// Validasi password (contoh: minimal 6 karakter)
if (strlen($password) < 6) {
    echo "<script>
        Swal.fire({
            title: 'Password terlalu pendek!',
            text: 'Password harus memiliki minimal 6 karakter.',
            icon: 'error',
            confirmButtonText: 'Kembali'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '../Register/'; // Redirect ke halaman register
            }
        });
    </script>";
    exit();
}

// Cek apakah username sudah ada di database
$check_username = mysqli_prepare($connect, "SELECT * FROM user WHERE Username = ?");
mysqli_stmt_bind_param($check_username, "s", $username);
mysqli_stmt_execute($check_username);
$result = mysqli_stmt_get_result($check_username);

if (mysqli_num_rows($result) > 0) {
    // Jika username sudah ada
    echo "<script>
        Swal.fire({
            title: 'Username sudah digunakan!',
            text: 'Silakan pilih username lain.',
            icon: 'error',
            confirmButtonText: 'Kembali'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '../Register/'; // Redirect ke halaman register
            }
        });
    </script>";
} else {
    // Hash password sebelum disimpan
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Menyimpan data pengguna ke database
    $query = mysqli_prepare($connect, "INSERT INTO user (Username, Password, Email, NamaLengkap, Alamat) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($query, "sssss", $username, $hashed_password, $email, $namaLengkap, $alamat);

    if (mysqli_stmt_execute($query)) {
        // Jika data berhasil disimpan
        echo "<script>
            Swal.fire({
                title: 'Registrasi Berhasil!',
                text: 'Anda telah berhasil mendaftar.',
                icon: 'success',
                confirmButtonText: 'Login'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '../Login/'; // Redirect ke halaman login
                }
            });
        </script>";
    } else {
        // Jika ada kesalahan saat menyimpan data
        echo "<script>
            Swal.fire({
                title: 'Registrasi Gagal!',
                text: 'Terjadi kesalahan, silakan coba lagi.',
                icon: 'error',
                confirmButtonText: 'Kembali'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '../Register/'; // Redirect ke halaman register
                }
            });
        </script>";
    }
}

mysqli_close($connect);
?>

</body>
</html>
