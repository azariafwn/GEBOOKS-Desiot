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

        .form-container {
            margin-top: 30px;
            padding: 45px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-heading {
            font-weight: bold;
            margin-bottom: 30px;
        }

        .form-group label {
            font-weight: bold;
        }

        .input-group .form-control {
            border-right: none;
        }

        .input-group .input-group-text {
            background-color: transparent;
            border-left: none;
            cursor: pointer;
        }

        .submit-btn {
            background-color: rgb(223, 220, 255);
            color: black;
            font-weight: bold;
            padding: 10px;
            border-radius: 40px;
            border: none;
            width: 100%;
        }

        .submit-btn:hover {
            background-color: rgb(150, 141, 243);
            color: white;
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
    <!-- Form Registrasi -->
    <div class="container d-flex justify-content-center">
        <div class="form-container col-lg-8 col-md-10 col-sm-12">
            <h1 class="form-heading text-center">Tambah Data Buku</h1>

            <?php
            // Include the database connection
            include 'connect.php';

            if (isset($_POST["tambah"])) {
                // Ambil data dari form
                $judul = $_POST['judul'];
                $penulis = $_POST['penulis'];
                $sisa_buku = $_POST['sisa_buku'];
                $sinopsis = $_POST['sinopsis'];
                $penerbit = $_POST['penerbit'];
                $isbn = $_POST['isbn'];
                $bahasa = $_POST['bahasa'];
                $berat = $_POST['berat'];
                $halaman = $_POST['halaman'];
                $lebar = $_POST['lebar'];
                $panjang = $_POST['panjang'];
                $kategori = $_POST['kategori'];

                // Menangani upload cover buku
                $cover = $_FILES['cover']['name'];
                $cover_tmp = $_FILES['cover']['tmp_name'];
                $cover_folder = "uploads/" . basename($cover);

                if (isset($_FILES['cover']) && $_FILES['cover']['error'] == 0) {
                    $cover = $_FILES['cover']['name'];
                    $cover_tmp = $_FILES['cover']['tmp_name'];
                    $cover_folder = "images/" . basename($cover);
                
                    if (move_uploaded_file($cover_tmp, $cover_folder)) {
                        // Lanjutkan dengan query ke database
                        $stmt = $connBook->prepare("INSERT INTO buku (judul, penulis, sisa_buku, sinopsis, penerbit, isbn, bahasa, berat, halaman, lebar, panjang, cover, kategori) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                        $stmt->bind_param("ssissisiiiiss", $judul, $penulis, $sisa_buku, $sinopsis, $penerbit, $isbn, $bahasa, $berat, $halaman, $lebar, $panjang, $cover, $kategori);
                
                        if ($stmt->execute()) {
                            echo "<div class='alert alert-success text-center'>Data buku berhasil ditambahkan!</div>";
                        } else {
                            echo "<div class='alert alert-danger text-center'>Terjadi kesalahan, data tidak dapat disimpan.</div>";
                        }
                
                        $stmt->close();
                    } else {
                        echo "<div class='alert alert-danger text-center'>Gagal mengunggah cover buku.</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger text-center'>Tidak ada file cover yang diunggah atau terjadi kesalahan saat unggah.</div>";
                }
                

                // Tutup koneksi
                mysqli_close($connBook);
            }
            ?>



            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group mb-3">
                    <label for="judul">Judul Buku</label>
                    <input type="text" class="form-control" id="judul" name="judul" placeholder="Masukkan judul buku" required>
                </div>

                <div class="form-group mb-3">
                    <label for="penulis">Penulis</label>
                    <input type="text" class="form-control" id="penulis" name="penulis" placeholder="Masukkan nama penulis" required>
                </div>

                <div class="form-group mb-3">
                    <label for="cover">Cover Buku</label>
                    <input type="file" class="form-control" id="cover" name="cover" accept="image/*" required>
                </div>


                <div class="form-group mb-3">
                    <label for="sisa_buku">Sisa Buku</label>
                    <input type="number" class="form-control" id="sisa_buku" name="sisa_buku" placeholder="Masukkan jumlah sisa buku" required min="0">
                </div>

                <div class="form-group mb-3">
                    <label for="sinopsis">Sinopsis</label>
                    <textarea class="form-control" id="sinopsis" name="sinopsis" placeholder="Masukkan sinopsis buku" rows="4" required></textarea>
                </div>

                <div class="form-group mb-3">
                    <label for="penerbit">Penerbit</label>
                    <input type="text" class="form-control" id="penerbit" name="penerbit" placeholder="Masukkan nama penerbit" required>
                </div>

                <div class="form-group mb-3">
                    <label for="penerbit">Kategori</label>
                    <input type="text" class="form-control" id="kategori" name="kategori" placeholder="Masukkan kategori buku" required>
                </div>

                <div class="form-group mb-3">
                    <label for="isbn">ISBN</label>
                    <input type="text" class="form-control" id="isbn" name="isbn" placeholder="Masukkan nomor ISBN" required>
                </div>

                <div class="form-group mb-3">
                    <label for="bahasa">Bahasa</label>
                    <input type="text" class="form-control" id="bahasa" name="bahasa" placeholder="Masukkan bahasa buku" required>
                </div>

                <div class="form-group mb-3">
                    <label for="berat">Berat (gram)</label>
                    <input type="number" class="form-control" id="berat" name="berat" placeholder="Masukkan berat buku dalam gram" required min="0">
                </div>

                <div class="form-group mb-3">
                    <label for="halaman">Halaman</label>
                    <input type="number" class="form-control" id="halaman" name="halaman" placeholder="Masukkan jumlah halaman" required min="1">
                </div>

                <div class="form-group mb-3">
                    <label for="lebar">Lebar (cm)</label>
                    <input type="number" class="form-control" id="lebar" name="lebar" placeholder="Masukkan lebar buku dalam cm" required min="0" step="0.01">
                </div>

                <div class="form-group mb-3">
                    <label for="panjang">Panjang (cm)</label>
                    <input type="number" class="form-control" id="panjang" name="panjang" placeholder="Masukkan panjang buku dalam cm" required min="0" step="0.01">
                </div>

                <div class="submit-btn">
                    <input type="submit" value="Tambah" name="tambah" class="submit-btn">
                </div>

            </form>
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
