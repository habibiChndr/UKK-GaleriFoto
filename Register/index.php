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
<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <script>
        // Menampilkan notifikasi menggunakan SweetAlert
        window.onload = function() {
            Swal.fire({
                title: 'Pemberitahuan',
                text: 'Anda bisa menambahkan foto profil di menu Profil setelah Masuk.',
                showConfirmButton: true,
                confirmButtonText: 'OK'
            });
        };
    </script>
    <div class="text-center bg-white p-8 rounded-bl-3xl rounded-tr-3xl shadow-lg w-full max-w-sm">
        <h1 class="text-4xl font-bold text-gray-800">Daftar</h1>
        <p class="mt-2 text-gray-600">Silahkan lengkapi form dibawah.</p>
        <hr class="my-4 border-gray-300">

        <form action="register_action.php" method="post">
            <div class="mb-4">
                <input type="text" name="namaLengkap" placeholder="Nama Lengkap" autocomplete="off" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-4">
                <input type="email" name="email" placeholder="Email" autocomplete="off" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-4">
                <input type="text" name="alamat" placeholder="Alamat" autocomplete="off" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="grid grid-cols-2 gap-2 mb-6">
                <div>
                    <input type="text" name="username" placeholder="Username" autocomplete="off" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <input type="password" name="password" placeholder="Password" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>

            <input type="submit" value="Daftar" class="w-full bg-blue-500 text-white text-center px-3 py-2 rounded-md hover:bg-blue-600 transition duration-200 cursor-pointer">
            <div class="mt-5">
                <p class="text-md text-gray-500">Sudah punya akun? <a href="../Login/" class="text-blue-500">Masuk</a></p>
            </div>
        </form>
    </div>
</body>
</html>
