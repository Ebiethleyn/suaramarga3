<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['email'])) {
    header('Location: ../login.php');
    exit();
}

require '../db_connection.php';

// Mengambil data dari database
$sql = "SELECT * FROM alumni";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Alumni</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>Daftar Alumni</h1>
    <a href="tambah_alumni.php">Tambah Data</a>
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
                <th>Aksi</th>
                <th>Nama Lengkap</th>
                <th>Gambar</th>
                <th>No HP</th>
                <th>Email</th>
                <th>Alamat</th>
                <th>Tahun Angkatan</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
            <?php $no = 1; ?>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td>
                    <a href="edit_alumni.php?id=<?php echo $row['id']; ?>">Ubah</a> |
                    <a href="hapus_alumni.php?id=<?php echo $row['id']; ?>"
                        onclick="return confirm('Anda yakin ingin menghapus data ini?')">Hapus</a>
                </td>
                <td><?php echo htmlspecialchars($row['nama_lengkap']); ?></td>
                <td>
                    <?php if ($row['gambar']): ?>
                    <img src="img/<?php echo $row['gambar']; ?>" width="50">
                    <?php else: ?>
                    Tidak ada gambar
                    <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($row['no_hp']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['alamat']); ?></td>
                <td><?php echo htmlspecialchars($row['angkatan']); ?></td>
            </tr>
            <?php endwhile; ?>
            <?php else: ?>
            <tr>
                <td colspan="8">Tidak ada data</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <br>
    <a href="../admin/dashboard.php">Kembali ke Dashboard</a> <!-- Path relatif ke dashboard.php -->
</body>

</html>
<?php
$conn->close();
?>