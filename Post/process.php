<?php
session_start();
include "../connection.php"; // Pastikan koneksi sudah benar

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['croppedImage']) && isset($_POST['userId']) && isset($_POST['judulFoto']) && isset($_POST['deskripsiFoto'])) {
        // Ambil data dari form
        $userId = $_POST['userId'];
        $judulFoto = mysqli_real_escape_string($connect, $_POST['judulFoto']);
        $deskripsiFoto = mysqli_real_escape_string($connect, $_POST['deskripsiFoto']);

        // Ambil album, jika tidak ada set ke NULL
        $albumID = isset($_POST['album']) && $_POST['album'] !== "" ? $_POST['album'] : NULL;

        // Ambil tanggal upload
        $tanggalUnggah = date('Y-m-d');

        // Tentukan folder upload
        $uploadDir = "../Uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true); // Membuat folder jika belum ada
        }

        // Tentukan nama file gambar
        $file = $_FILES['croppedImage'];
        $fileName = uniqid() . '.jpg';
        $uploadPath = $uploadDir . $fileName;

        // Cek apakah file berhasil diupload
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            // Siapkan query untuk menyimpan data ke tabel foto
            $query = "INSERT INTO foto (JudulFoto, DeskripsiFoto, TanggalUnggah, LokasiFile, AlbumID, UserID) 
                      VALUES ('$judulFoto', '$deskripsiFoto', '$tanggalUnggah', '$fileName', " . ($albumID === NULL ? "NULL" : "'$albumID'") . ", '$userId')";

            // Eksekusi query untuk menyimpan data foto ke database
            if (mysqli_query($connect, $query)) {
                echo json_encode(['status' => 'success', 'message' => 'Gambar berhasil diupload.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data ke database.', 'error' => mysqli_error($connect)]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal mengupload gambar.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap atau gambar tidak ada.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
