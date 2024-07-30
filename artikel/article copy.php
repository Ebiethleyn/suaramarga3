<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Artikel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- style css -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <!-- Navbar Bootstrap Start -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark text-light fixed-top shadow">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"> SuaraMarga3.com
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="../index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                    </li>

                    <!--Kategory start -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Category
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="?category=Politik">Politik</a></li>
                            <li><a class="dropdown-item" href="?category=Hukum">Hukum</a></li>
                            <li><a class="dropdown-item" href="?category=Ekonomi">Ekonomi</a></li>
                            <li><a class="dropdown-item" href="?category=Kesehatan">Kesehatan</a></li>
                            <li><a class="dropdown-item" href="?category=Sosial">Sosial</a></li>
                            <li><a class="dropdown-item" href="?category=Lingkungan">Lingkungan</a></li>
                            <li><a class="dropdown-item" href="?category=Teknologi">Teknologi</a></li>
                            <li><a class="dropdown-item" href="?category=Olahraga">Olahraga</a></li>
                            <li><a class="dropdown-item" href="?category=Hiburan">Hiburan</a></li>
                            <!-- <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li> -->
                        </ul>
                    </li>
                    <!-- Category end -->

                    <!-- <li class="nav-item">
                        <a class="nav-link disabled">Contact</a>
                    </li> -->
                </ul>
                <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-light" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>
    <!-- Navbar Bootstrap End -->

    <!-- Navbar Start -->
    <div class="header">
        <div class="container">

            <div id="menu" class="menu">
                <!-- <a href="?category=Politik">Politik</a> -->
                <!-- <a href="?category=Hukum">Hukum</a> -->
                <!-- <a href="?category=Ekonomi">Ekonomi</a> -->
                <!-- <a href="?category=Kesehatan">Kesehatan</a> -->
                <!-- <a href="?category=Sosial">Sosial</a> -->
                <!-- <a href="?category=Lingkungan">Lingkungan</a> -->
                <!-- <a href="?category=Teknologi">Teknologi</a> -->
                <!-- <a href="?category=Olahraga">Olahraga</a> -->
                <!-- <a href="?category=Hiburan">Hiburan</a> -->
            </div>
            <!-- <div class="nav-ekstra">
                <a href="#" id="instagram"><i data-feather="instagram"></i></a>
                <a href="#" id="instagram"><i data-feather="facebook"></i></a>
                <div class="menu-icon" id="menu-cat" onclick="toggleMenu()">☰</div>
            </div> -->
        </div>
    </div>
    <!-- Navbar End -->
    <div class="content">
        <div class="container">
            <?php
            require_once '../db_connection.php';

            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                $sql = "SELECT * FROM artikel WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('i', $id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    echo "<h2>" . $row['judul'] . "</h2>";
                    echo "<p><small><a href=\"?author=" . urlencode($row['nama_penulis']) . "\">" . $row['nama_penulis'] . "</a> | " . date('d M Y', strtotime($row['tanggal'])) . "</small></p>";
                    if (!empty($row['gambar'])) {
                        echo "<img src=\"../admin/news/img/" . $row['gambar'] . "\" alt=\"Gambar Artikel\" class=\"article-image\">";
                    }
                    echo "<p class=\"image-caption\">" . $row['ket_gambar'] . "</p>";
                    echo "<div class=\"social-icons\">
                          </div>";
                    echo "<p>" . nl2br($row['isi']) . "</p>";
                } else {
                    echo "<p>Artikel tidak ditemukan.</p>";
                }
            } elseif (isset($_GET['author'])) {
                $author = $_GET['author'];
                $sql = "SELECT * FROM artikel WHERE nama_penulis = ? ORDER BY created_at DESC";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('s', $author);
                $stmt->execute();
                $result = $stmt->get_result();

                echo "<h2>Artikel oleh " . htmlspecialchars($author) . "</h2>";

                while ($row = $result->fetch_assoc()) {
                    echo "<h3><a href=\"?id=" . $row['id'] . "\">" . $row['judul'] . "</a></h3>";
                    echo "<p><small>" . date('d M Y', strtotime($row['tanggal'])) . "</small></p>";
                    echo "<p>" . substr($row['isi'], 0, 150) . "...</p>";
                    echo "<a href=\"?id=" . $row['id'] . "\">Baca selengkapnya »</a>";
                    echo "<hr>";
                }
            } elseif (isset($_GET['category'])) {
                $category = $_GET['category'];
                $sql = "SELECT * FROM artikel WHERE kategori = ? ORDER BY created_at DESC";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('s', $category);
                $stmt->execute();
                $result = $stmt->get_result();

                echo "<h2>Artikel Kategori " . htmlspecialchars($category) . "</h2>";

                while ($row = $result->fetch_assoc()) {
                    echo "<h3><a href=\"?id=" . $row['id'] . "\">" . $row['judul'] . "</a></h3>";
                    echo "<p><small>" . date('d M Y', strtotime($row['tanggal'])) . "</small></p>";
                    echo "<p>" . substr($row['isi'], 0, 150) . "...</p>";
                    echo "<a href=\"?id=" . $row['id'] . "\">Baca selengkapnya »</a>";
                    echo "<hr>";
                }
            } else {
                $sql = "SELECT * FROM artikel ORDER BY created_at DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<h3><a href=\"?id=" . $row['id'] . "\">" . $row['judul'] . "</a></h3>";
                        echo "<p><small><a href=\"?author=" . urlencode($row['nama_penulis']) . "\">" . $row['nama_penulis'] . "</a> | " . date('d M Y', strtotime($row['tanggal'])) . "</small></p>";
                        if (!empty($row['gambar'])) {
                            echo "<img src=\"../admin/news/img/" . $row['gambar'] . "\" alt=\"Gambar Artikel\" class=\"article-image\">";
                        }
                        echo "<p>" . substr($row['isi'], 0, 150) . "...</p>";
                        echo "<a href=\"?id=" . $row['id'] . "\">Baca selengkapnya »</a>";
                        echo "<hr>";
                    }
                } else {
                    echo "<p>Tidak ada artikel yang tersedia.</p>";
                }
            }

            $conn->close();
            ?>
        </div>
    </div>
    <footer>
        <ul id="copyright">
            <li>
                &copy; 2024
                <a href="https://www.instagram.com/ebieth_slank?igsh=MXY1aDdyY2FvNW5jag==">Ebieth Leyn</a>
            </li>
            <li><a href="login.php">PMKRI - Jaktim</a></li>
        </ul>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
</body>

</html>