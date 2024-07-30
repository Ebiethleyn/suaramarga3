<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['email'])) {
    header('Location: ../login.php');
    exit();
}

require '../db_connection.php';

$id = $_GET['id'];
$sql = "SELECT * FROM alumni WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$alumni = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lengkap = $_POST['nama_lengkap'];
    $no_hp = $_POST['no_hp'] ?? null;
    $email = $_POST['email'] ?? null;
    $alamat = $_POST['alamat'] ?? null;
    $angkatan = $_POST['angkatan'] ?? null;

    $gambar = $alumni['gambar'];
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $fileExtension = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));

        if (in_array($fileExtension, $allowedExtensions)) {
            $uniqueName = uniqid() . '.' . $fileExtension;
            $targetFile = 'img/' . $uniqueName;

            if ($_FILES['gambar']['size'] <= 500 * 1024) {
                if (move_uploaded_file($_FILES['gambar']['tmp_name'], $targetFile)) {
                    if ($gambar && file_exists('img/' . $gambar)) {
                        unlink('img/' . $gambar);  // Hapus gambar lama
                    }
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

    $sql = "UPDATE alumni SET nama_lengkap = ?, gambar = ?, no_hp = ?, email = ?, alamat = ?, angkatan = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssii", $nama_lengkap, $gambar, $no_hp, $email, $alamat, $angkatan, $id);

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
    <title>Edit Data Alumni</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>Edit Data Alumni</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="nama_lengkap">Nama Lengkap:</label>
        <input type="text" name="nama_lengkap" id="nama_lengkap" value="<?php echo $alumni['nama_lengkap']; ?>"
            required>
        <br>
        <label for="gambar">Gambar:</label>
        <input type="file" name="gambar" id="gambar">
        <br>
        <label for="no_hp">No HP:</label>
        <input type="text" name="no_hp" id="no_hp" value="<?php echo $alumni['no_hp']; ?>">
        <br>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="<?php echo $alumni['email']; ?>">
        <br>
        <label for="alamat">Alamat:</label>
        <textarea name="alamat" id="alamat"><?php echo $alumni['alamat']; ?></textarea>
        <br>
        <label for="angkatan">Tahun Angkatan:</label>
        <input type="number" name="angkatan" id="angkatan" value="<?php echo $alumni['angkatan']; ?>">
        <br>
        <button type="submit">Simpan Perubahan</button>
    </form>
    <a href="alumni.php">Kembali ke Daftar Alumni</a>
</body>

</html>