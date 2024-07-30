<?php
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
    <style>
    .form-container {
        max-width: 700px;
        margin: auto;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .form-container img {
        max-width: 100%;
        height: auto;
        display: block;
        margin-bottom: 10px;
    }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="form-container">
            <h2 class="text-center mb-4">Edit Artikel</h2>
            <form action="edit_artikel.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="judul" class="form-label">Judul</label>
                    <input type="text" class="form-control" id="judul" name="judul"
                        value="<?php echo htmlspecialchars($article['judul']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="nama_penulis" class="form-label">Nama Penulis</label>
                    <input type="text" class="form-control" id="nama_penulis" name="nama_penulis"
                        value="<?php echo htmlspecialchars($article['nama_penulis']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="kategori" class="form-label">Kategori</label>
                    <select class="form-select" id="kategori" name="kategori" required>
                        <option value="Politik" <?php if ($article['kategori'] == 'Politik')
                            echo 'selected'; ?>>Politik
                        </option>
                        <option value="Hukum" <?php if ($article['kategori'] == 'Hukum')
                            echo 'selected'; ?>>Hukum
                        </option>
                        <option value="Ekonomi" <?php if ($article['kategori'] == 'Ekonomi')
                            echo 'selected'; ?>>Ekonomi
                        </option>
                        <option value="Kesehatan" <?php if ($article['kategori'] == 'Kesehatan')
                            echo 'selected'; ?>>
                            Kesehatan</option>
                        <option value="Sosial" <?php if ($article['kategori'] == 'Sosial')
                            echo 'selected'; ?>>Sosial
                        </option>
                        <option value="Lingkungan" <?php if ($article['kategori'] == 'Lingkungan')
                            echo 'selected'; ?>>
                            Lingkungan</option>
                        <option value="Teknologi" <?php if ($article['kategori'] == 'Teknologi')
                            echo 'selected'; ?>>
                            Teknologi</option>
                        <option value="Olahraga" <?php if ($article['kategori'] == 'Olahraga')
                            echo 'selected'; ?>>
                            Olahraga</option>
                        <option value="Hiburan" <?php if ($article['kategori'] == 'Hiburan')
                            echo 'selected'; ?>>Hiburan
                        </option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="isi" class="form-label">Isi Artikel</label>
                    <textarea class="form-control" id="isi" name="isi" rows="5"
                        required><?php echo htmlspecialchars($article['isi']); ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="gambar" class="form-label">Gambar (opsional)</label>
                    <input class="form-control" type="file" id="gambar" name="gambar">
                    <?php if (!empty($article['gambar'])): ?>
                    <img src="img/<?php echo $article['gambar']; ?>" alt="Gambar Artikel">
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label for="ket_gambar" class="form-label">Keterangan Gambar</label>
                    <input type="text" class="form-control" id="ket_gambar" name="ket_gambar"
                        value="<?php echo htmlspecialchars($article['ket_gambar']); ?>">
                </div>
                <div class="mb-3">
                    <label for="tanggal" class="form-label">Tanggal</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal"
                        value="<?php echo $article['tanggal']; ?>" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Simpan Perubahan</button>
            </form>
            <a href="artikel.php" class="btn btn-secondary w-100 mt-3">Kembali ke Manajemen Artikel</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
</body>

</html>