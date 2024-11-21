<?php
include "../../connection.php";

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['userId']) && isset($data['newPassword'])) {
    $userId = $data['userId'];
    $newPassword = $data['newPassword'];

    // Hash password baru sebelum disimpan
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Query untuk memperbarui password pengguna
    $query = "UPDATE user SET Password='$hashedPassword' WHERE UserID='$userId'";

    if (mysqli_query($connect, $query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan saat memperbarui password.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap.']);
}
?>
