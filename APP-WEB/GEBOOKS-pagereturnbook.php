<?php
session_start(); // Memulai sesi
include 'connect.php';

// Fungsi untuk mengembalikan buku
function kembalikanBuku($bukuId, $userId) {
    global $connBook; // Pastikan koneksi database diakses

    // Hapus peminjaman dari tabel peminjaman
    $queryDelete = $connBook->prepare("DELETE FROM peminjaman WHERE id_buku = ? AND id_user = ?");
    $queryDelete->bind_param("ii", $bukuId, $userId);
    $deleteSuccess = $queryDelete->execute();

    // Tambah sisa buku di tabel buku
    if ($deleteSuccess) {
        $queryUpdate = $connBook->prepare("UPDATE buku SET sisa_buku = sisa_buku + 1 WHERE id = ?");
        $queryUpdate->bind_param("i", $bukuId);
        return $queryUpdate->execute();
    }
    
    return false; // Jika ada kesalahan
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bukuId = $_POST['buku_id'];
    $userId = $_SESSION['user_id'];

    // Memanggil fungsi untuk mengembalikan buku
    if (kembalikanBuku($bukuId, $userId)) {
        header("Location: GEBOOKS-library.php?return=success");
        exit();
    } else {
        echo "Pengembalian gagal. Silakan coba lagi.";
    }
}
?>
