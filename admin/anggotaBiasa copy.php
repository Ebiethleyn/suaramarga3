<?php
require '../db_connection.php'; // Koneksi ke database

// Fungsi untuk menghapus data
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM anggota_biasa WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "Data berhasil dihapus.";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Mengambil data dari tabel anggota_biasa
$sql = "SELECT id, tahun_angkatan, nama_lengkap, gambar, no_hp, email, asal_kampus, asal_daerah FROM anggota_biasa";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Anggota Biasa</title>
    <link rel="stylesheet" href="anggotaBiasa.css">
</head>

<body>
    <h1>Kelola Anggota Biasa</h1>
    <a href="tambah_data.php">Tambah Data</a>
    <table>
        <tr>
            <th>No</th>
            <th>Aksi</th>
            <th>Nama Lengkap</th>
            <th>Gambar</th>
            <th>No Hp</th>
            <th>Email</th>
            <th>Asal Kampus</th>
            <th>Asal Daerah</th>
            <th>Tahun Angkatan</th>
        </tr>
        <?php if ($result->num_rows > 0) {
            $no = 1;
            while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $no++; ?></td>
            <td>
                <a href="anggotaBiasa.php?delete=<?php echo $row['id']; ?>"
                    onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a> |
                <a href="edit_anggota.php?id=<?php echo $row['id']; ?>">Edit</a>
            </td>
            <td><?php echo htmlspecialchars($row['nama_lengkap']); ?></td>
            <td><img src="img/<?php echo htmlspecialchars($row['gambar']); ?>" width="50" alt="Gambar"></td>
            <td><?php echo htmlspecialchars($row['no_hp']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td><?php echo htmlspecialchars($row['asal_kampus']); ?></td>
            <td><?php echo htmlspecialchars($row['asal_daerah']); ?></td>
            <td><?php echo htmlspecialchars($row['tahun_angkatan']); ?></td>
        </tr>
        <?php }
        } else { ?>
        <tr>
            <td colspan="9">Tidak ada data.</td>
        </tr>
        <?php } ?>
    </table>
    <br>
    <a href="dashboard.php">Kembali ke Dashboard</a>
</body>

</html>

<?php
$conn->close();
?>