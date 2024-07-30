<?php
session_start();
require '../db_connection.php';

// Pastikan ada parameter id yang diterima
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk menghapus data anggota berdasarkan id
    $sql = "DELETE FROM anggota_biasa WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redirect kembali ke halaman anggotaBiasa.php setelah berhasil menghapus
        header("Location: anggotaBiasa.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>