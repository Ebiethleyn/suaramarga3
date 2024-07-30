<?php
require_once '../../db_connection.php';

$sql = "SELECT id, judul, nama_penulis, kategori, tanggal, gambar FROM artikel ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manajemen Artikel - SuaraMarga3</title>
    <link rel="stylesheet" href="artikel.css">
</head>

<body>
    <h1>Manajemen Artikel</h1>
    <a href="tambah_artikel.php"><button>Tambah Artikel</button></a>
    <table>
        <tr>
            <th>ID</th>
            <th>Judul</th>
            <th>Nama Penulis</th>
            <th>Kategori</th>
            <th>Tanggal</th>
            <th>Gambar</th>
            <th>Aksi</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['judul']}</td>
                    <td>{$row['nama_penulis']}</td>
                    <td>{$row['kategori']}</td>
                    <td>{$row['tanggal']}</td>
                    <td><img src='img/{$row['gambar']}' alt='Gambar' width='50'></td>
                    <td>
                        <a href='edit_artikel.php?id={$row['id']}'>Edit</a> |
                        <a href='hapus_artikel.php?id={$row['id']}' onclick='return confirm(\"Apakah Anda yakin ingin menghapus artikel ini?\")'>Hapus</a>
                    </td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='7'>Tidak ada artikel yang tersedia</td></tr>";
        }
        ?>
    </table>

    <a href="../dashboard.php"><button>Kembali ke Dashboard</button></a>
</body>

</html>

<?php $conn->close(); ?>