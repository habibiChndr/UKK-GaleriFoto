<?php
// Menghubungkan ke database
include('../connection.php');

// Mengambil data JSON yang dikirim dari frontend
$data = json_decode(file_get_contents('php://input'), true);

// Menyaring input untuk menghindari SQL Injection
$albumID = mysqli_real_escape_string($connect, $data['albumID']);
$albumName = mysqli_real_escape_string($connect, $data['albumName']);
$albumDescription = mysqli_real_escape_string($connect, $data['albumDescription']);

// Memeriksa apakah data yang diperlukan ada
if (empty($albumID) || empty($albumName) || empty($albumDescription)) {
    echo json_encode(['success' => false, 'message' => 'Semua kolom harus diisi!']);
    exit;
}

// Query untuk memperbarui album
$sql = "UPDATE album SET NamaAlbum = '$albumName', Deskripsi = '$albumDescription' WHERE AlbumID = '$albumID'";

if (mysqli_query($connect, $sql)) {
    // Jika berhasil, kirim respon sukses
    echo json_encode(['success' => true]);
} else {
    // Jika gagal, kirim respon error
    echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan saat memperbarui album.']);
}

// Menutup koneksi database
mysqli_close($connect);
?>
