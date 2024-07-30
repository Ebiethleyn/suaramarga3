<!DOCTYPE html>
<html>

<head>
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="dashboard.css">
    <style>
    body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        margin: 0;
    }

    .header,
    .footer {
        background-color: #343a40;
        color: white;
        padding: 10px 0;
        text-align: center;
    }

    .content {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }

    .buttons {
        display: grid;
        gap: 10px;
    }

    .button {
        display: block;
        padding: 10px;
        background-color: #007bff;
        color: white;
        text-align: center;
        border-radius: 5px;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }

    .button:hover {
        background-color: #0056b3;
    }

    @media (min-width: 768px) {
        .buttons {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (min-width: 992px) {
        .buttons {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    .footer a {
        color: #007bff;
        text-decoration: none;
        margin: 0 5px;
    }

    .footer a:hover {
        text-decoration: underline;
    }
    </style>
</head>

<body>
    <div class="header">
        <div class="container">
            <h1>SuaraMarga3.com</h1>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <h2 class="text-center mb-4">Dashboard Admin</h2>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
</body>

</html>