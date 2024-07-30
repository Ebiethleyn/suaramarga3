<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['email'])) {
    header('Location: ../login.php');
    exit();
}

require '../db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lengkap = $_POST['nama_lengkap'];
    $no_hp = $_POST['no_hp'] ?? null;
    $email = $_POST['email'] ?? null;
    $alamat = $_POST['alamat'] ?? null;
    $angkatan = $_POST['angkatan'] ?? null;

    $gambar = null;
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $fileExtension = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));

        if (in_array($fileExtension, $allowedExtensions)) {
            $uniqueName = uniqid() . '.' . $fileExtension;
            $targetFile = 'img/' . $uniqueName;

            if ($_FILES['gambar']['size'] <= 500 * 1024) {
                if (move_uploaded_file($_FILES['gambar']['tmp_name'], $targetFile)) {
                    $gambar = $uniqueName;
                } else {
                    echo "Terjadi kesalahan saat mengunggah gambar.";
                }
            } else {
                echo "Ukuran file gambar tidak boleh lebih dari 500 KB.";
            }
        } else {
            echo "Ekstensi file yang diperbolehkan hanya: " . implode(', ', $allowedExtensions) . ".";
        }
    }

    $sql = "INSERT INTO alumni (nama_lengkap, gambar, no_hp, email, alamat, angkatan) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $nama_lengkap, $gambar, $no_hp, $email, $alamat, $angkatan);

    if ($stmt->execute()) {
        header('Location: alumni.php');
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Alumni</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>Tambah Data Alumni</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="nama_lengkap">Nama Lengkap:</label>
        <input type="text" name="nama_lengkap" id="nama_lengkap" required>
        <br>
        <label for="gambar">Gambar:</label>
        <input type="file" name="gambar" id="gambar">
        <br>
        <label for="no_hp">No HP:</label>
        <input type="text" name="no_hp" id="no_hp">
        <br>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email">
        <br>
        <label for="alamat">Alamat:</label>
        <textarea name="alamat" id="alamat"></textarea>
        <br>
        <label for="angkatan">Tahun Angkatan:</label>
        <input type="number" name="angkatan" id="angkatan">
        <br>
        <button type="submit">Tambah Data</button>
    </form>
    <a href="alumni.php">Kembali ke Daftar Alumni</a>
</body>

</html>