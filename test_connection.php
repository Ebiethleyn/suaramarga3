<?php
require 'db_connection.php';

if ($conn) {
    echo "Koneksi ke database berhasil!";
} else {
    echo "Koneksi ke database gagal.";
}

$conn->close();
?>