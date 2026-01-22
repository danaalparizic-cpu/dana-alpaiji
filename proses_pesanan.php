<?php
include 'config.php';

// Pastikan respon hanya berupa teks murni
ob_clean(); 

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    echo "unauthorized";
    exit;
}

if (isset($_POST['buat_pesanan'])) {
    $user_id      = $_SESSION['id'];
    $tukang_id    = mysqli_real_escape_string($conn, $_POST['tukang_id']);
    $jasa         = mysqli_real_escape_string($conn, $_POST['jasa']);
    $metode_bayar = mysqli_real_escape_string($conn, $_POST['metode_bayar']);
    
    // Validasi file
    if (!isset($_FILES['foto']) || $_FILES['foto']['error'] !== 0) {
        echo "error_no_file";
        exit;
    }

    $foto_name = $_FILES['foto']['name'];
    $tmp       = $_FILES['foto']['tmp_name'];
    $ekstensi  = strtolower(pathinfo($foto_name, PATHINFO_EXTENSION));
    $foto_baru = "kendala_" . time() . "_" . uniqid() . "." . $ekstensi;
    $folder    = "assets/img/";
    
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    if (move_uploaded_file($tmp, $folder . $foto_baru)) {
        // Query disederhanakan agar tidak bentrok dengan constraint database
        $sql = "INSERT INTO pesanan (user_id, tukang_id, jasa, foto_kendala, harga, metode_bayar, status, komisi_admin) 
                VALUES ('$user_id', '$tukang_id', '$jasa', '$foto_baru', 0, '$metode_bayar', 'proses', 0)";
        
        if (mysqli_query($conn, $sql)) {
            echo "success";
        } else {
            echo "error_db: " . mysqli_error($conn);
        }
    } else {
        echo "error_upload";
    }
} else {
    echo "invalid_request";
}
?>