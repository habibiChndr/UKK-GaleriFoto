<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>El-Gato - Upload Gambar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <?php
    session_start();
    include "../connection.php"; // Pastikan koneksi sesuai dengan aplikasi Anda

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php"); // Redirect jika user belum login
        exit;
    }
    $userId = $_SESSION['user_id'];

    // Ambil data album dari database untuk dropdown
    $albums = mysqli_query($connect, "SELECT * FROM album WHERE UserID = '$userId'");
    ?>

    <div class="text-center bg-white p-8 rounded-bl-3xl rounded-tr-3xl shadow-lg w-full max-w-3xl">
        <h1 class="text-4xl font-bold text-gray-800">Upload Gambar</h1>
        <p class="mt-2 text-gray-600">Silahkan pilih gambar yang ingin diupload.</p>
        <hr class="my-4 border-gray-300">

        <input type="hidden" id="userId" value="<?php echo $userId; ?>">

        <label for="uploadImage" id="chooseLabel" class="bg-blue-500 text-white text-center px-5 py-2 rounded-md hover:bg-blue-600 transition duration-200 cursor-pointer">
            Pilih Gambar
        </label>
        <input type="file" id="uploadImage" accept="image/*" class="hidden">

        <div class="m-5 hidden" id="previewContainer">
            <img id="preview" class="max-w-full max-h-96 mx-auto rounded-lg shadow" alt="Preview">
        </div>

        <!-- Form untuk Judul Foto, Deskripsi, dan Album -->
        <form id="uploadForm" method="POST" action="process.php" enctype="multipart/form-data" class="mt-4">
            <div class="mt-4">
                <label for="judulFoto" class="block text-gray-700">Judul Foto</label>
                <input type="text" id="judulFoto" name="judulFoto" class="w-full px-4 py-2 mt-2 border rounded-md" autocomplete="off" required>
            </div>

            <div class="mt-4">
                <label for="deskripsiFoto" class="block text-gray-700">Deskripsi Foto</label>
                <textarea id="deskripsiFoto" name="deskripsiFoto" class="w-full px-4 py-2 mt-2 border rounded-md resize-none" rows="3" required></textarea>
            </div>


            <div class="mt-4">
                <label for="album" class="block text-gray-700">Pilih Album</label>
                <select id="album" name="album" class="w-full px-4 py-2 mt-2 border rounded-md" required>
                <option value="" selected disabled>Pilih Album (opsional)</option>
                    <?php while ($album = mysqli_fetch_assoc($albums)) { ?>
                        <option value="<?php echo $album['AlbumID']; ?>"><?php echo $album['NamaAlbum']; ?></option>
                    <?php } ?>
                </select>
            </div>
        </form>

        <button id="uploadBtn" class="bg-green-600 text-white mt-5 px-4 py-2 rounded-md hover:bg-green-700 transition duration-200 hidden">
            Upload Gambar
        </button>
    </div>

    <script>
        const uploadImage = document.getElementById('uploadImage');
        const preview = document.getElementById('preview');
        const previewContainer = document.getElementById('previewContainer');
        const uploadBtn = document.getElementById('uploadBtn');
        const chooseLabel = document.getElementById('chooseLabel');
        const userId = document.getElementById('userId').value;
        const uploadForm = document.getElementById('uploadForm');

        // Event ketika file diunggah
        uploadImage.addEventListener('change', () => {
            const file = uploadImage.files[0];
            const reader = new FileReader();

            reader.onload = () => {
                preview.src = reader.result;

                // Tampilkan preview dan tombol upload
                previewContainer.classList.remove('hidden');
                uploadBtn.classList.remove('hidden');
                chooseLabel.classList.add('hidden');
            };

            if (file) {
                reader.readAsDataURL(file);
            }
        });

        // Event ketika tombol "Upload Gambar" diklik
        uploadBtn.addEventListener('click', (event) => {
            event.preventDefault(); // Mencegah form submit otomatis

            const formData = new FormData(uploadForm);
            formData.append('croppedImage', uploadImage.files[0]);
            formData.append('userId', userId);

            // Kirim gambar ke server
            fetch('process.php', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Gambar berhasil diupload.',
                        icon: 'success',
                        confirmButtonText: 'OK',
                    }).then(() => {
                        window.location.href="../";
                    });
                } else {
                    Swal.fire('Error!', data.message || 'Gagal mengupload gambar.', 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error!', 'Terjadi kesalahan saat mengupload.', 'error');
            });
        });
    </script>
</body>
</html>
