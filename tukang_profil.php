<?php
include 'config.php';

// Proteksi halaman
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'tukang') {
    header("location:index.php?status=error&pesan=Akses Ditolak!&icon=error"); exit;
}

$id_tukang = $_SESSION['id'];

// Logika Update Profil
if (isset($_POST['update_profil'])) {
    $nama   = mysqli_real_escape_string($conn, $_POST['nama']);
    $no_hp  = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $pw     = mysqli_real_escape_string($conn, $_POST['password']);
    
    $sql = "UPDATE users SET nama='$nama', no_hp='$no_hp', password='$pw' WHERE id='$id_tukang'";
    
    if (mysqli_query($conn, $sql)) {
        $_SESSION['nama'] = $nama;
        header("location:tukang_profil.php?status=success&pesan=Data Partner diperbarui&icon=success");
        exit;
    }
}

// Ambil data terbaru
$res = mysqli_query($conn, "SELECT * FROM users WHERE id='$id_tukang'");
$t = mysqli_fetch_assoc($res);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Partner | Si Tukang</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

    <style>
        :root { --primary: #3b82f6; --dark: #0f172a; --bg: #f8fafc; --text-muted: #94a3b8; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: var(--bg); color: var(--dark); overflow-x: hidden; }

        nav { 
            background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(15px);
            padding: 15px 8%; display: flex; justify-content: space-between; 
            align-items: center; position: sticky; top: 0; z-index: 1000;
        }
        .nav-brand { color: white; font-weight: 800; font-size: 20px; letter-spacing: -1px; }
        .nav-links { display: flex; gap: 20px; }
        .nav-links a { color: rgba(255,255,255,0.6); text-decoration: none; font-weight: 600; font-size: 11px; text-transform: uppercase; transition: 0.4s; }
        .nav-links a.active { color: white; border-bottom: 2px solid var(--primary); padding-bottom: 5px; }

        .container { max-width: 550px; margin: 50px auto; padding: 0 20px; }

        .profile-card { 
            background: white; padding: 40px; border-radius: 32px; 
            border: 1px solid #f1f5f9; box-shadow: 0 20px 40px rgba(0,0,0,0.03);
            opacity: 0; transform: translateY(30px);
        }
        
        .profile-header { text-align: center; margin-bottom: 40px; position: relative; }
        .avatar-circle {
            width: 90px; height: 90px; background: var(--dark); border-radius: 28px;
            display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;
            box-shadow: 0 15px 30px rgba(15, 23, 42, 0.15); border: 4px solid white;
        }
        .avatar-circle i { font-size: 35px; color: white; }
        .verify-badge { 
            background: var(--primary); color: white; font-size: 9px; font-weight: 800; 
            padding: 4px 12px; border-radius: 50px; display: inline-block; letter-spacing: 1px;
        }

        .form-group { margin-bottom: 24px; }
        label { display: block; font-weight: 800; margin-bottom: 8px; color: var(--text-muted); font-size: 10px; text-transform: uppercase; letter-spacing: 1px; }
        input { 
            width: 100%; padding: 15px 20px; border-radius: 16px; border: 1px solid #e2e8f0; 
            outline: none; transition: 0.3s; background: #fff; font-size: 14px; font-weight: 600;
        }
        input:focus { border-color: var(--primary); box-shadow: 0 0 0 4px rgba(59,130,246,0.1); transform: translateY(-2px); }
        input:disabled { background: #f8fafc; border-style: dashed; color: #cbd5e1; cursor: not-allowed; }

        .btn-update { 
            width: 100%; background: var(--dark); color: white; border: none; 
            padding: 18px; border-radius: 18px; font-weight: 800; cursor: pointer; 
            transition: 0.4s; margin-top: 10px; font-size: 13px; letter-spacing: 1px;
        }
        .btn-update:hover { background: var(--primary); transform: translateY(-3px); box-shadow: 0 12px 25px rgba(59,130,246,0.3); }

        .footer-info { text-align: center; margin-top: 30px; font-size: 11px; color: #94a3b8; line-height: 1.6; }
    </style>
</head>
<body>

<nav>
    <div class="nav-brand">si tukang.</div>
    <div class="nav-links">
        <a href="dashboard_tukang.php">Pekerjaan</a>
        <a href="tukang_dompet.php">Dompet</a>
        <a href="tukang_profil.php" class="active">Profil</a>
        <a href="logout.php">Keluar</a>
    </div>
</nav>

<div class="container">
    <div class="profile-card">
        <div class="profile-header">
            <div class="avatar-circle">
                <i class="fas fa-toolbox"></i>
            </div>
            <div class="verify-badge"><i class="fas fa-check-circle"></i> PARTNER TERVERIFIKASI</div>
            <h2 style="margin-top: 15px; font-weight: 800; letter-spacing: -0.5px;"><?= $t['nama']; ?></h2>
        </div>

        <form action="" method="POST">
            <div class="form-group">
                <label>Identitas Email (Sistem)</label>
                <input type="email" value="<?= $t['email']; ?>" disabled>
            </div>
            
            <div class="form-group">
                <label>Nama Profesional Partner</label>
                <input type="text" name="nama" value="<?= $t['nama']; ?>" required>
            </div>

            <div class="form-group">
                <label>Nomor WhatsApp Bisnis</label>
                <input type="text" name="no_hp" value="<?= $t['no_hp']; ?>" required>
            </div>

            <div class="form-group">
                <label>Keamanan Password</label>
                <input type="password" name="password" value="<?= $t['password']; ?>" required>
            </div>

            <button type="submit" name="update_profil" class="btn-update">
                SIMPAN PERUBAHAN PROFIL
            </button>
        </form>

        <div class="footer-info">
            ID Partner: #TKNG-0<?= $id_tukang; ?><br>
            <?php 
            // Cek apakah key 'created_at' ada dan tidak kosong
            if(isset($t['created_at']) && !empty($t['created_at']) && $t['created_at'] != '0000-00-00 00:00:00'): ?>
                Bergabung sejak: <?= date('d M Y', strtotime($t['created_at'])); ?>
            <?php else: ?>
                Partner Terverifikasi Si Tukang
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    window.onload = () => {
        gsap.to(".profile-card", { opacity: 1, y: 0, duration: 1, ease: "power4.out" });
    };

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