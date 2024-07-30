<?php
require_once '../../db_connection.php';

// Function to generate unique file name
function generateUniqueFileName($extension)
{
    return uniqid('img_', true) . '.' . strtolower($extension);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = $_POST['judul'];
    $nama_penulis = $_POST['nama_penulis'];
    $kategori = $_POST['kategori'];
    $isi = $_POST['isi'];
    $tanggal = date('Y-m-d', strtotime($_POST['tanggal']));
    $gambar = '';
    $ket_gambar = $_POST['ket_gambar'];
    $created_at = date('Y-m-d H:i:s');

    // File upload handling
    if (!empty($_FILES['gambar']['name'])) {
        $file_name = $_FILES['gambar']['name'];
        $file_tmp = $_FILES['gambar']['tmp_name'];
        $file_size = $_FILES['gambar']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $allowed_extensions = ['jpg', 'jpeg', 'png'];

        if (in_array($file_ext, $allowed_extensions) && $file_size <= 500000) { // 500 KB limit
            $new_file_name = generateUniqueFileName($file_ext);
            $upload_dir = 'img/';
            $upload_file = $upload_dir . $new_file_name;

            if (move_uploaded_file($file_tmp, $upload_file)) {
                $gambar = $new_file_name;
            }
        } else {
            if (!in_array($file_ext, $allowed_extensions)) {
                echo "<script>alert('Ekstensi gambar tidak didukung');</script>";
            } elseif ($file_size > 500000) {
                echo "<script>alert('Kapasitas gambar melebihi 500KB');</script>";
            }
            exit;
        }
    }

    // Insert data into database
    $sql = "INSERT INTO artikel (judul, nama_penulis, kategori, isi, gambar, ket_gambar, tanggal, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssssss', $judul, $nama_penulis, $kategori, $isi, $gambar, $ket_gambar, $tanggal, $created_at);

    if ($stmt->execute()) {
        echo "<script>alert('Artikel berhasil diupload');</script>";
        echo "<script>window.location.href='artikel.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Tambah Artikel</title>
    <link rel="stylesheet" type="text/css" href="artikel.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Tambah Artikel</h2>
        <form action="tambah_artikel.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="judul" class="form-label">Judul Artikel</label>
                <input type="text" class="form-control" id="judul" name="judul" required>
            </div>
            <div class="mb-3">
                <label for="nama_penulis" class="form-label">Nama Penulis</label>
                <input type="text" class="form-control" id="nama_penulis" name="nama_penulis" required>
            </div>
            <div class="mb-3">
                <label for="kategori" class="form-label">Kategori Artikel</label>
                <select class="form-select" id="kategori" name="kategori" required>
                    <option value="Politik">Politik</option>
                    <option value="Hukum">Hukum</option>
                    <option value="Ekonomi">Ekonomi</option>
                    <option value="Kesehatan">Kesehatan</option>
                    <option value="Sosial">Sosial</option>
                    <option value="Lingkungan">Lingkungan</option>
                    <option value="Teknologi">Teknologi</option>
                    <option value="Olahraga">Olahraga</option>
                    <option value="Hiburan">Hiburan</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="isi" class="form-label">Isi Artikel</label>
                <textarea class="form-control" id="isi" name="isi" rows="5" required></textarea>
            </div>
            <div class="mb-3">
                <label for="tanggal" class="form-label">Tanggal</label>
                <input type="date" class="form-control" id="tanggal" name="tanggal" required>
            </div>
            <div class="mb-3">
                <label for="gambar" class="form-label">Gambar (opsional)</label>
                <input class="form-control" type="file" id="gambar" name="gambar">
            </div>
            <div class="mb-3">
                <label for="ket_gambar" class="form-label">Keterangan Gambar</label>
                <input type="text" class="form-control" id="ket_gambar" name="ket_gambar">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        <br>
        <a href="artikel.php" class="btn btn-secondary">Kembali ke Manajemen Artikel</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
</body>

</html>