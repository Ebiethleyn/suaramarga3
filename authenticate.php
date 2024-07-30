<?php
session_start();
require 'db_connection.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// Query untuk memeriksa pengguna
$sql = "SELECT * FROM admin WHERE email = ? AND password = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $email, $password);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
    $_SESSION['email'] = $user['email'];
    header("Location: admin/dashboard.php");
    exit;
} else {
    echo "Email atau password salah. Silakan coba lagi.";
}

$stmt->close();
$conn->close();
?>