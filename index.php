<?php
session_start(); // Memulai sesi

// Memeriksa apakah pengguna sudah login
if (isset($_SESSION['user_id'])) {
    header("Location: Home/");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>El-Gato</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="text-center">
        <h1 class="text-4xl font-bold">El-Gato</h1>
        <p class="mt-2">Selamat datang di El-Gato, Galeri Foto!</p>
        <hr class="my-4 border-gray-300">
        <a href="Login" class="text-blue-500 hover:underline mr-4">Masuk</a>
        <a href="Register" class="text-blue-500 hover:underline">Daftar</a>
    </div>
</body>
</html>