<?php
require_once '../../db_connection.php';

// Delete article
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch current image
    $sql = "SELECT gambar FROM artikel WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $article = $result->fetch_assoc();
    $gambar = $article['gambar'];

    // Delete from database
    $sql = "DELETE FROM artikel WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        // Delete the image file
        if ($gambar && file_exists("img/$gambar")) {
            unlink("img/$gambar");
        }
        echo "<script>alert('Artikel berhasil dihapus');</script>";
        echo "<script>window.location.href='artikel.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Hapus Artikel</title>
    <link rel="stylesheet" type="text/css" href="artikel.css">

</head>

<body>
    <h2>Hapus Artikel</h2>
    <form action="hapus_artikel.php" method="GET">
        <label>Pilih artikel untuk dihapus:</label><br>
        <select name="id">
            <?php
            $sql = "SELECT id, judul FROM artikel";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                echo "<option value='{$row['id']}'>{$row['judul']}</option>";
            }
            ?>
        </select><br>
        <input type="submit" value="Hapus">
    </form>
    <br>
    <a href="artikel.php">Kembali ke Manajemen Artikel</a>
</body>

</html>