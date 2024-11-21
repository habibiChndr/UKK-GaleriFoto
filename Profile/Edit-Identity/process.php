<?php
include "../../connection.php";

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['userId']) && isset($data['field']) && isset($data['value'])) {
    $userId = $data['userId'];
    $field = $data['field'];
    $value = mysqli_real_escape_string($connect, $data['value']);

    // Validasi untuk email
    if ($field === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Format email tidak valid.']);
        exit();
    }

    // Query untuk memperbarui data
    $query = "UPDATE user SET $field='$value' WHERE UserID='$userId'";

    if (mysqli_query($connect, $query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan saat memperbarui data.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap.']);
}
?>
