<?php
session_start();  // Mulai sesi
session_unset();  // Hapus semua data sesi
session_destroy();  // Hancurkan sesi

// Arahkan kembali ke halaman login
header("Location: ../Login/");
exit();
?>
