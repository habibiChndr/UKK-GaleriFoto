<?php
session_start();

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

$id = $_SESSION['user_id'];
$search_query = ''; // Nilai default untuk mencegah error
$search_results = [];
$users = [];
$photos = [];

if (isset($_POST['search'])) {
    $search_query = trim($_POST['search']);
    if (strlen($search_query) < 3) {
        echo '<script>
            Swal.fire({
                title: "Peringatan",
                text: "Masukkan minimal 3 karakter untuk melakukan pencarian.",
                icon: "warning",
                confirmButtonText: "OK",
            });
        </script>';
    } else {
        // Query untuk pencarian
        $query_foto_album = "
            SELECT f.FotoID, f.JudulFoto, f.LokasiFile, a.NamaAlbum
            FROM foto f
            LEFT JOIN album a ON f.AlbumID = a.AlbumID
            WHERE f.JudulFoto LIKE ? OR a.NamaAlbum LIKE ?
        ";
        $stmt_foto_album = $connect->prepare($query_foto_album);
        $like_query = '%' . $search_query . '%';
        $stmt_foto_album->bind_param("ss", $like_query, $like_query);
        $stmt_foto_album->execute();
        $result_foto_album = $stmt_foto_album->get_result();

        while ($row = $result_foto_album->fetch_assoc()) {
            $row['type'] = 'foto_album';
            $photos[] = $row;
        }

        $query_user = "
            SELECT UserID, Username, ProfilePicture
            FROM user
            WHERE Username LIKE ?
        ";
        $stmt_user = $connect->prepare($query_user);
        $stmt_user->bind_param("s", $like_query);
        $stmt_user->execute();
        $result_user = $stmt_user->get_result();

        while ($row = $result_user->fetch_assoc()) {
            $row['type'] = 'user';
            $users[] = $row;
        }

        $search_results = array_merge($users, $photos);
    }
}

$data = $connect->prepare("SELECT * FROM user WHERE UserID = ?");
$data->bind_param("i", $id);
$data->execute();
$user_data = $data->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>El-Gato - Telusuri</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/fa4b6e3008.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function validateSearchForm() {
            const searchInput = document.querySelector('input[name="search"]').value.trim();
            if (searchInput.length < 3) {
                Swal.fire({
                    title: "Peringatan",
                    text: "Masukkan minimal 3 karakter untuk melakukan pencarian.",
                    icon: "warning",
                    confirmButtonText: "OK",
                });
                return false;
            }
            return true;
        }
    </script>
</head>
<body class="bg-gray-200 font-sans">
    <div class="flex">
        <!-- Sidebar -->
        <div class="w-1/5 h-screen bg-gray-100 p-4 sticky top-0">
            <div class="flex items-center space-x-2">
                <?php
                $profile_picture = !empty($user_data['ProfilePicture']) ? "../Profile/ProfilePicture/" . $user_data['ProfilePicture'] : '../Asset/user.png';
                ?>
                <img src="<?php echo $profile_picture; ?>" alt="Profile" class="rounded-full" width="40px">
                <span class="font-bold">@<?php echo $user_data['Username']; ?></span>
            </div>
            <nav class="mt-10 space-y-4">
                <ul class="space-y-4">
                    <li>
                        <a href="../Home/" class="flex items-center text-gray-500 hover:text-gray-700 font-bold">
                            <i class="fa-solid fa-house mr-3"></i>
                            <span>Beranda</span>
                        </a>
                    </li>
                    <li>
                        <a href="../Search/" class="flex items-center text-blue-500 font-bold bg-gray-200 p-2 rounded-lg">
                            <i class="fa-solid fa-magnifying-glass mr-3"></i>
                            <span>Telusuri</span>
                        </a>
                    </li>
                    <li>
                        <a href="../Explore/" class="flex items-center text-gray-500 hover:text-gray-700 font-bold">
                            <i class="fa-solid fa-compass mr-3"></i>
                            <span>Jelajahi</span>
                        </a>
                    </li>
                    <li>
                        <a href="../Profile/" class="flex items-center text-gray-500 hover:text-gray-700 font-bold">
                            <i class="fa-solid fa-user mr-3"></i>
                            <span>Profil</span>
                        </a>
                    </li>
                </ul>
                <hr class="bg-gray-200 border-2">
                <a href="../Post/" class="flex items-center text-gray-500 hover:text-gray-700 font-bold">
                    <i class="fa-solid fa-arrow-up-from-bracket mr-3"></i>
                    <span>Posting</span>
                </a>
                <a href="../Logout/" class="flex items-center text-red-500 hover:text-red-700 font-bold">
                    <i class="fa-solid fa-sign-out-alt mr-3"></i>
                    <span>Logout</span>
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="w-4/5 p-8">
            <div class="mt-10">
                <form method="POST" action="" onsubmit="return validateSearchForm()">
                    <input type="text" name="search" class="w-full px-4 py-2 bg-gray-100 border rounded-lg outline-none" placeholder="Cari foto atau pengguna..." value="<?php echo htmlspecialchars($search_query); ?>" autocomplete="off">
                </form>

                <?php if (!empty($search_query) && strlen($search_query) >= 3): ?>
                    <div class="mt-5">
                        <h2 class="text-2xl font-bold">Hasil Pencarian untuk "<?php echo htmlspecialchars($search_query); ?>"</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mt-5">
                            <?php if (!empty($search_results)): ?>
                                <?php foreach ($search_results as $result): ?>
                                    <?php if ($result['type'] === 'foto_album'): ?>
                                        <a href="../Detail/?post=<?php echo $result['FotoID']; ?>" class="flex flex-col items-center bg-gray-100 p-4 rounded-lg shadow-md hover:shadow-lg transition">
                                            <img src="../Uploads/<?php echo $result['LokasiFile']; ?>" alt="Foto" class="rounded-lg w-full object-cover h-48">
                                            <div class="text-center mt-2">
                                                <p class="font-semibold"><?php echo htmlspecialchars($result['JudulFoto']); ?></p>
                                                <p class="text-gray-500"><?php echo htmlspecialchars($result['NamaAlbum']) ?: "Tidak ada album"; ?></p>
                                            </div>
                                        </a>
                                    <?php elseif ($result['type'] === 'user'): ?>
                                        <?php if ($result['UserID'] == $_SESSION['user_id']): ?>
                                            <!-- Tampilkan 'Anda (di list)' jika UserID sama dengan session -->
                                            <a href="../Profile/" class="flex items-center bg-gray-100 p-4 rounded-lg shadow-md hover:shadow-lg transition">
                                                <img src="<?php echo !empty($result['ProfilePicture']) ? "../Profile/ProfilePicture/" . $result['ProfilePicture'] : "../Asset/user.png"; ?>" alt="User Photo" class="rounded-full w-10 h-10">
                                                <span class="ml-3 font-semibold flex-1">@<?php echo htmlspecialchars($result['Username']); ?></span>
                                                <?php if ($result['UserID'] == $_SESSION['user_id']): ?>
                                                    <span class="text-sm my-auto font-bold text-gray-500">Anda</span>
                                                <?php endif; ?>
                                            </a>


                                        <?php else: ?>
                                            <!-- Tampilkan pengguna lainnya jika tidak sama dengan session -->
                                            <a href="../Detail/?user=<?php echo $result['UserID']; ?>" class="flex items-center bg-gray-100 p-4 rounded-lg shadow-md hover:shadow-lg transition">
                                                <img src="<?php echo !empty($result['ProfilePicture']) ? "../Profile/ProfilePicture/" . $result['ProfilePicture'] : "../Asset/user.png"; ?>" alt="User Photo" class="rounded-full w-10 h-10">
                                                <span class="ml-3 font-semibold">@<?php echo htmlspecialchars($result['Username']); ?></span>
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>

                            <?php else: ?>
                                <p class="text-gray-500">Tidak ada hasil yang ditemukan.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
