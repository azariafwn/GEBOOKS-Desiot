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
            margin-left: 35px;
        }

        .content-image {
            width: 40%;
            max-width: 300px;
            height: auto;
        }

        .book-cover {
            width: 100%;
            height: 90%;
            display: block;
            margin: 0 auto;
            position: relative;
        }

        .book-title {
            text-align: center;
            margin-top: 10px; 
            margin-bottom: 20px;
            font-weight: bold;
            font-size: 1.5rem;
            color: #333;
        }

        /* Media query untuk menyesuaikan ukuran font berdasarkan panjang judul */
        @media (max-width: 576px) {
            .book-title {
                font-size: 1.1rem; /* Ukuran font untuk layar kecil */
            }
        }

        .btn-addBook {
            background-color: white;
            color: black;
            font-family: Barlow, sans-serif;
            font-weight: bold;
            font-size: 1.2rem;
            margin-top: 16px;
            border-radius: 40px;
            border-color: black;
            padding-top: 6px;
            padding-bottom: 6px;
            padding-left: 50px;
            padding-right: 50px;
        }

        .btn-addBook:hover {
            background-color: rgb(223, 220, 255);
            color: white;
            border-color: rgb(223, 220, 255);
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
                <!-- Ikon Buku untuk Akses ke Halaman Library -->
                <a href="GEBOOKS-library.php">
                    <i class="bi bi-book book-icon fs-4 me-2"></i>
                </a>
                <!-- Icon Profil -->
                <a href="GEBOOKS-pageprofile.php">
                    <img src="images/profil.jpeg" alt="Profile" class="profile-icon">
                </a>
            </div>

        </div>
    </nav>

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
