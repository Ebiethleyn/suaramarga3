<!DOCTYPE html>
<html>

<head>
    <title>Dashboard Admin</title>
    <link rel="stylesheet" type="text/css" href="dashboard.css">
</head>

<body>
    <div class="header">
        <div class="container">
            <h1>SuaraMarga3.com</h1>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <h2>Dashboard Admin</h2>
            <div class="buttons">
                <a href="news/artikel.php" class="button">Manajemen Artikel</a>
                <a href="anggotaBiasa.php" class="button">Daftar Anggota Biasa</a>
                <a href="../alumni/alumni.php" class="button">Daftar Alumni</a>
                <a href="logout.php" class="button">Logout</a>
            </div>
        </div>
    </div>

    <div class="footer">
        <div class="container">
            <p>PMKRI - Jaktim</p>
            <p><a href="#">Home</a> - <a href="#">About</a> - <a href="#">Article</a></p>
            <p>© 2024 Ebieth Leyn</p>
        </div>
    </div>

    <script>
        function toggleMenu() {
            var menu = document.querySelector(".menu");
            if (menu.style.display === "none" || menu.style.display === "") {
                menu.style.display = "block";
            } else {
                menu.style.display = "none";
            }
        }
    </script>
</body>

</html>