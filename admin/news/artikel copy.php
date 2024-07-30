<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Artikel</title>
    <link rel="stylesheet" type="text/css" href="artikel.css">

</head>

<body>
    <h1>Manajemen Artikel</h1>
    <a href="../dashboard.php">Kembali ke dashboard</a> <!-- Tombol untuk kembali ke dashboard admin -->
    <br><br>
    <a href="tambah_artikel.php">Tambah Artikel</a>

    <table border="1">
        <tr>
            <th>Judul</th>
            <th>Nama Penulis</th>
            <th>Kategori</th>
            <th>Gambar</th>
            <th>Isi</th>
            <th>Tanggal</th>
            <th>Opsi</th>
        </tr>
        <?php
        require_once '../../db_connection.php';

        $sql = "SELECT * FROM artikel ORDER BY created_at DESC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['judul'] . "</td>";
                echo "<td>" . $row['nama_penulis'] . "</td>";
                echo "<td>" . $row['kategori'] . "</td>";
                echo "<td><img src='img/" . $row['gambar'] . "' width='100'></td>";
                echo "<td>" . substr($row['isi'], 0, 100) . "...</td>";
                echo "<td>" . $row['tanggal'] . "</td>";
                echo "<td>
                        <a href='edit_artikel.php?id=" . $row['id'] . "'>Edit</a> |
                        <a href='hapus_artikel.php?id=" . $row['id'] . "' onclick='return confirm(\"Apakah Anda yakin ingin menghapus artikel ini?\");'>Hapus</a>
                    </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>Tidak ada artikel.</td></tr>";
        }

        $conn->close();
        ?>
    </table>
</body>

</html>