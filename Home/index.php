<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>El-Gato - Beranda</title>
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
    ?>
    
    <div class="flex">
        <!-- Sidebar -->
        <div class="w-1/5 h-screen bg-gray-100 p-4 sticky top-0">
            <div class="flex items-center space-x-2">
                <img src="<?php echo $profile_picture; ?>" alt="Profile" class="rounded-full" width="40px">
                <span class="font-bold">@<?php echo $output['Username']; ?></span>
            </div>
            <nav class="mt-10 space-y-4">
                <ul class="space-y-4">
                    <li>
                        <a href="../Home/" class="flex items-center space-x-2 text-blue-500 font-bold bg-gray-200 p-2 rounded-lg">
                            <i class="fa-solid fa-house mr-3"></i>
                            <span>Beranda</span>
                        </a>
                    </li>
                    <li>
                        <a href="../Search/" class="flex items-center space-x-2 text-gray-500 hover:text-gray-700 font-bold">
                            <i class="fa-solid fa-magnifying-glass mr-3"></i>
                            <span>Telusuri</span>
                        </a>
                    </li>
                    <li>
                        <a href="../Explore/" class="flex items-center space-x-2 text-gray-500 hover:text-gray-700 font-bold">
                            <i class="fa-solid fa-compass mr-3"></i>
                            <span>Jelajahi</span>
                        </a>
                    </li>
                    <li>
                        <a href="../Profile/" class="flex items-center space-x-2 text-gray-500 hover:text-gray-700 font-bold">
                            <i class="fa-solid fa-user mr-3"></i>
                            <span>Profil</span>
                        </a>
                    </li>
                </ul>

                <hr class="bg-gray-200 border-2">

                <a href="../Post/" class="flex items-center space-x-2 text-gray-500 hover:text-gray-700 font-bold">
                    <i class="fa-solid fa-arrow-up-from-bracket mr-3"></i>
                    <span>Posting</span>
                </a>
                <a href="../Logout/" class="flex items-center space-x-2 text-red-500 hover:text-red-700 font-bold">
                    <i class="fa-solid fa-sign-out-alt mr-3"></i>
                    <span>Logout</span>
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="w-4/5 p-8">
            <div class="mt-10">
                <h2 class="text-xl font-semibold">Beranda</h2>
                <p class="text-gray-500">Selamat datang, <?php echo $output['NamaLengkap']; ?>!</p>
                
                <!-- Pengguna yang Disarankan -->
                <div class="mt-8">
                    <h3 class="text-lg font-semibold mb-4">Pengguna yang Disarankan</h3>
                    <div class="flex overflow-x-auto space-x-4 py-2">
                        <?php
                            // Query untuk mendapatkan pengguna lain yang tidak sama dengan yang sedang login
                            $suggested_users = mysqli_query($connect, "SELECT * FROM user WHERE UserID != '$id' LIMIT 6");

                            if (mysqli_num_rows($suggested_users) > 0) {
                                while ($user = mysqli_fetch_array($suggested_users)) {
                                    $user_profile_picture = empty($user['ProfilePicture']) ? '../Asset/user.png' : "../Profile/ProfilePicture/{$user['ProfilePicture']}";
                                    echo '<a href="../Detail/?user=' . $user['UserID'] . '" class="flex flex-col items-center space-y-2 p-2 rounded-lg bg-gray-100 w-64">
                                            <img src="' . $user_profile_picture . '" alt="User" class="w-16 h-16 rounded-full object-cover">
                                            <p class="font-bold">@' . $user['Username'] . '</p>
                                            <p class="text-gray-500 text-sm">' . $user['NamaLengkap'] . '</p>
                                          </a>';
                                }
                            } else {
                                echo '<p class="text-center text-gray-500">Tidak ada pengguna yang disarankan saat ini.</p>';
                            }
                        ?>
                    </div>
                </div>

                <!-- Foto Acak -->
                <div class="mt-8">
                    <h3 class="text-lg font-semibold mb-4 mt-10">Foto yang Disarankan</h3>
                    <?php
                    // Query untuk mengambil foto secara acak
                    $explore_data = mysqli_query($connect, "SELECT * FROM foto ORDER BY RAND() LIMIT 9");

                    if (mysqli_num_rows($explore_data) > 0) {
                        echo '<div class="grid grid-cols-3 gap-4">';

                        while ($post = mysqli_fetch_array($explore_data)) {
                            echo '<a href="../Detail/?post=' . $post['FotoID'] . '" 
                                      class="relative group overflow-hidden rounded-lg shadow-md">
                                    <img src="../Uploads/' . $post['LokasiFile'] . '" 
                                         alt="Post Image" 
                                         class="w-full h-48 object-cover group-hover:scale-110 transition transform duration-300">
                                    <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition duration-300 flex flex-col justify-center items-center text-white">
                                        <h4 class="text-lg font-bold">' . $post['JudulFoto'] . '</h4>
                                        <p class="text-sm mt-2 mx-2">' . $post['DeskripsiFoto'] . '</p>
                                    </div>
                                  </a>';
                        }
                        echo '</div>';
                    } else {
                        echo '<p class="text-center text-gray-500 font-semibold mt-10">Belum ada postingan.</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php
    }
    ?>
</body>
</html>
