<?php
if (isset($_GET['album'])) {
    $album_id = $_GET['album'];

    // Query untuk mengambil detail album dan informasi pembuatnya
    $query = "
        SELECT 
            album.*, 
            user.NamaLengkap, 
            user.Username, 
            user.ProfilePicture 
        FROM album 
        JOIN user ON album.UserID = user.UserID 
        WHERE AlbumID = '$album_id'
    ";
    $result = mysqli_query($connect, $query);
    $album = mysqli_fetch_array($result);

    if ($album) {
        // Gambar profil pembuat album
        $profile_picture = empty($album['ProfilePicture']) ? '../Asset/user.png' : "../Profile/ProfilePicture/{$album['ProfilePicture']}";
        ?>
        <!-- Balik -->
        <a href="javascript:history.back()" class="bg-gray-100 p-2 rounded-lg">
          <i class="fa-solid fa-angle-left my-auto mr-2"></i> Kembali
        </a>
        <div class="p-6 bg-white shadow-md rounded-lg max-w-4xl mx-auto">
            <!-- Info Pembuat Album -->
            <div class="flex items-center space-x-4 mb-6">
                <img src="<?php echo $profile_picture; ?>" alt="Profile" class="w-16 h-16 rounded-full object-cover">
                <div>
                    <h4 class="text-lg font-semibold"><?php echo htmlspecialchars($album['NamaLengkap']); ?></h4>
                    <p class="text-gray-500">@<?php echo htmlspecialchars($album['Username']); ?></p>
                </div>
            </div>

            <!-- Header Album -->
            <div class="text-center mb-6">
                <h2 class="text-3xl font-bold text-gray-800"><?php echo htmlspecialchars($album['NamaAlbum']); ?></h2>
                <p class="text-gray-600 mt-2"><?php echo htmlspecialchars($album['Deskripsi']); ?></p>
                <p class="text-sm text-gray-400 mt-1">Dibuat pada: <?php echo date("d M Y", strtotime($album['TanggalDibuat'])); ?></p>
            </div>
            
            <!-- Foto-Foto Album -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php
                // Query untuk mengambil foto-foto dalam album
                $foto_query = "SELECT * FROM foto WHERE AlbumID = '$album_id'";
                $foto_result = mysqli_query($connect, $foto_query);

                if (mysqli_num_rows($foto_result) > 0) {
                    while ($foto = mysqli_fetch_array($foto_result)) {
                        ?>
                        <div class="relative group overflow-hidden rounded-lg shadow-lg bg-gray-100 border-b">
                            <img src="../Uploads/<?php echo htmlspecialchars($foto['LokasiFile']); ?>" 
                                 alt="Foto Album" 
                                 class="object-cover w-full h-48 transform group-hover:scale-105 transition">
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black via-transparent to-transparent p-4 opacity-0 group-hover:opacity-100 transition">
                                <h3 class="text-lg font-semibold text-white">
                                    <?php echo htmlspecialchars($foto['JudulFoto']); ?>
                                </h3>
                                <p class="text-sm text-gray-300">
                                    <?php echo htmlspecialchars($foto['DeskripsiFoto']); ?>
                                </p>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <p class="text-gray-500 col-span-full text-center">Album Kosong.</p>
                    <?php
                }
                ?>
            </div>
        </div>
        <?php
    } else {
        echo "<p class='text-red-500 text-center'>Album tidak ditemukan.</p>";
    }
}
?>
