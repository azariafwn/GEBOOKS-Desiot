<?php
session_start();
include 'connect.php';

// Ambil ID buku dari URL
$id_buku = $_GET['id'];

// Jika user belum login, simpan ID buku di session dan redirect ke halaman login
if (!isset($_SESSION['user_id'])) {
    $_SESSION['id_buku'] = $id_buku; // Simpan ID buku di session
    header("Location: GEBOOKS-login.php?redirect=detail-buku");
    exit();
}

// Query untuk mengambil detail buku dari database
$query = $connBook->prepare("SELECT * FROM buku WHERE id = ?");
$query->bind_param("i", $id_buku);
$query->execute();
$result = $query->get_result();
$buku = $result->fetch_assoc();

if (!$buku) {
    echo "Buku tidak ditemukan!";
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

        .content-image {
            width: 40%;
            max-width: 300px;
            height: auto;
        }

        .text-justify {
            text-align: justify;
        }

        .detail-buku p {
            margin-bottom: 0rem;
        }

        .button-pinjam {
            background-color: white;
            color: black;
            font-family: Barlow, sans-serif;
            font-weight: bold;
            margin-top: 16px;
            border-radius: 40px;
            border-color: black;
            padding-top: 6px;
            padding-bottom: 6px;
            padding-left: 50px;
            padding-right: 50px;
        }

        .button-pinjam:hover {
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
                    <img src="images/logo.png" alt="Gebooks" width="40" height="40">
                    <span class="gebooks-text">Gebooks</span>
                </a>
            </div>
            <!-- Kanan: Icon Profil -->
            <div class="d-flex align-items-center">
                <a href="GEBOOKS-pageprofile.php">
                    <img src="images/profil.jpeg" alt="Profile" class="profile-icon">
                </a>
            </div>
        </div>
    </nav>

    <!-- Konten -->
    <div class="container mt-5 d-flex justify-content-center align-items-center flex-column" style="min-height: 80vh;">
        <!-- Gambar Persegi Panjang di Tengah -->
        <img src="images/cover-themirrorcrack.jpeg" alt="Content Image" class="content-image mt-4">
        <button class="button-pinjam" id="pinjamButton">PINJAM</button>

        <!-- Informasi Buku di bawah tombol -->
        <div class="text-start mt-4 px-4" style="width: 100%;">
            <h6><strong><?php echo htmlspecialchars($buku['penulis']); ?></strong></h6>
            <h1><strong><?php echo htmlspecialchars($buku['judul']); ?></strong></h1>
            <h6>Sisa Buku : <?php echo htmlspecialchars($buku['sisa_buku']); ?> eksemplar</h6>
            <p>Tersedia kembali pada: dd-mm-yyyy</p>
            <hr class="w-85 mx-auto" style="border-top: 3px solid #000;">
            <p class="text-justify" style="text-indent: 2em;">
                <?php echo htmlspecialchars($buku['sinopsis']); ?>
            </p>

            <h4><strong>Detail Buku</strong></h4>
        </div>

        <!-- Detail Buku -->
        <div class="container detail-buku">
            <div class="row">
                <div class="col-6 col-md-12 px-4">
                    <p><strong>Penerbit:</strong> <?php echo htmlspecialchars($buku['penerbit']); ?></p>
                    <p><strong>ISBN:</strong> <?php echo htmlspecialchars($buku['isbn']); ?></p>
                    <p><strong>Bahasa:</strong> <?php echo htmlspecialchars($buku['bahasa']); ?></p>
                    <p><strong>Berat:</strong> <?php echo htmlspecialchars($buku['berat']); ?> gr</p>
                </div>
                <div class="col-6 col-md-12 px-4">
                    <p><strong>Halaman:</strong> <?php echo htmlspecialchars($buku['halaman']); ?> </p>
                    <p><strong>Lebar:</strong> <?php echo htmlspecialchars($buku['lebar']); ?> cm</p>
                    <p><strong>Panjang:</strong> <?php echo htmlspecialchars($buku['panjang']); ?> cm</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Login Berhasil -->
    <div class="modal fade" id="loginSuccessModal" tabindex="-1" aria-labelledby="loginSuccessModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginSuccessModalLabel">Login Berhasil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Anda telah berhasil login! Selamat datang kembali.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Peminjaman Berhasil -->
    <div class="modal fade" id="pinjamSuccessModal" tabindex="-1" aria-labelledby="pinjamSuccessModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pinjamSuccessModalLabel">Peminjaman Berhasil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Buku berhasil dipinjam!
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS dan Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js"></script>

    <script>
        // Cek URL untuk parameter pinjam
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('pinjam') && urlParams.get('pinjam') === 'success') {
            const pinjamModal = new bootstrap.Modal(document.getElementById('pinjamSuccessModal'));
            pinjamModal.show();
        }

        // Menangani klik tombol Pinjam
        document.getElementById('pinjamButton').addEventListener('click', function () {
            const isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>; // Menggunakan PHP untuk mengecek login
            if (isLoggedIn) {
                const pinjamModal = new bootstrap.Modal(document.getElementById('pinjamSuccessModal'));
                pinjamModal.show();
                // Tambahkan logika peminjaman di sini
            } else {
                alert('Silakan login terlebih dahulu untuk meminjam buku.');
                window.location.href = 'GEBOOKS-pagelogin.php?redirect=detail-buku';
            }
        });

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
