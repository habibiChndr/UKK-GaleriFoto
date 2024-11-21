<?php
    if (isset($_GET['user'])) {
        $user_id = $_GET['user'];

        // Query untuk mengambil detail user
        include "../connection.php";
        $query = "SELECT * FROM user WHERE UserID = '$user_id'";
        $result = mysqli_query($connect, $query);
        $user = mysqli_fetch_array($result);

        if ($user) {
            // Query untuk menghitung jumlah album dan foto
            $album_count_result = mysqli_query($connect, "SELECT COUNT(*) AS total_album FROM album WHERE UserID='$user_id'");
            $photo_count_result = mysqli_query($connect, "SELECT COUNT(*) AS total_photo FROM foto WHERE UserID='$user_id'");

            $album_count = mysqli_fetch_assoc($album_count_result)['total_album'] ?? 0;
            $photo_count = mysqli_fetch_assoc($photo_count_result)['total_photo'] ?? 0;

            // Menentukan foto profil
            $profile_picture = empty($user['ProfilePicture']) ? '../Asset/user.png' : "../Profile/ProfilePicture/{$user['ProfilePicture']}";
        } else {
            echo "<p class='text-red-500'>User tidak ditemukan.</p>";
            exit;
        }
    } else {
        echo "<p class='text-red-500'>Parameter user tidak ditemukan.</p>";
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>El-Gato - Profil Pengguna</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/fa4b6e3008.js" crossorigin="anonymous"></script>
</head>
<body class="bg-gray-200 font-sans">
<!-- Balik -->
<a href="javascript:history.back()" class="bg-gray-100 p-2 rounded-lg">
    <i class="fa-solid fa-angle-left my-auto mr-2"></i> Kembali
</a>
<div class="flex justify-center items-center min-h-screen">
    <div class="w-full max-w-4xl">
        <div class="text-center">
            <!-- Foto Profil -->
            <div class="flex justify-center my-10">
                <img src="<?php echo $profile_picture; ?>" alt="Profile Picture" class="rounded-full" width="150px">
            </div>
            <h1 class="text-xl mx-5"><?php echo htmlspecialchars($user['NamaLengkap']); ?></h1>
            <p class="text-md mb-5">@<?php echo htmlspecialchars($user['Username']); ?></p>
        </div>
        <div class="flex flex-row justify-center gap-20">
            <p><span class="font-bold"><?php echo $album_count; ?></span> Album</p>
            <p><span class="font-bold"><?php echo $photo_count; ?></span> Foto</p>
        </div>

        <hr class="border border-gray-300 m-5"></hr>

        <!-- Album Section -->
        <div class="flex flex-col mb-5 bg-gray-100 rounded-lg p-5">
            <div class="flex justify-between items-center mb-5">
                <h2 class="text-xl font-bold text-gray-700">Album Pengguna</h2>
            </div>
            <hr class="mb-5">
            <div class="overflow-x-auto whitespace-nowrap">
                <?php
                // Query untuk mendapatkan semua album user berdasarkan UserID
                $userAlbums = mysqli_query($connect, "SELECT * FROM album WHERE UserID='$user_id' ORDER BY TanggalDibuat DESC");

                if (mysqli_num_rows($userAlbums) > 0) {
                    while ($album = mysqli_fetch_array($userAlbums)) {
                        $albumID = $album['AlbumID'];
                        $photoCountResult = mysqli_query($connect, "SELECT COUNT(*) AS total_photos FROM foto WHERE AlbumID='$albumID'");
                        $photoCount = mysqli_fetch_assoc($photoCountResult)['total_photos'] ?? 0;
                ?>
                        <a href="../Detail/?album=<?php echo $albumID; ?>" class="inline-block bg-gray-100 p-4 m-2 rounded-lg shadow hover:bg-gray-200 transition-all w-64">
                            <h3 class="font-bold text-lg text-gray-700 truncate"><?php echo htmlspecialchars($album['NamaAlbum']); ?></h3>
                            <p class="text-sm text-gray-500"><?php echo date('d M Y', strtotime($album['TanggalDibuat'])); ?></p>
                            <p class="mt-2 text-gray-700"><i class="fa-solid fa-images mr-2"></i><?php echo $photoCount; ?> Foto</p>
                        </a>
                <?php
                    }
                } else {
                    echo "<p class='text-gray-500'>Pengguna ini belum memiliki album.</p>";
                }
                ?>
            </div>
        </div>

        <!-- Photos Grid -->
        <div class="grid grid-cols-3 gap-2 px-5">
            <?php
            $userPhotos = mysqli_query($connect, "SELECT * FROM foto WHERE UserID='$user_id' ORDER BY TanggalUnggah DESC");
            if (mysqli_num_rows($userPhotos) > 0) {
                while ($photo = mysqli_fetch_array($userPhotos)) {
                    $photoPath = "../Uploads/" . $photo['LokasiFile'];
                    $photoID = $photo['FotoID'];
            ?>
                    <div class="relative">
                        <a href="../Detail/?post=<?php echo $photoID; ?>">
                            <img src="<?php echo $photoPath; ?>" alt="User Photo" class="w-full h-full object-cover">
                        </a>
                    </div>
            <?php
                }
            } else {
                echo "<p class='text-gray-500'>Pengguna ini belum mengunggah foto.</p>";
            }
            ?>
        </div>
    </div>
</div>

</body>
</html>
