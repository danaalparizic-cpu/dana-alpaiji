<?php
include 'config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("location:index.php?status=error&pesan=Akses Ditolak!&icon=error"); exit;
}

// Logika Tambah Kategori
if (isset($_POST['tambah_kategori'])) {
    $nama_kategori = mysqli_real_escape_string($conn, $_POST['nama_kategori']);
    $query = mysqli_query($conn, "INSERT INTO kategori (nama_kategori) VALUES ('$nama_kategori')");
    if ($query) {
        header("location:admin_kategori.php?status=success&pesan=Kategori Berhasil Ditambahkan&icon=success");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kategori Jasa | Admin Si Tukang</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary: #0056FF; --bg: #F4F7FE; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-size: 13px; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); display: flex; }
        .main-content { flex: 1; margin-left: 220px; padding: 30px; }
        .card { background: white; padding: 25px; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); }
        input { padding: 10px; border: 1px solid #E2E8F0; border-radius: 8px; width: 100%; outline: none; }
        .btn-primary { background: var(--primary); color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: 600; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { text-align: left; padding: 12px; color: #718096; border-bottom: 2px solid #F7FAFC; font-size: 11px; text-transform: uppercase; }
        td { padding: 12px; border-bottom: 1px solid #F7FAFC; }
    </style>
</head>
<body>

<?php include 'sidebar_admin.php'; ?>

<div class="main-content">
    <div class="header animate__animated animate__fadeInDown" style="margin-bottom: 25px;">
        <h2 style="font-size: 18px; font-weight: 700;">Pengaturan Kategori Jasa</h2>
        <p style="color: #718096;">Kelola jenis layanan yang tersedia di aplikasi.</p>
    </div>

    <div class="card animate__animated animate__fadeInUp">
        <h3 style="margin-bottom: 15px;">Tambah Kategori Baru</h3>
        <form action="" method="POST" style="display: flex; gap: 10px;">
            <input type="text" name="nama_kategori" placeholder="Misal: Tukang Cat, Tukang Kayu..." required>
            <button type="submit" name="tambah_kategori" class="btn-primary">Tambah</button>
        </form>

        <hr style="margin: 30px 0; border: 0; border-top: 1px solid #F1F5F9;">

        <h3>Daftar Kategori Saat Ini</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Kategori</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $q = mysqli_query($conn, "SELECT * FROM kategori ORDER BY id DESC");
                if(mysqli_num_rows($q) == 0) echo "<tr><td colspan='3' style='text-align:center; padding:20px;'>Belum ada kategori.</td></tr>";
                while($d = mysqli_fetch_assoc($q)) {
                    echo "<tr>
                        <td>#{$d['id']}</td>
                        <td style='font-weight:600;'>{$d['nama_kategori']}</td>
                        <td><span style='color:#10B981;'><i class='fas fa-check-circle'></i> Aktif</span></td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    <?php if(isset($_GET['status'])): ?>
    Swal.fire({ icon: '<?= $_GET['icon'] ?>', title: '<?= $_GET['pesan'] ?>', showConfirmButton: false, timer: 1500, toast: true, position: 'top-end' });
    <?php endif; ?>
</script>
</body>
</html>