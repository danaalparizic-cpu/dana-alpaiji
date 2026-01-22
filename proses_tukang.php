<?php
include 'config.php';

// Proteksi: Hanya tukang yang bisa akses
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'tukang') {
    echo "unauthorized";
    exit;
}

// --- LOGIKA 1: KIRIM ESTIMASI HARGA (AJAX) ---
if (isset($_GET['aksi']) && $_GET['aksi'] == 'kirim_harga') {
    $id_pesanan = mysqli_real_escape_string($conn, $_POST['id']);
    $harga      = mysqli_real_escape_string($conn, $_POST['harga']);

    // Update harga dan status tetap 'proses' (agar user bisa konfirmasi)
    $query = mysqli_query($conn, "UPDATE pesanan SET 
                                 harga = '$harga' 
                                 WHERE id = '$id_pesanan'");

    if ($query) {
        echo "success";
    } else {
        echo "error";
    }
    exit;
}

// --- LOGIKA 2: SELESAIKAN PEKERJAAN ---
// Catatan: Tombol selesai di sisi tukang hanya muncul jika User sudah SETUJU harga.
if (isset($_GET['aksi']) && $_GET['aksi'] == 'selesai') {
    $id_pesanan = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Ambil data pesanan untuk menghitung komisi admin 10%
    $q_data = mysqli_query($conn, "SELECT harga FROM pesanan WHERE id = '$id_pesanan'");
    $d = mysqli_fetch_assoc($q_data);
    $komisi = $d['harga'] * 0.10;

    // Update status ke 'selesai' dan catat komisi admin
    $update = mysqli_query($conn, "UPDATE pesanan SET 
                                   status = 'selesai', 
                                   komisi_admin = '$komisi' 
                                   WHERE id = '$id_pesanan'");

    if ($update) {
        header("location:dashboard_tukang.php?status=success&pesan=Pekerjaan selesai! Pendapatan telah masuk.&icon=success");
    } else {
        header("location:dashboard_tukang.php?status=error&pesan=Gagal memperbarui status.&icon=error");
    }
    exit;
}
?>