<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>El-Gato - Profil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/fa4b6e3008.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom CSS -->
    <style>
        /* Memastikan input dan textarea memiliki panjang yang sama */
        .swal2-input,
        .swal2-textarea {
            width: 80%;
            box-sizing: border-box;
        }

        /* Membatasi textarea agar ukuran tidak bisa diubah */
        .swal2-textarea {
            resize: none;
            height: 100px;  /* Tentukan tinggi textarea sesuai kebutuhan */
        }
    </style>
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
        
        // Mendapatkan UserID dari session
        $id = $_SESSION['user_id'];
        
        // Query untuk mengambil data pengguna
        $data = mysqli_query($connect, "SELECT * FROM user WHERE UserID='$id'");
        
        // Mengambil data pengguna
        while($output = mysqli_fetch_array($data)) {
            // Memeriksa apakah pengguna memiliki foto profil
            $profile_picture = empty($output['ProfilePicture']) ? '../Asset/user.png' : "ProfilePicture/{$output['ProfilePicture']}";
            
            // Query untuk menghitung jumlah album dan foto
            $album_count_result = mysqli_query($connect, "SELECT COUNT(*) AS total_album FROM album WHERE UserID='$id'");
            $photo_count_result = mysqli_query($connect, "SELECT COUNT(*) AS total_photo FROM foto WHERE UserID='$id'");

            $album_count = mysqli_fetch_assoc($album_count_result)['total_album'] ?? 0;
            $photo_count = mysqli_fetch_assoc($photo_count_result)['total_photo'] ?? 0;
    ?>
    
    <div class="flex">
        <!-- Sidebar -->
        <div class="w-1/5 h-screen bg-gray-100 p-4 sticky top-0">
            <div class="flex items-center space-x-2">
                <img src="<?php echo $profile_picture; ?>" alt="Profile" class="rounded-full" width="40px">
                <span class="font-bold">@<?php echo $output['Username']; ?></span>
            </div>
            <nav class="mt-10 space-y-4">
                <!-- Sidebar Links -->
                <a href="../Home/" class="flex items-center space-x-2 text-gray-500 hover:text-gray-700 font-bold">
                    <i class="fa-solid fa-house mr-3"></i>
                    <span>Beranda</span>
                </a>
                <a href="../Search/" class="flex items-center space-x-2 text-gray-500 hover:text-gray-700 font-bold">
                    <i class="fa-solid fa-magnifying-glass mr-3"></i>
                    <span>Telusuri</span>
                </a>
                <a href="../Explore/" class="flex items-center space-x-2 text-gray-500 hover:text-gray-700 font-bold">
                    <i class="fa-solid fa-compass mr-3"></i>
                    <span>Jelajahi</span>
                </a>
                <a href="../Profile/" class="flex items-center space-x-2 text-blue-500 font-bold bg-gray-200 p-2 rounded-lg">
                    <i class="fa-solid fa-user mr-3"></i>
                    <span>Profil</span>
                </a>

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
                <div class="text-center">
                    <div class="flex justify-center my-10">
                        <!-- Menampilkan Foto Profil -->
                        <img src="<?php echo $profile_picture; ?>" alt="Profile Picture" class="rounded-full" width="150px">
                    </div>
                    <h1 class="text-xl mx-5"><?php echo $output['NamaLengkap']; ?></h1>
                    <p class="text-md mb-5">@<?php echo $output['Username']; ?></p>
                </div>
                <div class="flex flex-row justify-center gap-20">
                    <p><span class="font-bold"><?php echo $album_count; ?></span> Album</p>
                    <p><span class="font-bold"><?php echo $photo_count; ?></span> Foto</p>
                </div>

                <!-- Tombol Edit Profil -->
                <div class="flex justify-center my-2 relative">
                    <button class="bg-sky-500 py-2 px-11 text-white rounded-lg" id="edit-profile-btn">
                        <i class="fa-solid fa-pen mr-3"></i> Edit Profil
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="edit-profile-menu" class="absolute bg-white shadow-md rounded-md mt-10 w-48 justify-center hidden">
                        <a href="Edit-Profile/" class="block px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-t-md">
                            <i class="fa-solid fa-camera mr-3"></i> Edit Foto Profil
                        </a>
                        <a href="Edit-Identity/" class="block px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-b-md">
                            <i class="fa-solid fa-user-edit mr-2"></i> Edit Identitas
                        </a>
                    </div>
                </div>
            </div>

            <hr class="border border-gray-300 m-5"></hr>

            <!-- Album Section -->
            <div class="flex flex-col mb-5 bg-gray-100 rounded-lg p-5">
                <!-- Header dan Tombol -->
                <div class="flex justify-between items-center mb-5">
                    <h2 class="text-xl font-bold text-gray-700">Album Saya</h2>
                    <button class="bg-blue-500 text-white px-6 py-2 rounded-full" onclick="createAlbum()">
                        <i class="fa-solid fa-plus mr-2"></i> Buat Album
                    </button>
                </div>
                <hr class="mb-5">

                <!-- Album List (Horizontal Scroll) -->
                <div class="overflow-x-auto whitespace-nowrap">
                    <?php
                    // Query untuk mendapatkan semua album user berdasarkan UserID
                    $userAlbums = mysqli_query($connect, "SELECT * FROM album WHERE UserID='$id' ORDER BY TanggalDibuat DESC");

                    // Memeriksa apakah ada album yang ditemukan
                    if (mysqli_num_rows($userAlbums) > 0) {
                        while ($album = mysqli_fetch_array($userAlbums)) {
                            // Query untuk menghitung jumlah foto dalam album
                            $albumID = $album['AlbumID'];
                            $photoCountResult = mysqli_query($connect, "SELECT COUNT(*) AS total_photos FROM foto WHERE AlbumID='$albumID'");
                            $photoCount = mysqli_fetch_assoc($photoCountResult)['total_photos'] ?? 0;
                    ?>
                            <!-- Kartu Album -->
                            <div class="inline-block bg-gray-100 p-4 m-2 rounded-lg shadow hover:bg-gray-200 transition-all w-64 relative">
                                <h3 class="font-bold text-lg text-gray-700 truncate"><?php echo htmlspecialchars($album['NamaAlbum']); ?></h3>
                                <p class="text-sm text-gray-500"><?php echo date('d M Y', strtotime($album['TanggalDibuat'])); ?></p>
                                <p class="mt-2 text-gray-700"><i class="fa-solid fa-images mr-2"></i><?php echo $photoCount; ?> Foto</p>

                                <!-- Tombol Edit dan Hapus -->
                                <div class="absolute top-2 right-2 flex space-x-2">
                                    <!-- Tombol Edit -->
                                    <button class="text-yellow-500 hover:text-yellow-700" onclick="editAlbum(<?php echo $album['AlbumID']; ?>, '<?php echo htmlspecialchars($album['NamaAlbum']); ?>', '<?php echo htmlspecialchars($album['Deskripsi']); ?>')">
                                        <i class="fa-solid fa-edit"></i>
                                    </button>
                                    <!-- Tombol Hapus -->
                                    <button class="text-red-500 hover:text-red-700" onclick="confirmDeleteAlbum(<?php echo $album['AlbumID']; ?>)">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                    <?php
                        }
                    } else {
                        echo "<p class='text-gray-500'>Anda belum memiliki album. Mulailah dengan membuat album baru!</p>";
                    }
                    ?>
                </div>

            </div>


            <!-- Photos Grid -->
            <div class="grid grid-cols-3 gap-2 px-5">
                <?php
                // Query untuk mendapatkan semua foto user berdasarkan UserID
                $userPhotos = mysqli_query($connect, "SELECT * FROM foto WHERE UserID='$id' ORDER BY TanggalUnggah DESC");

                // Memeriksa apakah ada foto yang ditemukan
                if (mysqli_num_rows($userPhotos) > 0) {
                    // Looping untuk menampilkan setiap foto
                    while ($photo = mysqli_fetch_array($userPhotos)) {
                        $photoPath = "../Uploads/" . $photo['LokasiFile'];
                        $photoID = $photo['FotoID'];  // Ambil ID foto
                ?>
                    <div class="relative group overflow-hidden rounded-lg shadow-md">
                        <a href="../Detail/?post=<?php echo $photoID; ?>" class="w-full h-full">
                            <img src="<?php echo $photoPath; ?>" alt="User Photo" class="w-full h-48 object-cover group-hover:scale-110 transition transform duration-300">
                            <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition duration-300 flex flex-col justify-center items-center text-white">
                                <h4 class="text-lg font-bold"><?php echo $photo['JudulFoto']; ?></h4>
                                <p class="text-sm mt-2 mx-2"><?php echo $photo['DeskripsiFoto']; ?></p>
                            </div>
                        </a>
                    </div>
                <?php
                    }
                } else {
                    echo "<p class='text-gray-500'>Tidak ada foto yang diunggah.</p>";
                }
                ?>
            </div>
        </div>
    </div>

    <script>
        // Toggle dropdown visibility ketika tombol diklik
        document.getElementById('edit-profile-btn').addEventListener('click', function() {
            const menu = document.getElementById('edit-profile-menu');
            const btn = document.getElementById('edit-profile-btn');
            menu.classList.toggle('hidden');
            btn.classList.add('bg-gray-500', 'cursor-not-allowed');
            btn.classList.remove('bg-sky-500');
            btn.disabled = true;
        });

        // Menutup dropdown jika diklik di luar
        window.addEventListener('click', function(event) {
            const menu = document.getElementById('edit-profile-menu');
            const btn = document.getElementById('edit-profile-btn');
            if (!menu.contains(event.target) && !event.target.matches('#edit-profile-btn')) {
                menu.classList.add('hidden');
                if (btn.disabled) {
                    btn.classList.remove('bg-gray-500', 'cursor-not-allowed');
                    btn.classList.add('bg-sky-500');
                    btn.disabled = false;
                }
            }
        });
    </script>

    <!-- Script untuk menampilkan SweetAlert2 -->
    <script>
        function createAlbum() {
            Swal.fire({
                title: 'Buat Album Baru',
                html: `
                    <input type="text" id="album-name" class="swal2-input" placeholder="Nama album" autocomplete="off" required>
                    <textarea id="album-description" class="swal2-textarea" placeholder="Deskripsi album" required></textarea>
                `,
                showCancelButton: true,
                confirmButtonText: 'Buat Album',
                cancelButtonText: 'Batal',
                preConfirm: () => {
                    const albumName = document.getElementById('album-name').value;
                    const albumDescription = document.getElementById('album-description').value;

                    if (!albumName || !albumDescription) {
                        Swal.showValidationMessage('Nama dan deskripsi album harus diisi!');
                        return false;
                    }

                    return { albumName: albumName, albumDescription: albumDescription };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const albumName = result.value.albumName;
                    const albumDescription = result.value.albumDescription;
                    const userId = '<?php echo $id; ?>';  // ID pengguna dari session

                    // Kirim data ke server untuk membuat album
                    fetch('create_album.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            userId: userId,
                            albumName: albumName,
                            albumDescription: albumDescription,
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Berhasil!', 'Album berhasil dibuat.', 'success').then(() => {
                                location.reload(); // Refresh halaman setelah album berhasil dibuat
                            });
                        } else {
                            Swal.fire('Gagal!', data.message || 'Terjadi kesalahan saat membuat album.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Gagal!', 'Terjadi kesalahan saat membuat album.', 'error');
                    });
                }
            });
        }

        // Fungsi untuk edit album
        function editAlbum(albumID, currentName, currentDescription) {
            Swal.fire({
                title: 'Edit Album',
                html: `
                    <input type="text" id="album-name" class="swal2-input" value="${currentName}" placeholder="Nama album" autocomplete="off" required>
                    <textarea id="album-description" class="swal2-textarea" placeholder="Deskripsi album" required>${currentDescription}</textarea>
                `,
                showCancelButton: true,
                confirmButtonText: 'Simpan Perubahan',
                cancelButtonText: 'Batal',
                preConfirm: () => {
                    const albumName = document.getElementById('album-name').value;
                    const albumDescription = document.getElementById('album-description').value;

                    if (!albumName || !albumDescription) {
                        Swal.showValidationMessage('Nama dan deskripsi album harus diisi!');
                        return false;
                    }

                    return { albumID: albumID, albumName: albumName, albumDescription: albumDescription };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const albumName = result.value.albumName;
                    const albumDescription = result.value.albumDescription;

                    // Kirim data ke server untuk mengupdate album
                    fetch('edit_album.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            albumID: result.value.albumID,
                            albumName: albumName,
                            albumDescription: albumDescription
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Berhasil!', 'Album berhasil diperbarui.', 'success').then(() => {
                                location.reload(); // Refresh halaman setelah album berhasil diperbarui
                            });
                        } else {
                            Swal.fire('Gagal!', data.message || 'Terjadi kesalahan saat memperbarui album.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Gagal!', 'Terjadi kesalahan saat memperbarui album.', 'error');
                    });
                }
            });
        }

        // Fungsi untuk konfirmasi hapus album
        function confirmDeleteAlbum(albumID) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Album ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Kirim permintaan ke server untuk menghapus album
                    fetch('delete_album.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ albumID: albumID }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Berhasil!', 'Album berhasil dihapus.', 'success').then(() => {
                                location.reload(); // Refresh halaman setelah album dihapus
                            });
                        } else {
                            Swal.fire('Gagal!', data.message || 'Terjadi kesalahan saat menghapus album.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus album.', 'error');
                    });
                }
            });
        }
    </script>
    
    <?php
    }
    ?>
</body>
</html>
