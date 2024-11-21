<?php
session_start(); // Memulai sesi

// Memeriksa apakah pengguna sudah login
if (isset($_SESSION['user_id'])) {
    header("Location: ../Home/");
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
    <div class="text-center bg-white p-8 rounded-bl-3xl rounded-tr-3xl shadow-lg w-full max-w-sm">
        <h1 class="text-4xl font-bold text-gray-800">Masuk</h1>
        <p class="mt-2 text-gray-600">Silahkan masukkan data anda.</p>
        <hr class="my-4 border-gray-300">

        <form action="login_action.php" method="post">
            <div class="mb-4">
                <input type="text" name="username" placeholder="Username" autocomplete="off" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-6">
                <input type="password" name="password" placeholder="Password" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <input type="submit" value="Masuk" class="w-full bg-blue-500 text-white text-center px-3 py-2 rounded-md hover:bg-blue-600 transition duration-200 cursor-pointer">
            <div class="mt-5">
                <p class="text-md text-gray-500">Belum punya akun? <a href="../Register/" class="text-blue-500">Daftar</a></p>
            </div>
        </form>
    </div>
</body>
</html>
