<?php
include 'config.php';

// Proteksi Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("location:index.php?status=error&pesan=Akses Ditolak!&icon=error"); exit;
}

// Logika Verifikasi Tukang
if(isset($_GET['approve'])) {
    $id = mysqli_real_escape_string($conn, $_GET['approve']);
    $query = mysqli_query($conn, "UPDATE users SET status_verifikasi='1' WHERE id='$id'");
    if($query) {
        header("location:admin_verifikasi.php?status=success&pesan=Tukang Berhasil Diverifikasi&icon=success");
        exit;
    }
}

// Logika Tolak/Hapus Tukang (Jika data tidak valid)
if(isset($_GET['reject'])) {
    $id = mysqli_real_escape_string($conn, $_GET['reject']);
    $query = mysqli_query($conn, "DELETE FROM users WHERE id='$id'");
    if($query) {
        header("location:admin_verifikasi.php?status=success&pesan=Data Tukang Dihapus&icon=info");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Tukang | Admin Si Tukang</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary: #0056FF; --bg: #F4F7FE; --white: #ffffff; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-size: 13px; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); display: flex; }
        
        /* Sidebar Style (Pastikan sama dengan dashboard_admin.php) */
        .sidebar { width: 220px; background: var(--primary); color: white; height: 100vh; position: fixed; padding: 25px 15px; }
        .sidebar-brand { font-size: 18px; font-weight: 800; margin-bottom: 35px; }
        .menu-link { text-decoration: none; color: rgba(255,255,255,0.6); padding: 10px; display: flex; align-items: center; gap: 10px; border-radius: 8px; margin-bottom: 5px; transition: 0.3s; }
        .menu-link:hover, .menu-link.active { background: rgba(255,255,255,0.1); color: white; transform: translateX(5px); }

        .main-content { flex: 1; margin-left: 220px; padding: 30px; }
        .card { background: white; padding: 25px; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); }
        
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { text-align: left; padding: 12px; color: #718096; border-bottom: 2px solid #F7FAFC; font-size: 11px; text-transform: uppercase; }
        td { padding: 12px; border-bottom: 1px solid #F7FAFC; vertical-align: middle; }

        .btn-action { padding: 6px 12px; border-radius: 6px; border: none; cursor: pointer; font-weight: 600; text-decoration: none; display: inline-block; }
        .btn-approve { background: #10B981; color: white; }
        .btn-reject { background: #FEE2E2; color: #EF4444; margin-left: 5px; }
        
        .empty-state { text-align: center; padding: 50px; color: #A0AEC0; }
    </style>
</head>
<body>

<?php include 'sidebar_admin.php'; ?>

<div class="main-content">
    <div class="header animate__animated animate__fadeInDown" style="margin-bottom: 25px;">
        <h2 style="font-size: 18px; font-weight: 700;">Verifikasi Tukang Baru</h2>
        <p style="color: #718096;">Setujui pendaftaran tukang agar mereka muncul di list pencarian user.</p>
    </div>

    <div class="card animate__animated animate__fadeInUp">
        <table>
            <thead>
                <tr>
                    <th>Nama Tukang</th>
                    <th>Kontak</th>
                    <th>Email</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $query = mysqli_query($conn, "SELECT * FROM users WHERE role='tukang' AND status_verifikasi='0'");
                if(mysqli_num_rows($query) == 0) {
                    echo "<tr><td colspan='4' class='empty-state'><i class='fas fa-user-clock' style='font-size:30px; display:block; margin-bottom:10px;'></i> Belum ada pengajuan tukang baru.</td></tr>";
                }
                while($row = mysqli_fetch_assoc($query)) {
                ?>
                <tr class="animate__animated animate__fadeIn">
                    <td style="font-weight: 600;"><?= $row['nama'] ?></td>
                    <td><?= $row['no_hp'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td>
                        <a href="admin_verifikasi.php?approve=<?= $row['id'] ?>" class="btn-action btn-approve" onclick="return confirm('Setujui tukang ini?')">
                            <i class="fas fa-check"></i> Setujui
                        </a>
                        <a href="admin_verifikasi.php?reject=<?= $row['id'] ?>" class="btn-action btn-reject" onclick="return confirm('Hapus pengajuan ini?')">
                            <i class="fas fa-times"></i> Tolak
                        </a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
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