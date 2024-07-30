<?php
require '../db_connection.php'; // Koneksi ke database

// Fungsi untuk menghapus data
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM anggota_biasa WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil dihapus.'); window.location='anggotaBiasa.php';</script>";
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
    <link rel="stylesheet" href="anggotaBiasa.css">
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
        width: 100%;
        margin-top: 20px;
        margin-bottom: 20px;
    }

    table th,
    table td {
        text-align: center;
        vertical-align: middle;
    }

    table img {
        border-radius: 50%;
    }

    .btn {
        margin-top: 20px;
    }

    .btn-danger,
    .btn-warning {
        margin-right: 10px;
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
        <h1>Daftar Anggota Biasa</h1>
        <div class="d-flex justify-content-end">
            <a href="tambah_data.php" class="btn btn-primary">Tambah Data</a>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
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
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0) {
                        $no = 1;
                        while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td>
                            <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $row['id']; ?>)"
                                class="btn btn-danger">Hapus</a>
                            <a href="edit_anggota.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">Edit</a>
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
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-end">
            <a href="dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>
        </div>
    </div>

    <!-- Modal Konfirmasi Penghapusan -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Penghapusan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus data ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Hapus</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script>
    function confirmDelete(id) {
        const deleteBtn = document.getElementById('confirmDeleteBtn');
        deleteBtn.href = 'anggotaBiasa.php?delete=' + id;
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }
    </script>
</body>

</html>

<?php
$conn->close();
?>