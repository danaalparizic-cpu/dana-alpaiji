<?php
include 'config.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("location:index.php?status=error&pesan=Akses Ditolak!&icon=error"); exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Monitoring Transaksi | Si Tukang</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary: #0056FF; --bg: #F4F7FE; --white: #ffffff; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-size: 12px; } /* Font lebih kecil */
        body { font-family: 'Inter', sans-serif; background: var(--bg); display: flex; }
        .sidebar { width: 220px; background: var(--primary); color: white; height: 100vh; position: fixed; padding: 25px 15px; }
        .main-content { flex: 1; margin-left: 220px; padding: 30px; }
        .card { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); }
        
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { text-align: left; padding: 12px; color: #718096; border-bottom: 2px solid #F7FAFC; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
        td { padding: 12px; border-bottom: 1px solid #F7FAFC; }
        
        .badge { padding: 4px 8px; border-radius: 4px; font-weight: 700; font-size: 9px; }
        .badge-proses { background: #FEF3C7; color: #D97706; }
        .badge-selesai { background: #D1FAE5; color: #059669; }
        
        .empty-state { text-align: center; padding: 40px; color: #A0AEC0; }
    </style>
</head>
<body>

<?php include 'sidebar_admin.php'; ?>

<div class="main-content">
    <div class="header animate__animated animate__fadeInDown" style="margin-bottom: 20px;">
        <h2 style="font-size: 16px; font-weight: 700;">Log Transaksi Realtime</h2>
        <p style="color: #718096;">Memantau jasa aktif di wilayah Serang dan sekitarnya.</p>
    </div>

    <div class="card animate__animated animate__fadeInUp">
        <table>
            <thead>
                <tr>
                    <th>ID Pesanan</th>
                    <th>Pelanggan</th>
                    <th>Tukang</th>
                    <th>Jasa</th>
                    <th>Harga</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr><td colspan="6" class="empty-state">Belum ada aktivitas transaksi terdeteksi.</td></tr>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>