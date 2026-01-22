<?php
include 'config.php';

// Proteksi halaman
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("location:index.php?status=error&pesan=Akses Ditolak!&icon=error"); exit;
}

// Ambil ID dari session
$id_admin = $_SESSION['id'];

// Logika Update Password
if (isset($_POST['update_password'])) {
    $pw_baru = mysqli_real_escape_string($conn, $_POST['password_baru']);
    
    $query = mysqli_query($conn, "UPDATE users SET password='$pw_baru' WHERE id='$id_admin'");
    if ($query) {
        header("location:admin_profil.php?status=success&pesan=Password Berhasil Diperbarui&icon=success");
        exit;
    }
}

// Ambil data terbaru dari database untuk menghindari error Undefined Index
$admin_data = mysqli_query($conn, "SELECT email FROM users WHERE id='$id_admin'");
$row = mysqli_fetch_assoc($admin_data);
$email_display = $row['email'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Saya | Si Tukang</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary: #0056FF; --bg: #F4F7FE; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-size: 13px; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); display: flex; }
        .main-content { flex: 1; margin-left: 220px; padding: 30px; }
        .card { background: white; padding: 30px; border-radius: 15px; max-width: 450px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); }
        label { font-weight: 600; color: #4A5568; display: block; margin-bottom: 5px; }
        input { padding: 12px; border: 1px solid #E2E8F0; border-radius: 8px; width: 100%; margin-bottom: 20px; outline: none; transition: 0.3s; }
        input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(0,86,255,0.1); }
        input:disabled { background: #F8FAFC; cursor: not-allowed; color: #A0AEC0; }
        .btn-primary { background: var(--primary); color: white; border: none; padding: 14px; width: 100%; border-radius: 8px; cursor: pointer; font-weight: 700; transition: 0.3s; }
        .btn-primary:hover { opacity: 0.9; transform: translateY(-2px); }
    </style>
</head>
<body>

<?php include 'sidebar_admin.php'; ?>

<div class="main-content">
    <div class="header animate__animated animate__fadeInDown" style="margin-bottom: 25px;">
        <h2 style="font-size: 18px; font-weight: 700;">Profil Saya</h2>
    </div>

    <div class="card animate__animated animate__fadeInUp">
        <form action="" method="POST">
            <label>Email Admin</label>
            <input type="text" value="<?= $email_display; ?>" disabled>
            
            <label>Ganti Password Baru</label>
            <input type="password" name="password_baru" placeholder="Masukkan password baru..." required>
            
            <button type="submit" name="update_password" class="btn-primary">Simpan Perubahan</button>
        </form>
    </div>
</div>

<script>
    <?php if(isset($_GET['status'])): ?>
    Swal.fire({ 
        icon: '<?= $_GET['icon'] ?>', 
        title: '<?= $_GET['pesan'] ?>', 
        showConfirmButton: false, 
        timer: 1500, 
        toast: true, 
        position: 'top-end' 
    });
    <?php endif; ?>
</script>
</body>
</html>