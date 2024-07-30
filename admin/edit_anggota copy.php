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
                echo "File ". htmlspecialchars(basename($_FILES['gambar']['name'])) . " telah diunggah.";
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
    <link rel="stylesheet" href="anggotaBiasa.css">
</head>

<body>
    <h1>Edit Data Anggota</h1>
    <form action="edit_anggota.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
        <label for="tahun_angkatan">Tahun Angkatan:</label>
        <input type="text" name="tahun_angkatan" id="tahun_angkatan"
            value="<?php echo htmlspecialchars($data['tahun_angkatan']); ?>" required>
        <br>
        <label for="nama_lengkap">Nama Lengkap:</label>
        <input type="text" name="nama_lengkap" id="nama_lengkap"
            value="<?php echo htmlspecialchars($data['nama_lengkap']); ?>" required>
        <br>
        <label for="gambar">Gambar:</label>
        <input type="file" name="gambar" id="gambar">
        <br>
        <label for="no_hp">No Hp:</label>
        <input type="text" name="no_hp" id="no_hp" value="<?php echo htmlspecialchars($data['no_hp']); ?>" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($data['email']); ?>" required>
        <br>
        <label for="asal_kampus">Asal Kampus:</label>
        <input type="text" name="asal_kampus" id="asal_kampus"
            value="<?php echo htmlspecialchars($data['asal_kampus']); ?>" required>
        <br>
        <label for="asal_daerah">Asal Daerah:</label>
        <input type="text" name="asal_daerah" id="asal_daerah"
            value="<?php echo htmlspecialchars($data['asal_daerah']); ?>" required>
        <br>
        <button type="submit" name="update">Perbarui Data</button>
    </form>
    <br>
    <a href="anggotaBiasa.php">Kembali ke Daftar Anggota</a>
</body>

</html>