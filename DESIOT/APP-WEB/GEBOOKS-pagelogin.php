<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GEBOOKS - Login</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: Barlow, sans-serif;
            background-color: #f8f9fa;
        }

        .form-container {
            margin-top: 50px;
            padding: 40px;
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

        .register-link {
            margin-top: 15px;
            text-align: center;
        }

        .register-link a {
            color: #5a67d8;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-light bg-light py-3 sticky-top shadow">
        <div class="container-fluid">
            <div class="center-logo mx-auto">
                <img src="images/logo.png" alt="Gebooks" width="40" height="40">
                <span class="gebooks-text">Gebooks</span>
            </div>
        </div>
    </nav>

    <!-- Form Login -->
    <div class="container d-flex justify-content-center">
        <div class="form-container col-lg-8 col-md-10 col-sm-12">
            <h1 class="form-heading text-center">Login</h1>

            <?php
            session_start();
            include 'connect.php';

            if (isset($_POST["login"])) {
                // Ambil data dari form
                $email = $_POST['email'];
                $password = $_POST['password'];

                // Query untuk mengambil data user berdasarkan email
                $stmt = $connUser->prepare("SELECT * FROM registration WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();

                // Cek apakah email ditemukan
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();

                    // Verifikasi password
                    if ($password === $row['password']) {
                        // Jika login berhasil, simpan data user ke session
                        $_SESSION['user_id'] = $row['id'];
                        $_SESSION['user_name'] = $row['name'];

                        // Cek apakah ada ID buku di session
                        if (isset($_SESSION['id_buku']) && isset($_GET['redirect']) && $_GET['redirect'] === 'detail-buku') {
                            $id_buku = $_SESSION['id_buku'];

                            // Redirect ke halaman detail buku dengan ID dan tambahan parameter untuk pop-up
                            header("Location: GEBOOKS-pagedetailbook.php?id=$id_buku&status=pinjam_berhasil");
                        } else {
                            // Jika tidak ada ID buku, redirect ke halaman utama
                            header("Location: GEBOOKS-homepage.php");
                        }
                        exit(); // Penting untuk menghentikan eksekusi setelah redirect
                    } else {
                        echo "<div class='alert alert-danger text-center'>Password salah!</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger text-center'>Email tidak ditemukan!</div>";
                }

                // Tutup koneksi
                $stmt->close();
                mysqli_close($connUser);
            }
            ?>

            <form action="" method="POST">
                <div class="form-group mb-3">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email" required>
                </div>

                <div class="form-group mb-3">
                    <label for="password">Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
                        <span class="input-group-text" id="togglePassword">
                            <i class="bi bi-eye-slash" id="password-icon"></i>
                        </span>
                    </div>
                </div>

                <div class="submit-btn">
                    <input type="submit" value="Login" name="login" class="submit-btn">
                </div>

                <div class="register-link">
                    <p>Belum punya akun? <a href="GEBOOKS-pageregistrasi.php">Daftar di sini</a></p>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Toggle password visibility for Password field
        const togglePassword = document.getElementById('togglePassword');
        const passwordField = document.getElementById('password');
        const passwordIcon = document.getElementById('password-icon');

        togglePassword.addEventListener('click', function () {
            // Toggle the type attribute
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);

            // Toggle the eye icon
            passwordIcon.classList.toggle('bi-eye');
            passwordIcon.classList.toggle('bi-eye-slash');
        });
    </script>
</body>

</html>
