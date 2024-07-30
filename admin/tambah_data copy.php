<?php
require '../db_connection.php';

// Fungsi untuk upload gambar
function uploadImage($image, $targetDir)
{
    $allowedExtensions = ['jpg', 'jpeg', 'png'];
    $maxFileSize = 500 * 1024; // 500 KB

    $fileName = $image['name'];
    $fileTmpName = $image['tmp_name'];
    $fileSize = $image['size'];
    $fileError = $image['error'];
    $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Check for allowed extensions
    if (!in_array($fileType, $allowedExtensions)) {
        return "File dengan ekstensi $fileType tidak diperbolehkan.";
    }

    // Check file size
    if ($fileSize > $maxFileSize) {
        return "Ukuran file terlalu besar. Maksimal adalah 500 KB.";
    }

    // Generate unique file name
    $newFileName = uniqid() . '.' . $fileType;

    // Move the file to target directory
    $targetFilePath = $targetDir . $newFileName;
    if (move_uploaded_file($fileTmpName, $targetFilePath)) {
        return $newFileName;
    } else {
        return "Gagal meng-upload file.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $namaLengkap = $_POST['nama_lengkap'];
    $noHp = $_POST['no_hp'];
    $email = $_POST['email'];
    $asalKampus = $_POST['asal_kampus'];
    $asalDaerah = $_POST['asal_daerah'];
    $tahunAngkatan = $_POST['tahun_angkatan'];

    // Proses gambar
    if (isset($_FILES['gambar'])) {
        $uploadResult = uploadImage($_FILES['gambar'], '../admin/img/');

        if (strpos($uploadResult, "tidak diperbolehkan") !== false || strpos($uploadResult, "terlalu besar") !== false || strpos($uploadResult, "Gagal") !== false) {
            echo $uploadResult; // Tampilkan pesan kesalahan
            exit();
        }

        $gambar = $uploadResult;
    } else {
        $gambar = null;
    }

    // Insert data to database
    $sql = "INSERT INTO anggota_biasa (nama_lengkap, gambar, no_hp, email, asal_kampus, asal_daerah, tahun_angkatan) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $namaLengkap, $gambar, $noHp, $email, $asalKampus, $asalDaerah, $tahunAngkatan);

    // Execute the query and check for errors
    if ($stmt->execute()) {
        echo "Data berhasil ditambahkan.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Tambah Data Anggota Biasa</title>
</head>

<body>
    <h1>Tambah Data Anggota Biasa</h1>
    <form action="tambah_data.php" method="POST" enctype="multipart/form-data">
        <label for="nama_lengkap">Nama Lengkap:</label>
        <input type="text" name="nama_lengkap" id="nama_lengkap" required><br>

        <label for="no_hp">No HP:</label>
        <input type="text" name="no_hp" id="no_hp" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required><br>

        <label for="asal_kampus">Asal Kampus:</label>
        <input type="text" name="asal_kampus" id="asal_kampus" required><br>

        <label for="asal_daerah">Asal Daerah:</label>
        <input type="text" name="asal_daerah" id="asal_daerah" required><br>

        <label for="tahun_angkatan">Tahun Angkatan:</label>
        <input type="number" name="tahun_angkatan" id="tahun_angkatan" required><br>

        <label for="gambar">Gambar:</label>
        <input type="file" name="gambar" id="gambar" required><br><br>

        <button type="submit">Tambah Data</button>
    </form>
    <br>
    <a href="anggotaBiasa.php">Kembali</a>
</body>

</html>