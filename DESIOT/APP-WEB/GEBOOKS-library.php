<?php
session_start(); // Memulai sesi

// Mengambil data peminjaman buku
$bukuDipinjam = [];
if (isset($_SESSION['peminjaman']) && !empty($_SESSION['peminjaman'])) {
    $bukuDipinjam = $_SESSION['peminjaman'];
} else {
    $bukuDipinjam = null; // Untuk menandakan tidak ada buku yang dipinjam
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GEBOOKS</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: Barlow, sans-serif;
        }

        .search-icon {
            font-size: 1.5rem;
            cursor: pointer;
            color: black;
            margin-left: 10px;
        }

        .profile-icon {
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
            margin-left: 10px;
        }

        .gebooks-text {
            font-weight: bold;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            font-size: large;
            margin-left: 20px;
        }

        .search-form {
            display: none;
            position: absolute;
            top: 65px;
            left: 10px;
            width: 300px;
            z-index: 100;
        }

        .search-input {
            width: 100%;
            border-radius: 20px;
        }

        .show-search {
            display: block !important;
        }

        .navbar {
            position: sticky;
        }

        /* Footer Styles */
        footer {
            background-color: #f8f9fa;
            padding: 40px 0;
            margin-top: 50px;
            text-align: center;
            border-top: 1px solid #e7e7e7;
        }

        .footer-logo {
            width: 40px;
            height: 40px;
        }

        .footer-text {
            margin-top: 10px;
            font-size: 18px;
            font-weight: bold;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
        }

        .footer-links {
            margin-top: 20px;
        }

        .footer-links a {
            color: #5a67d8;
            text-decoration: none;
            margin: 0 15px;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }

        .footer-contact {
            margin-top: 20px;
            font-size: 14px;
            color: #6c757d;
        }

        .footer-copyright {
            margin-top: 10px;
            font-size: 14px;
            color: #6c757d;
        }

        /* Custom styles for book card */
        .card {
            display: flex;
            flex-direction: row;
            align-items: center;
            padding: 15px;
            border: 1px solid #e7e7e7;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .card img {
            width: 80px; /* Ukuran gambar lebih kecil */
            height: auto;
            margin-right: 15px; /* Jarak antara gambar dan teks */
        }

        .card-body {
            flex: 1; /* Memungkinkan teks mengisi ruang yang tersedia */
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-light bg-light py-3 sticky-top shadow">
        <div class="container-fluid navbar-content">
            <!-- Kiri: Icon Search -->
            <div>
                <i class="bi bi-search search-icon" id="searchIcon"></i>
                <!-- Form Search -->
                <form class="search-form mt-2" id="searchForm">
                    <input type="text" class="form-control search-input" placeholder="Cari buku...">
                </form>
            </div>
            <!-- Tengah: Logo dan Nama Aplikasi -->
            <div class="center-logo mx-auto">
                <a href="GEBOOKS-homepage.php" class="text-decoration-none d-flex align-items-center justify-content-center">
                    <span class="gebooks-text">Gebooks</span>
                </a>
            </div>
            <!-- Kanan: Icon Buku dan Profil -->
            <div class="d-flex align-items-center">
                <!-- Icon Profil -->
                <a href="GEBOOKS-pageprofile.php">
                    <img src="images/profil.jpeg" alt="Profile" class="profile-icon">
                </a>
            </div>

        </div>
    </nav>

    <!-- Konten -->
    <div class="container mt-5">
        <h1>Daftar Buku yang Dipinjam</h1>
        <div class="row">
            <?php if ($bukuDipinjam): ?>
                <?php foreach ($bukuDipinjam as $buku): ?>
                    <div class="col-12">
                        <div class="card">
                            <img src="images/<?php echo htmlspecialchars($buku['cover']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($buku['judul']); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($buku['judul']); ?></h5>
                                <p class="card-text">Penulis: <?php echo htmlspecialchars($buku['penulis']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Anda belum meminjam buku apapun.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap 5 JS dan Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js"></script>

    <script>
        // Menangani ikon pencarian
        document.getElementById('searchIcon').addEventListener('click', function () {
            const searchForm = document.getElementById('searchForm');
            searchForm.classList.toggle('show-search');
        });

        // Menutup form pencarian ketika mengklik di luar
        document.addEventListener('click', function (event) {
            const searchForm = document.getElementById('searchForm');
            const searchIcon = document.getElementById('searchIcon');
            if (!searchForm.contains(event.target) && !searchIcon.contains(event.target)) {
                searchForm.classList.remove('show-search');
            }
        });

    </script>

    <!-- Footer -->
    <footer>
        <img src="images/logo.png" alt="Logo" class="footer-logo">
        <div class="footer-text">Gebooks</div>
        <div class="footer-links">
            <a href="#">Tentang Kami</a>
            <a href="#">Kontak</a>
            <a href="#">Kebijakan Privasi</a>
        </div>
        <div class="footer-contact">Email: info@gebooks.com | Telepon: 123-456-7890</div>
        <div class="footer-copyright">© 2024 Gebooks. All rights reserved.</div>
    </footer>
</body>

</html>
