<?php
session_start();
include 'connect.php';

// Ambil ID buku dari URL
$id_buku = $_GET['id'];

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    $_SESSION['id_buku'] = $id_buku; // Simpan ID buku di session
    header("Location: GEBOOKS-pagelogin.php?redirect=detail-buku");
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

// Ambil informasi stok buku
$sisa_buku = (int) $buku['sisa_buku'];

// Proses peminjaman buku
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pinjam_buku'])) {
    // Cek apakah user sudah login dan stok buku masih ada
    if (isset($_SESSION['user_id']) && $sisa_buku > 0) {
        // Cek apakah pengguna sudah meminjam buku ini sebelumnya
        $check_query = $connBook->prepare("SELECT * FROM peminjaman WHERE id_user = ? AND id_buku = ?");
        $check_query->bind_param("ii", $_SESSION['user_id'], $id_buku);
        $check_query->execute();
        $check_result = $check_query->get_result();

        if ($check_result->num_rows > 0) {
            // Jika sudah meminjam, set session untuk menampilkan modal dan redirect
            $_SESSION['buku_sama'] = true;
            header("Location: GEBOOKS-pagedetailbook.php?id=$id_buku");
            exit();
        }

        // Kurangi sisa buku
        $sisa_buku--;

        // Set tanggal pinjam dan tanggal kembali
        $tanggal_pinjam = date('Y-m-d');
        $tanggal_kembali = date('Y-m-d', strtotime('+7 days'));

        // Update jumlah sisa buku di database
        $update_query = $connBook->prepare("UPDATE buku SET sisa_buku = ? WHERE id = ?");
        $update_query->bind_param("ii", $sisa_buku, $id_buku);
        $update_query->execute();

        // Simpan informasi peminjaman di tabel peminjaman
        $insert_query = $connBook->prepare("INSERT INTO peminjaman (id_user, id_buku, judul_buku, penulis_buku, tanggal_pinjam, tanggal_kembali) VALUES (?, ?, ?, ?, ?, ?)");
        $insert_query->bind_param("iissss", $_SESSION['user_id'], $buku['id'], $buku['judul'], $buku['penulis'], $tanggal_pinjam, $tanggal_kembali);
        $insert_query->execute();

        // Redirect ke halaman detail buku dengan status sukses
        header("Location: GEBOOKS-pagedetailbook.php?id=$id_buku&pinjam=success");
        exit();
    } elseif ($sisa_buku <= 0) {
        // Jika stok habis, set session untuk menampilkan modal dan redirect
        $_SESSION['stok_habis'] = true;
        header("Location: GEBOOKS-pagedetailbook.php?id=$id_buku");
        exit();
    } else {
        // Jika user belum login, redirect ke halaman login
        header("Location: GEBOOKS-pagelogin.php");
        exit();
    }
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
            margin-left: 35px;
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

    <!-- Konten -->
    <div class="container mt-5 d-flex justify-content-center align-items-center flex-column" style="min-height: 80vh;">
        <!-- Gambar Persegi Panjang di Tengah -->
        <img src="images/<?php echo htmlspecialchars($buku['cover']); ?>" alt="Cover Buku" class="content-image mt-4">
        
        <!-- Tombol Pinjam -->
        <form method="POST">
            <button type="submit" name="pinjam_buku" class="button-pinjam">PINJAM</button>
        </form>

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
                    <p><strong>Kategori:</strong> <?php echo htmlspecialchars($buku['kategori']); ?> </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Success Peminjaman -->
    <div class="modal fade" id="pinjamSuccessModal" tabindex="-1" aria-labelledby="pinjamSuccessLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pinjamSuccessLabel"><strong>Peminjaman Berhasil</strong></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Buku berhasil dipinjam! 
                    <br>Jangan lupa mengembalikan tepat waktu ya!
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk Peminjaman Buku Sudah Dipinjam -->
    <div class="modal fade" id="bukuSamaModal" tabindex="-1" aria-labelledby="bukuSamaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bukuSamaLabel"><strong>Peminjaman Buku Gagal</strong></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Kamu sudah meminjam buku ini sebelumnya.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Stok Habis -->
    <div class="modal fade" id="stokHabisModal" tabindex="-1" aria-labelledby="stokHabisLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="stokHabisLabel"><strong>Stok Buku Habis</strong></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Maaf, stok buku saat ini habis. 
                    <br>Silakan cek kembali nanti!
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script untuk membuka modal ketika peminjaman berhasil -->
    <?php
    if (isset($_GET['pinjam']) && $_GET['pinjam'] === 'success') {
        echo "<script>
            const pinjamModal = new bootstrap.Modal(document.getElementById('pinjamSuccessModal'));
            pinjamModal.show();
        </script>";
    }
    ?>

    <!-- Script untuk membuka modal ketika pengguna sudah meminjam buku yang sama -->
    <?php
    if (isset($_SESSION['buku_sama'])) {
        echo "<script>
            const bukuSamaModal = new bootstrap.Modal(document.getElementById('bukuSamaModal'));
            bukuSamaModal.show();
        </script>";
        unset($_SESSION['buku_sama']); // Hapus session setelah ditampilkan
    }
    ?>


    <!-- Script untuk membuka modal ketika stok habis -->
    <?php
    if (isset($_SESSION['stok_habis'])) {
        echo "<script>
            const stokHabisModal = new bootstrap.Modal(document.getElementById('stokHabisModal'));
            stokHabisModal.show();
        </script>";
        unset($_SESSION['stok_habis']); // Hapus session setelah ditampilkan
    }
    ?>


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
