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
    <style>
        /* Form Style */
        form input[type="text"],
        form input[type="file"],
        form select,
        form textarea {
            width: calc(100% - 20px);
            /* Menghindari overflow */
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        form label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        form textarea {
            height: 150px;
            resize: vertical;
        }

        form input[type="submit"],
        form button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        form input[type="submit"]:hover,
        form button:hover {
            background-color: #218838;
        }
    </style>
</head>

<body>
    <h2>Tambah Artikel</h2>
    <form action="tambah_artikel.php" method="POST" enctype="multipart/form-data">
        <label>Judul Artikel:</label><br>
        <input type="text" name="judul" required><br>

        <label>Nama Penulis:</label><br>
        <input type="text" name="nama_penulis" required><br>

        <label>Kategori Artikel:</label><br>
        <select name="kategori" required>
            <option value="Politik">Politik</option>
            <option value="Hukum">Hukum</option>
            <option value="Ekonomi">Ekonomi</option>
            <option value="Kesehatan">Kesehatan</option>
            <option value="Sosial">Sosial</option>
            <option value="Lingkungan">Lingkungan</option>
            <option value="Teknologi">Teknologi</option>
            <option value="Olahraga">Olahraga</option>
            <option value="Hiburan">Hiburan</option>
        </select><br>

        <label>Isi Artikel:</label><br>
        <textarea name="isi" required></textarea><br>

        <label>Tanggal:</label><br>
        <input type="date" name="tanggal" required><br>

        <label>Gambar (opsional):</label><br>
        <input type="file" name="gambar"><br>

        <label>Keterangan Gambar:</label><br>
        <input type="text" name="ket_gambar"><br>

        <input type="submit" value="Submit">
    </form>
    <br>
    <a href="artikel.php">Kembali ke Manajemen Artikel</a>
</body>

</html>