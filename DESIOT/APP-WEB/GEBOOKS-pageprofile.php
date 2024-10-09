<?php
// Include the database connection
include 'connect.php';

// Mulai session untuk mendapatkan data user yang sedang login
session_start();

// Cek apakah user sudah login, jika belum, redirect ke halaman login
if (!isset($_SESSION['user_id'])) {
    header("Location: GEBOOKS-pagelogin.php");
    exit();
}

// Ambil ID user dari session
$user_id = $_SESSION['user_id'];

// Query untuk mengambil data user berdasarkan user_id
$sql = "SELECT name, email, phone, nik, dob, address FROM registration WHERE id = ?";
$stmt = $connUser->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Jika user ditemukan, ambil datanya
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User tidak ditemukan";
    exit();
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
            width: 35px;
            height: 35px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
        }

        .center-logo {
            display: flex;
            align-items: center;
        }

        .gebooks-text {
            font-weight: bold;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            font-size: larger;
            margin-left: 10px;
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

        /* Profile Styles */
        .profile-container {
            max-width: 85%;
            padding: 20px;
            margin-top: 2rem;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #f9f9f9;
        }

        .profile-container img {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            object-fit: cover;
        }

        .profile-info {
            display: flex;
            align-items: center;
        }

        .profile-details {
            margin-left: 30px;
        }

        .profile-details h1 {
            margin-bottom: 0;
        }

        .profile-details p {
            margin-bottom: 0.5rem;
            color: #6c757d;
        }

        .separator {
            border-top: 2px solid #ddd;
            margin: 20px 0;
        }

        .list-data .form-label {
            font-weight: bold;
            font-size: 1.3rem;
            padding-left: 5px; /* padding kiri */
        }

        .list-data p {
            font-size: 1.3rem;
            padding-right: 5px; /* padding kanan */
        }

        /* Custom Logout Button Styles */
        .logout-button {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #ff4757;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
        }

        .logout-button:hover {
            background-color: #e84118;
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
                    <img src="images/logo.png" alt="Gebooks" width="40" height="40">
                    <span class="gebooks-text">Gebooks</span>
                </a>
            </div>
            <!-- Kanan: Icon Profil -->
            <div class="d-flex align-items-center">
                <img src="images/profil.jpeg" alt="Profile" class="profile-icon">
            </div>
        </div>
    </nav>

    <!-- Konten Profil -->
    <div class="container profile-container">
        <!-- Profile Picture dan Info Utama -->
        <div class="profile-info">
            <img src="images/profil.jpeg" alt="Profile Picture">
            <div class="profile-details">
                <h1><strong><?php echo htmlspecialchars($user['name']); ?></strong></h1>
                <p><?php echo htmlspecialchars($user['email']); ?></p>
            </div>
        </div>

        <!-- Garis Pemisah -->
        <div class="separator"></div>

        <!-- List Data User -->
        <div class="row list-data">
            <div class="col-md-6 col-12 mb-3 d-flex justify-content-between">
                <label class="form-label">Nama:</label>
                <p><?php echo htmlspecialchars($user['name']); ?></p>
            </div>
            <div class="col-md-6 col-12 mb-3 d-flex justify-content-between">
                <label class="form-label">Email:</label>
                <p><?php echo htmlspecialchars($user['email']); ?></p>
            </div>
            <div class="col-md-6 col-12 mb-3 d-flex justify-content-between">
                <label class="form-label">NIK:</label>
                <p><?php echo htmlspecialchars($user['nik']); ?></p>
            </div>
            <div class="col-md-6 col-12 mb-3 d-flex justify-content-between">
                <label class="form-label">Nomor Telepon:</label>
                <p><?php echo htmlspecialchars($user['phone']); ?></p>
            </div>
            <div class="col-md-6 col-12 mb-3 d-flex justify-content-between">
                <label class="form-label">Tanggal Lahir:</label>
                <p><?php echo htmlspecialchars($user['dob']); ?></p>
            </div>
            <div class="col-md-6 col-12 mb-3 d-flex justify-content-between">
                <label class="form-label">Alamat:</label>
                <p><?php echo htmlspecialchars($user['address']); ?></p>
            </div>

            <!-- Tombol Logout -->
            <a href="GEBOOKS-pagelogin.php" class="logout-button">Logout</a>
        </div>


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