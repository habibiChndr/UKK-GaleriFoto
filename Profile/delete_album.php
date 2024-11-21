<?php
// Menghubungkan ke database
include('../connection.php');

// Mengambil data JSON yang dikirim dari frontend
$data = json_decode(file_get_contents('php://input'), true);

// Menyaring input untuk menghindari SQL Injection
$albumID = mysqli_real_escape_string($connect, $data['albumID']);

// Memeriksa apakah albumID ada
if (empty($albumID)) {
    echo json_encode(['success' => false, 'message' => 'Album ID tidak ditemukan!']);
    exit;
}

// Query untuk menghapus album
$sql = "DELETE FROM album WHERE AlbumID = '$albumID'";

// Eksekusi query
if (mysqli_query($connect, $sql)) {
    // Jika berhasil, kirim respon sukses
    echo json_encode(['success' => true]);
} else {
    // Jika gagal, kirim respon error
    echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan saat menghapus album.']);
}

// Menutup koneksi database
mysqli_close($connect);
?>
