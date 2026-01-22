<?php
include 'config.php';

// Proteksi halaman
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("location:index.php?status=error&pesan=Akses Ditolak!&icon=error"); exit;
}

$id_user = $_SESSION['id'];

// Logika Update Profil
if (isset($_POST['update_profil'])) {
    $nama   = mysqli_real_escape_string($conn, $_POST['nama']);
    $no_hp  = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $pw     = mysqli_real_escape_string($conn, $_POST['password']);
    
    $sql = "UPDATE users SET nama='$nama', no_hp='$no_hp', password='$pw' WHERE id='$id_user'";
    
    if (mysqli_query($conn, $sql)) {
        $_SESSION['nama'] = $nama;
        header("location:user_profil.php?status=success&pesan=Profil berhasil diperbarui&icon=success");
        exit;
    }
}

$res = mysqli_query($conn, "SELECT * FROM users WHERE id='$id_user'");
$u = mysqli_fetch_assoc($res);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya | Si Tukang</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    
    <style>
        :root { --primary: #3b82f6; --dark: #0f172a; --bg: #f8fafc; --text-muted: #64748b; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: var(--bg); color: var(--dark); overflow-x: hidden; }

        /* Navbar Glassmorphism Sinkron */
        nav { 
            background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(15px);
            padding: 15px 8%; display: flex; justify-content: space-between; 
            align-items: center; position: sticky; top: 0; z-index: 1000;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .nav-brand { color: white; font-weight: 800; font-size: 20px; letter-spacing: -1px; display: flex; align-items: center; gap: 8px; }
        .nav-brand i { color: var(--primary); }
        .nav-links { display: flex; gap: 20px; }
        .nav-links a { color: rgba(255,255,255,0.6); text-decoration: none; font-weight: 600; font-size: 11px; text-transform: uppercase; transition: 0.4s; }
        .nav-links a.active { color: white; border-bottom: 2px solid var(--primary); padding-bottom: 5px; }

        .container { max-width: 550px; margin: 50px auto; padding: 0 20px; }

        /* Card Profile Premium */
        .profile-card { 
            background: white; padding: 40px; border-radius: 30px; 
            border: 1px solid #f1f5f9; box-shadow: 0 20px 40px rgba(0,0,0,0.03);
            opacity: 0; transform: translateY(30px);
        }
        
        .profile-header { text-align: center; margin-bottom: 35px; }
        .avatar-wrapper {
            width: 100px; height: 100px; background: var(--dark); border-radius: 30px;
            display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;
            box-shadow: 0 10px 20px rgba(15, 23, 42, 0.2);
        }
        .avatar-wrapper i { font-size: 40px; color: white; }
        .profile-header h2 { font-size: 22px; font-weight: 800; letter-spacing: -0.5px; }
        .profile-header p { color: var(--text-muted); font-size: 13px; margin-top: 5px; }

        /* Form Styling Sinkron Dashboard */
        .form-group { margin-bottom: 22px; }
        label { display: block; font-weight: 800; margin-bottom: 8px; color: #94a3b8; font-size: 10px; text-transform: uppercase; letter-spacing: 1px; }
        input { 
            width: 100%; padding: 14px 18px; border-radius: 15px; border: 1px solid #e2e8f0; 
            outline: none; transition: 0.3s; background: #fff; font-size: 14px; font-weight: 600;
        }
        input:focus { border-color: var(--primary); box-shadow: 0 0 0 4px rgba(59,130,246,0.1); transform: scale(1.01); }
        input:disabled { background: #f8fafc; color: #cbd5e1; cursor: not-allowed; border-style: dashed; }

        .btn-save { 
            width: 100%; background: var(--dark); color: white; border: none; 
            padding: 16px; border-radius: 18px; font-weight: 800; cursor: pointer; 
            transition: 0.4s; margin-top: 10px; font-size: 14px;
            box-shadow: 0 10px 20px rgba(15, 23, 42, 0.1);
        }
        .btn-save:hover { background: var(--primary); transform: translateY(-3px); box-shadow: 0 12px 25px rgba(59,130,246,0.3); }
        
        .logout-section { text-align: center; margin-top: 25px; }
        .logout-link { color: #fca5a5; text-decoration: none; font-size: 12px; font-weight: 700; transition: 0.3s; }
        .logout-link:hover { color: #ef4444; }
    </style>
</head>
<body>

<nav>
    <div class="nav-brand"><i class="fas fa-screwdriver-wrench"></i> si tukang.</div>
    <div class="nav-links">
        <a href="dashboard_user.php">Eksplor</a>
        <a href="user_pesanan.php">Pesanan</a>
        <a href="user_profil.php" class="active">Profil</a>
        <a href="logout.php">Keluar</a>
    </div>
</nav>

<div class="container">
    <div class="profile-card">
        <div class="profile-header">
            <div class="avatar-wrapper">
                <i class="fas fa-user-shield"></i>
            </div>
            <h2>Pengaturan Akun</h2>
            <p>Kelola informasi identitas Anda di Si Tukang.</p>
        </div>

        <form action="" method="POST">
            <div class="form-group">
                <label>Alamat Email</label>
                <input type="email" value="<?= $u['email']; ?>" disabled>
            </div>
            
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" value="<?= $u['nama']; ?>" placeholder="Masukkan nama lengkap" required>
            </div>

            <div class="form-group">
                <label>Nomor WhatsApp (Aktif)</label>
                <input type="text" name="no_hp" value="<?= $u['no_hp']; ?>" placeholder="0812xxxx" required>
            </div>

            <div class="form-group">
                <label>Kata Sandi Baru</label>
                <input type="password" name="password" value="<?= $u['password']; ?>" placeholder="Minimal 6 karakter" required>
            </div>

            <button type="submit" name="update_profil" class="btn-save">
                PERBARUI PROFIL
            </button>
        </form>

        <div class="logout-section">
            <a href="logout.php" class="logout-link">Ingin keluar dari akun? Klik di sini</a>
        </div>
    </div>
</div>

<script>
    // GSAP Entrance Animation
    window.onload = () => {
        gsap.to(".profile-card", { opacity: 1, y: 0, duration: 1, ease: "power4.out" });
    };

    <?php if(isset($_GET['status'])): ?>
    Swal.fire({
        icon: '<?= $_GET['icon'] ?>',
        title: '<?= $_GET['pesan'] ?>',
        showConfirmButton: false,
        timer: 2000,
        background: '#fff',
        color: '#0f172a'
    });
    <?php endif; ?>
</script>

</body>
</html>