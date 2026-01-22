<?php
include 'config.php';

// Proteksi Keamanan
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'tukang') {
    echo "unauthorized";
    exit;
}

if (isset($_POST['nominal'])) {
    $tukang_id = $_SESSION['id'];
    $nominal   = mysqli_real_escape_string($conn, $_POST['nominal']);
    $metode    = mysqli_real_escape_string($conn, $_POST['metode']);
    $nomor     = mysqli_real_escape_string($conn, $_POST['nomor']);

    // --- VALIDASI SALDO (PENTING AGAR TIDAK BOBOL) ---
    
    // 1. Hitung total pendapatan (90%)
    $q_masuk = mysqli_query($conn, "SELECT SUM(harga * 0.9) as total FROM pesanan WHERE tukang_id = '$tukang_id' AND status = 'selesai'");
    $total_masuk = mysqli_fetch_assoc($q_masuk)['total'] ?? 0;

    // 2. Hitung total yang sudah ditarik sebelumnya
    $q_keluar = mysqli_query($conn, "SELECT SUM(nominal) as total FROM penarikan WHERE tukang_id = '$tukang_id' AND status = 'berhasil'");
    $total_keluar = mysqli_fetch_assoc($q_keluar)['total'] ?? 0;

    $saldo_tersedia = $total_masuk - $total_keluar;

    // Cek apakah saldo cukup
    if ($nominal > $saldo_tersedia) {
        echo "insufficient_balance";
        exit;
    }

    if ($nominal < 10000) {
        echo "min_limit";
        exit;
    }

    // --- EKSEKUSI PENARIKAN ---
    // Simpan riwayat penarikan ke database
    $query = "INSERT INTO penarikan (tukang_id, nominal, metode, nomor_tujuan, status, created_at) 
              VALUES ('$tukang_id', '$nominal', '$metode', '$nomor', 'berhasil', NOW())";

    if (mysqli_query($conn, $query)) {
        echo "success";
    } else {
        echo "error_db";
    }
} else {
    echo "invalid_request";
}
?>