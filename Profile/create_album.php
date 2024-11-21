<?php
include "../connection.php";

$data = json_decode(file_get_contents('php://input'), true);

// Memeriksa apakah data yang diperlukan ada dalam request
if (isset($data['userId']) && isset($data['albumName']) && isset($data['albumDescription'])) {
    $userId = $data['userId'];  // ID pengguna yang sedang login
    $albumName = mysqli_real_escape_string($connect, $data['albumName']);  // Nama album
    $albumDescription = mysqli_real_escape_string($connect, $data['albumDescription']);  // Deskripsi album
    $tanggalDibuat = date('Y-m-d');  // Tanggal pembuatan album adalah tanggal saat ini

    // Validasi: Memastikan nama album dan deskripsi tidak kosong
    if (empty($albumName) || empty($albumDescription)) {
        echo json_encode(['success' => false, 'message' => 'Nama dan deskripsi album harus diisi.']);
        exit();
    }

    // Query untuk memasukkan data album baru
    $query = "INSERT INTO album (NamaAlbum, Deskripsi, TanggalDibuat, UserID) 
              VALUES ('$albumName', '$albumDescription', '$tanggalDibuat', '$userId')";

    // Menjalankan query dan mengembalikan hasilnya dalam format JSON
    if (mysqli_query($connect, $query)) {
        echo json_encode(['success' => true, 'message' => 'Album berhasil dibuat.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan saat membuat album.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap.']);
}
?>
