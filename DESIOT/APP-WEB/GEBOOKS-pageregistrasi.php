<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GEBOOKS - Registrasi</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: Barlow, sans-serif;
            background-color: #f8f9fa;
        }

        .gebooks-text {
            font-weight: bold;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            font-size: large;
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

        .login-link {
            margin-top: 15px;
            text-align: center;
        }

        .login-link a {
            color: #5a67d8;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-light bg-light py-3 sticky-top shadow">
        <div class="container-fluid">
            <div class="center-logo mx-auto">
                <span class="gebooks-text">Gebooks</span>
            </div>
        </div>
    </nav>

    <!-- Form Registrasi -->
    <div class="container d-flex justify-content-center">
        <div class="form-container col-lg-8 col-md-10 col-sm-12">
            <h1 class="form-heading text-center">Registrasi</h1>

            <?php
            // Include the database connection
            include 'connect.php';

            if (isset($_POST["daftar"])) {
                // Ambil data dari form
                $name = $_POST['name'];
                $email = $_POST['email'];
                $NIK = $_POST['NIK'];
                $phone = $_POST['phone'];
                $dob = $_POST['dob'];
                $address = $_POST['address'];
                $password = $_POST['password'];
                $confirm_password = $_POST['confirm_password'];

                // Cek apakah password dan konfirmasi password cocok
                if ($password === $confirm_password) {

                    // Query dengan prepared statement untuk menghindari SQL Injection
                    $stmt = $connUser->prepare("INSERT INTO user (name, email, NIK, phone, dob, address, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssiisss", $name, $email, $NIK, $phone, $dob, $address, $password);

                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success text-center'>Registrasi berhasil!</div>";
                    } else {
                        echo "<div class='alert alert-danger text-center'>Terjadi kesalahan, data tidak dapat disimpan.</div>";
                    }
                    $stmt->close();
                } else {
                    echo "<div class='alert alert-danger text-center'>Password dan konfirmasi password tidak cocok!</div>";
                }

                // Tutup koneksi
                mysqli_close($connUser);
            }
            ?>

            <form action="" method="POST">
                <div class="form-group mb-3">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan nama lengkap" required>
                </div>

                <div class="form-group mb-3">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email" required>
                </div>

                <div class="form-group mb-3">
                    <label for="NIK">NIK</label>
                    <input type="text" class="form-control" id="NIK" name="NIK" placeholder="Masukkan NIK" required>
                </div>

                <div class="form-group mb-3">
                    <label for="phone">Telepon</label>
                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Masukkan nomor telepon" required>
                </div>

                <div class="form-group mb-3">
                    <label for="dob">Tanggal Lahir</label>
                    <input type="date" class="form-control" id="dob" name="dob" required>
                </div>

                <div class="form-group mb-3">
                    <label for="address">Alamat</label>
                    <textarea class="form-control" id="address" name="address" placeholder="Masukkan alamat" rows="3" required></textarea>
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

                <div class="form-group mb-3">
                    <label for="confirm_password">Konfirmasi Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Konfirmasi password" required>
                        <span class="input-group-text" id="toggleConfirmPassword">
                            <i class="bi bi-eye-slash" id="confirm-password-icon"></i>
                        </span>
                    </div>
                </div>

                <div class="submit-btn">
                    <input type="submit" value="Daftar" name="daftar" class="submit-btn">
                </div>

                <div class="login-link">
                    <p>Sudah punya akun? <a href="GEBOOKS-pagelogin.php">Login di sini</a></p>
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

        // Toggle password visibility for Confirm Password field
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        const confirmPasswordField = document.getElementById('confirm_password');
        const confirmPasswordIcon = document.getElementById('confirm-password-icon');

        toggleConfirmPassword.addEventListener('click', function () {
            // Toggle the type attribute
            const type = confirmPasswordField.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPasswordField.setAttribute('type', type);

            // Toggle the eye icon
            confirmPasswordIcon.classList.toggle('bi-eye');
            confirmPasswordIcon.classList.toggle('bi-eye-slash');
        });
    </script>
</body>

</html>
