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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU0fK9KRq/4kucH9G2quUgaFsvHXbI7nPQ/t+" crossorigin="anonymous">
    <style>
    body {
        background-color: #f8f9fa;
    }

    .container {
        margin-top: 20px;
    }

    h1 {
        text-align: center;
        margin-bottom: 20px;
        color: #343a40;
    }

    table {
        margin-top: 20px;
    }

    table img {
        border-radius: 50%;
    }

    .btn {
        margin-top: 20px;
    }

    @media (max-width: 768px) {
        table {
            font-size: 12px;
        }
    }

    @media (max-width: 576px) {
        table {
            font-size: 10px;
        }
    }
    </style>
</head>

<body>
    <div class="container">
        <h1>Daftar Alumni</h1>
        <div class="d-flex justify-content-end mb-3">
            <a href="tambah_alumni.php" class="btn btn-primary">Tambah Data</a>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
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
                            <a href="edit_alumni.php?id=<?php echo $row['id']; ?>"
                                class="btn btn-warning btn-sm">Ubah</a>
                            <a href="hapus_alumni.php?id=<?php echo $row['id']; ?>"
                                onclick="return confirm('Anda yakin ingin menghapus data ini?')"
                                class="btn btn-danger btn-sm">Hapus</a>
                        </td>
                        <td><?php echo htmlspecialchars($row['nama_lengkap']); ?></td>
                        <td>
                            <?php if ($row['gambar']): ?>
                            <img src="img/<?php echo $row['gambar']; ?>" width="50" alt="Gambar Alumni">
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
                        <td colspan="8" class="text-center">Tidak ada data</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-end mt-3">
            <a href="../admin/dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kaCPtt1BICNU66G72juu2k6wKZqv9RVYpcl0pX0I0/uXKJD7xUK7U7+KHsXLh4tx" crossorigin="anonymous">
    </script>
</body>

</html>

<?php
$conn->close();
?>