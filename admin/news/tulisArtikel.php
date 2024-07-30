<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: ../login.php');
    exit();
}

// Proses menyimpan artikel jika form telah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require '../db_connection.php';

    $judul = $_POST['judul'];
    $kategori = $_POST['kategori'];
    $isi = $_POST['isi'];
    $penulis = $_POST['penulis'];
    $tanggal = $_POST['tanggal'];
    $gambar = null;
    $keteranganGambar = $_POST['keteranganGambar'] ?? null;
    $mediaSosial = $_POST['mediaSosial'] ?? null;

    // Proses upload gambar jika ada
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['gambar']['tmp_name'];
        $fileName = $_FILES['gambar']['name'];
        $fileSize = $_FILES['gambar']['size'];
        $fileType = $_FILES['gambar']['type'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        if (in_array($fileExtension, $allowedExtensions) && $fileSize <= 500000) { // 500 KB
            $newFileName = uniqid() . '.' . $fileExtension;
            $uploadFileDir = 'img/';
            $dest_path = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $gambar = $newFileName;
            } else {
                echo "Error uploading file.";
                exit();
            }
        } else {
            echo "Invalid file extension or file size too large.";
            exit();
        }
    }

    // Simpan data ke database
    $sql = "INSERT INTO artikel (judul, kategori, isi, penulis, tanggal, gambar, keterangan_gambar, media_sosial)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss", $judul, $kategori, $isi, $penulis, $tanggal, $gambar, $keteranganGambar, $mediaSosial);
    if ($stmt->execute()) {
        echo "Artikel berhasil di upload.";
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
    header('Location: artikel.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tulis Artikel</title>
    <link rel="stylesheet" href="../style.css"> <!-- Pastikan style.css mengarah ke file CSS yang benar -->
</head>

<body>
    <h1>Tulis Artikel</h1>
    <form action="tulisArtikel.php" method="post" enctype="multipart/form-data">
        <label for="judul">Judul Artikel:</label><br>
        <input type="text" id="judul" name="judul" required><br><br>

        <label for="kategori">Kategori Artikel:</label><br>
        <select id="kategori" name="kategori" required>
            <option value="Politik">Politik</option>
            <option value="Hukum">Hukum</option>
            <option value="Ekonomi">Ekonomi</option>
            <option value="Sosial">Sosial</option>
            <option value="Pendidikan">Pendidikan</option>
            <option value="Teknologi">Teknologi</option>
            <option value="Kesehatan">Kesehatan</option>
            <option value="Lingkungan">Lingkungan</option>
            <option value="Olahraga">Olahraga</option>
        </select><br><br>

        <label for="isi">Isi Artikel:</label><br>
        <textarea id="isi" name="isi" rows="10" cols="50" required></textarea><br><br>

        <label for="penulis">Nama Penulis:</label><br>
        <input type="text" id="penulis" name="penulis" required><br><br>

        <label for="tanggal">Tanggal:</label><br>
        <input type="date" id="tanggal" name="tanggal" required><br><br>

        <label for="gambar">Gambar:</label><br>
        <input type="file" id="gambar" name="gambar" accept=".jpg,.jpeg,.png"><br><br>

        <label for="keteranganGambar">Keterangan Gambar:</label><br>
        <input type="text" id="keteranganGambar" name="keteranganGambar"><br><br>

        <label for="mediaSosial">Media Sosial (Opsional):</label><br>
        <input type="url" id="mediaSosial" name="mediaSosial" placeholder="Link Media Sosial"><br><br>

        <input type="submit" value="Submit">
    </form>
    <button onclick="location.href='artikel.php'">Kembali ke Manajemen Artikel</button>
</body>

</html>