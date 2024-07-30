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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Anggota Biasa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-body">
                <h2 class="card-title text-center">Tambah Data Anggota Biasa</h2>
                <form action="tambah_data.php" method="POST" enctype="multipart/form-data" class="mt-4">
                    <div class="mb-3">
                        <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required>
                    </div>
                    <div class="mb-3">
                        <label for="no_hp" class="form-label">No HP</label>
                        <input type="text" class="form-control" id="no_hp" name="no_hp" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="asal_kampus" class="form-label">Asal Kampus</label>
                        <input type="text" class="form-control" id="asal_kampus" name="asal_kampus" required>
                    </div>
                    <div class="mb-3">
                        <label for="asal_daerah" class="form-label">Asal Daerah</label>
                        <input type="text" class="form-control" id="asal_daerah" name="asal_daerah" required>
                    </div>
                    <div class="mb-3">
                        <label for="tahun_angkatan" class="form-label">Tahun Angkatan</label>
                        <input type="number" class="form-control" id="tahun_angkatan" name="tahun_angkatan" required>
                    </div>
                    <div class="mb-3">
                        <label for="gambar" class="form-label">Gambar</label>
                        <input type="file" class="form-control" id="gambar" name="gambar" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Tambah Data</button>
                </form>
                <a href="anggotaBiasa.php" class="btn btn-secondary w-100 mt-3">Kembali</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
</body>

</html>