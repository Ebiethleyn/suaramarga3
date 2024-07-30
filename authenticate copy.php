<?php
session_start();
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Menggunakan prepared statement untuk mencegah SQL Injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verifikasi password menggunakan password_hash dan password_verify
        if (password_verify($password, $user['password'])) {
            // Menyimpan data pengguna di sesi
            $_SESSION['user'] = $user;
            header("Location: dashboard.php");
        } else {
            echo "Password salah!";
        }
    } else {
        echo "Email tidak ditemukan!";
    }

    $stmt->close();
}
$conn->close();
?>