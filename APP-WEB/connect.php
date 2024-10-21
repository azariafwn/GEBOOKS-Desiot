<?php
// $servername = "localhost"; // Ganti dengan nama server
// $username = "root"; // Ganti dengan username database
// $password = ""; // Ganti dengan password database
// $dbname = "gebooks"; // Ganti dengan nama database

// Koneksi ke database pengguna
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$connUser = mysqli_connect("localhost", "root", "", "gebooks");
// Cek koneksi
if (!$connUser) {
    die("Koneksi database user gagal: " . mysqli_connect_error());
}

// Koneksi ke database buku
$connBook = new mysqli("localhost", "root", "", "gebooks");
if (!$connBook) {
    die("Koneksi database buku gagal: " . mysqli_connect_error());
}
?>

