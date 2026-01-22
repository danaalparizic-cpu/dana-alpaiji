<?php
include 'config.php';

// Pastikan ada data yang dikirim melalui POST
if (!isset($_POST['aksi'])) {
    header("location:index.php");
    exit;
}

$aksi = $_POST['aksi'];

// --- LOGIKA PENDAFTARAN ---
if ($aksi == 'daftar') {
    $nama     = mysqli_real_escape_string($conn, $_POST['nama']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $no_hp    = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role     = mysqli_real_escape_string($conn, $_POST['role']);

    // Cek apakah email sudah terdaftar
    $cek_email = mysqli_query($conn, "SELECT email FROM users WHERE email='$email'");
    
    if (mysqli_num_rows($cek_email) > 0) {
        header("location:index.php?status=error&pesan=Email sudah digunakan!&icon=error");
        exit;
    } else {
        // Simpan ke database (status_verifikasi 0 untuk tukang secara default)
        $query = "INSERT INTO users (nama, email, no_hp, password, role, status_verifikasi) 
                  VALUES ('$nama', '$email', '$no_hp', '$password', '$role', '0')";
        
        if (mysqli_query($conn, $query)) {
            header("location:index.php?status=success&pesan=Daftar Berhasil! Silakan Masuk.&icon=success");
            exit;
        } else {
            header("location:index.php?status=error&pesan=Gagal Mendaftar!&icon=error");
            exit;
        }
    }
}

// --- LOGIKA LOGIN ---
if ($aksi == 'login') {
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $login = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND password='$password'");
    $cek   = mysqli_num_rows($login);

    if ($cek > 0) {
        $data = mysqli_fetch_assoc($login);
        
        // Menyimpan data ke session agar bisa dipanggil di profil
        $_SESSION['id']    = $data['id'];
        $_SESSION['nama']  = $data['nama'];
        $_SESSION['role']  = $data['role'];
        $_SESSION['email'] = $data['email']; // Menambahkan email ke session

        // Redirect sesuai level (Tanpa folder)
        if ($data['role'] == "admin") {
            header("location:dashboard_admin.php");
        } else if ($data['role'] == "tukang") {
            header("location:dashboard_tukang.php");
        } else {
            header("location:dashboard_user.php");
        }
        exit;
    } else {
        header("location:index.php?status=error&pesan=Email atau Password Salah!&icon=error");
        exit;
    }
}
?>