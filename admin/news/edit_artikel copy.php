<?php
// Perbaiki jalur ke file db_connection.php
require_once '../../db_connection.php';

$id = $_GET['id'];
$sql = "SELECT * FROM artikel WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $article = $result->fetch_assoc();
} else {
    echo "Artikel tidak ditemukan.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'];
    $nama_penulis = $_POST['nama_penulis'];
    $kategori = $_POST['kategori'];
    $isi = $_POST['isi'];
    $tanggal = $_POST['tanggal'];
    $ket_gambar = $_POST['ket_gambar'];

    // File upload handling
    $gambar = $article['gambar']; // Default to current image
    $target_dir = "img/";
    $uploadOk = 1;

    if (!empty($_FILES["gambar"]["name"])) {
        $file_name = basename($_FILES["gambar"]["name"]);
        $file_size = $_FILES["gambar"]["size"];
        $file_tmp = $_FILES["gambar"]["tmp_name"];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = array("jpg", "jpeg", "png");

        // Check file size
        if ($file_size > 500000) {
            echo "<script>alert('Kapasitas Gambar Melebihi 500kb');</script>";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if (!in_array($file_ext, $allowed_ext)) {
            echo "<script>alert('Ekstensi Gambar Tidak Didukung');</script>";
            $uploadOk = 0;
        }

        if ($uploadOk) {
            // Generate unique file name
            $unique_name = uniqid() . '.' . $file_ext;
            $target_file = $target_dir . $unique_name;

            // Move file to target directory
            if (move_uploaded_file($file_tmp, $target_file)) {
                // Remove old image if new one is uploaded
                if (!empty($article['gambar']) && file_exists($target_dir . $article['gambar'])) {
                    unlink($target_dir . $article['gambar']);
                }

                $gambar = $unique_name;
            } else {
                echo "<script>alert('Gagal Mengunggah Gambar');</script>";
            }
        }
    }

    // Update database
    $sql = "UPDATE artikel SET judul = ?, nama_penulis = ?, kategori = ?, isi = ?, gambar = ?, ket_gambar = ?, tanggal = ?, created_at = NOW() WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssssssi', $judul, $nama_penulis, $kategori, $isi, $gambar, $ket_gambar, $tanggal, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Artikel berhasil diperbarui'); window.location.href='artikel.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui artikel');</script>";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Artikel</title>
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
    <div class="container">
        <h1>Edit Artikel</h1>
        <form action="edit_artikel.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
            <label for="judul">Judul:</label>
            <input type="text" name="judul" id="judul" value="<?php echo htmlspecialchars($article['judul']); ?>"
                required>

            <label for="nama_penulis">Nama Penulis:</label>
            <input type="text" name="nama_penulis" id="nama_penulis"
                value="<?php echo htmlspecialchars($article['nama_penulis']); ?>" required>

            <label for="kategori">Kategori:</label>
            <select name="kategori" id="kategori" required>
                <option value="Politik" <?php if ($article['kategori'] == 'Politik')
                    echo 'selected'; ?>>Politik</option>
                <option value="Hukum" <?php if ($article['kategori'] == 'Hukum')
                    echo 'selected'; ?>>Hukum</option>
                <option value="Ekonomi" <?php if ($article['kategori'] == 'Ekonomi')
                    echo 'selected'; ?>>Ekonomi</option>
                <option value="Kesehatan" <?php if ($article['kategori'] == 'Kesehatan')
                    echo 'selected'; ?>>Kesehatan
                </option>
                <option value="Sosial" <?php if ($article['kategori'] == 'Sosial')
                    echo 'selected'; ?>>Sosial</option>
                <option value="Lingkungan" <?php if ($article['kategori'] == 'Lingkungan')
                    echo 'selected'; ?>>Lingkungan
                </option>
                <option value="Teknologi" <?php if ($article['kategori'] == 'Teknologi')
                    echo 'selected'; ?>>Teknologi
                </option>
                <option value="Olahraga" <?php if ($article['kategori'] == 'Olahraga')
                    echo 'selected'; ?>>Olahraga
                </option>
                <option value="Hiburan" <?php if ($article['kategori'] == 'Hiburan')
                    echo 'selected'; ?>>Hiburan</option>
            </select>

            <label for="isi">Isi Artikel:</label>
            <textarea name="isi" id="isi" required><?php echo htmlspecialchars($article['isi']); ?></textarea>

            <label for="gambar">Gambar:</label>
            <input type="file" name="gambar" id="gambar">
            <?php if (!empty($article['gambar'])): ?>
            <p>Gambar saat ini: <img src="img/<?php echo $article['gambar']; ?>" alt="Gambar Artikel"
                    style="width: 100px; height: auto;"></p>
            <?php endif; ?>

            <label for="ket_gambar">Keterangan Gambar:</label>
            <input type="text" name="ket_gambar" id="ket_gambar"
                value="<?php echo htmlspecialchars($article['ket_gambar']); ?>">

            <label for="tanggal">Tanggal:</label>
            <input type="date" name="tanggal" id="tanggal" value="<?php echo $article['tanggal']; ?>" required>

            <input type="submit" value="Simpan Perubahan">
        </form>
        <a href="artikel.php">Kembali ke Manajemen Artikel</a>
    </div>
</body>

</html>