<?php
include "../../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['croppedImage']) && isset($_POST['userId'])) {
        $id = $_POST['userId'];
        $file = $_FILES['croppedImage'];
        $uploadDir = '../ProfilePicture/';
        $fileName = uniqid() . '.jpg';
        $uploadPath = $uploadDir . $fileName;

        // Buat folder jika belum ada
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Cek apakah pengguna sudah memiliki foto profil
        $queryCheck = "SELECT ProfilePicture FROM user WHERE UserID = '$id'";
        $resultCheck = mysqli_query($connect, $queryCheck);

        if ($resultCheck && mysqli_num_rows($resultCheck) > 0) {
            // Ambil nama file lama
            $oldData = mysqli_fetch_assoc($resultCheck);
            $oldFileName = $oldData['ProfilePicture'];
            $oldFilePath = $uploadDir . $oldFileName;

            // Hapus foto profil lama jika file ada
            if (!empty($oldFileName) && file_exists($oldFilePath)) {
                unlink($oldFilePath); // Menghapus file lama
            }
        }

        // Update nama file baru di database
        $queryUpdate = "UPDATE user SET ProfilePicture = '$fileName' WHERE UserID = '$id'";
        $resultUpdate = mysqli_query($connect, $queryUpdate);

        // Menyimpan gambar ke direktori jika query berhasil
        if ($resultUpdate && move_uploaded_file($file['tmp_name'], $uploadPath)) {
            echo json_encode(['status' => 'success', 'path' => $uploadPath]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Database error: ' . mysqli_error($connect)]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No file uploaded']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
