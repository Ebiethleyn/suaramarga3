<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['email'])) {
    header('Location: ../login.php');
    exit();
}

require '../db_connection.php';

$id = $_GET['id'];

// Ambil data alumni untuk mendapatkan nama file gambar
$sql = "SELECT gambar FROM alumni WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($gambar);
$stmt->fetch();
$stmt->close();

// Hapus data alumni dari database
$sql = "DELETE FROM alumni WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    if ($gambar && file_exists('img/' . $gambar)) {
        unlink('img/' . $gambar);  // Hapus gambar dari folder img
    }
    header('Location: alumni.php');
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>