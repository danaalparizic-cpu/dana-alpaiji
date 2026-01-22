<?php
include 'config.php';

// Proteksi Sesi
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    echo "unauthorized";
    exit;
}

if (isset($_POST['buat_pesanan'])) {
    $user_id      = $_SESSION['id'];
    $tukang_id    = mysqli_real_escape_string($conn, $_POST['tukang_id']);
    $jasa         = mysqli_real_escape_string($conn, $_POST['jasa']);
    $metode_bayar = mysqli_real_escape_string($conn, $_POST['metode_bayar']);
    
    $harga_awal   = 0;
    $status_awal  = 'proses';
    $komisi_awal  = 0;

    // Manajemen Foto
    if (!isset($_FILES['foto']) || $_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
        echo "error_no_file";
        exit;
    }

    $foto_name = $_FILES['foto']['name'];
    $tmp       = $_FILES['foto']['tmp_name'];
    $ekstensi  = strtolower(pathinfo($foto_name, PATHINFO_EXTENSION));
    
    // Penamaan file unik
    $foto_baru = "kendala_" . time() . "_" . bin2hex(random_bytes(4)) . "." . $ekstensi;
    $folder    = "assets/img/";
    $path      = $folder . $foto_baru;

    // Buat folder jika belum ada
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    if (move_uploaded_file($tmp, $path)) {
        // Query yang sudah disinkronkan dengan database
        $query = "INSERT INTO pesanan (user_id, tukang_id, jasa, foto_kendala, harga, metode_bayar, status, komisi_admin, created_at) 
                  VALUES ('$user_id', '$tukang_id', '$jasa', '$foto_baru', '$harga_awal', '$metode_bayar', '$status_awal', '$komisi_awal', NOW())";
        
        if (mysqli_query($conn, $query)) {
            echo "success";
        } else {
            unlink($path); // Hapus foto jika gagal masuk DB
            echo "error_db";
        }
    } else {
        echo "error_upload";
    }
} else {
    echo "invalid_request";
}
?>