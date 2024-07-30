<?php
require '../db_connection.php'; // Koneksi ke database

// Fungsi untuk mendapatkan data anggota
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM anggota_biasa WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();
}

// Fungsi untuk memperbarui data anggota
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $tahun_angkatan = $_POST['tahun_angkatan'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $no_hp = $_POST['no_hp'];
    $email = $_POST['email'];
    $asal_kampus = $_POST['asal_kampus'];
    $asal_daerah = $_POST['asal_daerah'];

    // Upload gambar
    $uploadOk = 1;
    $gambar = $data['gambar']; // Default gambar dari data lama
    if (!empty($_FILES['gambar']['name'])) {
        $target_dir = "img/";
        $imageFileType = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        $gambar = uniqid() . '.' . $imageFileType;
        $target_file = $target_dir . $gambar;

        // Cek apakah file gambar atau bukan
        $check = getimagesize($_FILES['gambar']['tmp_name']);
        if ($check === false) {
            echo "File bukan gambar.";
            $uploadOk = 0;
        }

        // Cek ukuran file
        if ($_FILES['gambar']['size'] > 500000) {
            echo "Ukuran file terlalu besar.";
            $uploadOk = 0;
        }

        // Hanya ekstensi tertentu yang diperbolehkan
        $allowed_extensions = ['jpg', 'jpeg', 'png'];
        if (!in_array($imageFileType, $allowed_extensions)) {
            echo "Hanya file JPG, JPEG, dan PNG yang diperbolehkan.";
            $uploadOk = 0;
        }

        // Cek apakah $uploadOk = 0 oleh error
        if ($uploadOk == 0) {
            echo "Maaf, file Anda tidak dapat diunggah.";
        } else {
            // Jika semuanya benar, pindahkan file ke folder tujuan
            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
                echo "File " . htmlspecialchars(basename($_FILES['gambar']['name'])) . " telah diunggah.";
            } else {
                echo "Ada kesalahan saat mengunggah file Anda.";
            }
        }
    }

    // Update data ke dalam database
    if ($uploadOk == 1) {
        $sql = "UPDATE anggota_biasa SET tahun_angkatan = ?, nama_lengkap = ?, gambar = ?, no_hp = ?, email = ?, asal_kampus = ?, asal_daerah = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssi", $tahun_angkatan, $nama_lengkap, $gambar, $no_hp, $email, $asal_kampus, $asal_daerah, $id);
        if ($stmt->execute()) {
            echo "Data berhasil diperbarui.";
            header('Location: anggotaBiasa.php'); // Redirect ke halaman anggotaBiasa.php
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Anggota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-body">
                <h2 class="card-title text-center">Edit Data Anggota</h2>
                <form action="edit_anggota.php" method="post" enctype="multipart/form-data" class="mt-4">
                    <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
                    <div class="mb-3">
                        <label for="tahun_angkatan" class="form-label">Tahun Angkatan</label>
                        <input type="text" class="form-control" id="tahun_angkatan" name="tahun_angkatan"
                            value="<?php echo htmlspecialchars($data['tahun_angkatan']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap"
                            value="<?php echo htmlspecialchars($data['nama_lengkap']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="gambar" class="form-label">Gambar</label>
                        <input type="file" class="form-control" id="gambar" name="gambar">
                    </div>
                    <div class="mb-3">
                        <label for="no_hp" class="form-label">No Hp</label>
                        <input type="text" class="form-control" id="no_hp" name="no_hp"
                            value="<?php echo htmlspecialchars($data['no_hp']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="<?php echo htmlspecialchars($data['email']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="asal_kampus" class="form-label">Asal Kampus</label>
                        <input type="text" class="form-control" id="asal_kampus" name="asal_kampus"
                            value="<?php echo htmlspecialchars($data['asal_kampus']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="asal_daerah" class="form-label">Asal Daerah</label>
                        <input type="text" class="form-control" id="asal_daerah" name="asal_daerah"
                            value="<?php echo htmlspecialchars($data['asal_daerah']); ?>" required>
                    </div>
                    <button type="submit" name="update" class="btn btn-primary w-100">Perbarui Data</button>
                </form>
                <a href="anggotaBiasa.php" class="btn btn-secondary w-100 mt-3">Kembali ke Daftar Anggota</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
</body>

</html>