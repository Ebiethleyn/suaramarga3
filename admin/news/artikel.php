<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Artikel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        background-color: #f8f9fa;
        padding: 20px;
    }

    h1 {
        text-align: center;
        margin-bottom: 20px;
        color: #343a40;
    }

    .btn-primary,
    .btn-secondary {
        margin-bottom: 20px;
        margin-right: 10px;
    }

    table {
        margin-top: 20px;
    }

    .table img {
        max-width: 100px;
        height: auto;
        border-radius: 8px;
    }

    @media (max-width: 768px) {
        table {
            font-size: 14px;
        }
    }

    @media (max-width: 576px) {
        table {
            font-size: 12px;
        }
    }
    </style>
</head>

<body>
    <div class="container">
        <h1>Manajemen Artikel</h1>
        <div class="d-flex justify-content-end">
            <a href="../dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>
            <a href="tambah_artikel.php" class="btn btn-primary">Tambah Artikel</a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Judul</th>
                        <th>Nama Penulis</th>
                        <th>Kategori</th>
                        <th>Gambar</th>
                        <th>Isi</th>
                        <th>Tanggal</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    require_once '../../db_connection.php';

                    $sql = "SELECT * FROM artikel ORDER BY created_at DESC";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['judul']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['nama_penulis']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['kategori']) . "</td>";
                            echo "<td><img src='img/" . htmlspecialchars($row['gambar']) . "' alt='Gambar Artikel'></td>";
                            echo "<td>" . htmlspecialchars(substr($row['isi'], 0, 100)) . "...</td>";
                            echo "<td>" . htmlspecialchars($row['tanggal']) . "</td>";
                            echo "<td>
                                    <a href='edit_artikel.php?id=" . htmlspecialchars($row['id']) . "' class='btn btn-warning btn-sm'>Edit</a>
                                    <a href='hapus_artikel.php?id=" . htmlspecialchars($row['id']) . "' onclick='return confirm(\"Apakah Anda yakin ingin menghapus artikel ini?\");' class='btn btn-danger btn-sm'>Hapus</a>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center'>Tidak ada artikel.</td></tr>";
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>