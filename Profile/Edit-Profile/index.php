<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>El-Gato - Edit Foto Profil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <style>
        #preview {
            max-width: 100%; 
            max-height: 400px;
            margin-top: 10px;
        }
    </style>
    <?php
        session_start();
        include "../../connection.php";
        
        $id = $_SESSION['user_id'];
        $data = mysqli_query($connect, "SELECT * FROM user WHERE UserID='$id'");
        while($output = mysqli_fetch_array($data)) {
    ?>
    <div class="text-center bg-white p-8 rounded-bl-3xl rounded-tr-3xl shadow-lg w-full max-w-3xl">
        <h1 class="text-4xl font-bold text-gray-800">Edit Foto Profil</h1>
        <p class="mt-2 text-gray-600">Silahkan masukkan foto.</p>
        <hr class="my-4 border-gray-300">

        <input type="hidden" id="userId" value="<?php echo $id; ?>">

        <label for="uploadImage" class="bg-blue-500 text-white text-center px-5 py-2 rounded-md hover:bg-blue-600 transition duration-200 cursor-pointer">
            Upload Gambar
        </label>
        <input type="file" id="uploadImage" accept="image/*" class="hidden">

        <div class="m-5">
            <img id="preview">
        </div>

        <button id="cropBtn" class="bg-green-600 text-white text-center px-3 py-2 rounded-md hover:bg-green-700 transition duration-200 cursor-pointer" hidden>Crop & Simpan</button>

        <script>
        const image = document.getElementById('preview');
        const cropBtn = document.getElementById('cropBtn');
        let cropper;

        // Event ketika file di-upload
        document.getElementById('uploadImage').addEventListener('change', (event) => {
            const file = event.target.files[0];
            const reader = new FileReader();

            reader.onload = () => {
                image.src = reader.result;
                cropBtn.hidden = false; // Tampilkan tombol setelah gambar diunggah
                if (cropper) cropper.destroy(); // Hapus cropper jika ada sebelumnya
                cropper = new Cropper(image, {
                    aspectRatio: 1, // Aspek rasio 1:1 untuk foto profil
                    viewMode: 1,
                });
            };
            reader.readAsDataURL(file);
        });

        // Event ketika tombol "Crop & Simpan" diklik
        cropBtn.addEventListener('click', () => {
            const userId = document.getElementById('userId').value;

            if (!userId) {
                Swal.fire({
                    title: 'Kesalahan',
                    text: 'User ID tidak ditemukan!',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return;
            }

            if (cropper) {
                const croppedCanvas = cropper.getCroppedCanvas();
                croppedCanvas.toBlob((blob) => {
                    const formData = new FormData();
                    formData.append('croppedImage', blob);
                    formData.append('userId', userId);

                    // Kirim gambar dan UserID ke PHP menggunakan fetch
                    fetch('process.php', {
                        method: 'POST',
                        body: formData,
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire({
                                title: 'Berhasil',
                                text: 'Foto profil berhasil diperbarui.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.href = '../'; // Redirect ke halaman profil
                            });
                        } else {
                            Swal.fire({
                                title: 'Gagal',
                                text: data.message || 'Terjadi kesalahan saat menyimpan foto.',
                                icon: 'error',
                                confirmButtonText: 'Coba Lagi'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Kesalahan',
                            text: 'Terjadi kesalahan saat mengunggah foto.',
                            icon: 'error',
                            confirmButtonText: 'Coba Lagi'
                        });
                    });
                });
            }
        });
        </script>
    </div>
    <?php
    }
    ?>
</body>
</html>
