<?php
include 'config.php';

// Proteksi halaman: Hanya admin yang boleh masuk
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("location:index.php?status=error&pesan=Akses Ditolak!&icon=error"); exit;
}

// --- PENGAMBILAN DATA REAL-TIME ---

// 1. Hitung Total Komisi Admin (10%) dari pesanan yang sudah selesai
$q_komisi = mysqli_query($conn, "SELECT SUM(komisi_admin) as total_komisi FROM pesanan WHERE status='selesai'");
$total_komisi = mysqli_fetch_assoc($q_komisi)['total_komisi'] ?? 0;

// 2. Hitung Total Perputaran Uang (GMV) - Total harga semua pesanan selesai
$q_gmv = mysqli_query($conn, "SELECT SUM(harga) as total_gmv FROM pesanan WHERE status='selesai'");
$total_gmv = mysqli_fetch_assoc($q_gmv)['total_gmv'] ?? 0;

// 3. Jumlah Tukang Terverifikasi
$q_tukang = mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='tukang' AND status_verifikasi='1'");
$total_tukang = mysqli_fetch_assoc($q_tukang)['total'];

// 4. Jumlah Pesanan Aktif (Dalam proses atau diterima)
$q_pesanan = mysqli_query($conn, "SELECT COUNT(*) as total FROM pesanan WHERE status != 'selesai'");
$pesanan_aktif = mysqli_fetch_assoc($q_pesanan)['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Si Tukang</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

    <style>
        :root { 
            --primary: #3b82f6; 
            --dark-navy: #0f172a; 
            --bg: #f8fafc; 
            --text-muted: #64748b;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        
        body { background: var(--bg); color: var(--dark-navy); display: flex; overflow-x: hidden; }

        /* Main Content Padding & Sidebar Sync */
        .main-content { 
            flex: 1; 
            margin-left: 240px; 
            padding: 40px; 
            transition: all 0.3s;
        }

        .header-section {
            margin-bottom: 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-section h2 { font-size: 24px; font-weight: 800; letter-spacing: -1px; }

        /* Premium Stats Grid */
        .stats-grid { 
            display: grid; 
            grid-template-columns: repeat(4, 1fr); 
            gap: 20px; 
            margin-bottom: 40px;
        }

        .stat-card { 
            background: white; 
            padding: 25px; 
            border-radius: 24px; 
            border: 1px solid #f1f5f9;
            box-shadow: 0 10px 30px rgba(0,0,0,0.02);
            opacity: 0;
            transform: translateY(20px);
            transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 15px 35px rgba(59,130,246,0.1); }

        .stat-label {
            color: var(--text-muted);
            font-weight: 800;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .stat-value { font-size: 22px; font-weight: 800; color: var(--dark-navy); letter-spacing: -0.5px; }

        .icon-box {
            width: 38px; height: 38px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 16px;
        }

        /* Monitoring Table */
        .monitor-card {
            background: white;
            border-radius: 28px;
            padding: 30px;
            border: 1px solid #f1f5f9;
            opacity: 0;
            transform: translateY(30px);
        }

        .monitor-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        .monitor-header h3 { font-size: 18px; font-weight: 800; letter-spacing: -0.5px; }

        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 15px; color: #94a3b8; font-size: 10px; text-transform: uppercase; border-bottom: 2px solid #f8fafc; }
        td { padding: 18px 15px; font-size: 13px; border-bottom: 1px solid #f8fafc; }

        .badge { padding: 6px 12px; border-radius: 50px; font-size: 9px; font-weight: 800; text-transform: uppercase; }
        .status-selesai { background: #dcfce7; color: #15803d; }
        .status-proses { background: #fffbeb; color: #d97706; }
    </style>
</head>
<body>

<?php include 'sidebar_admin.php'; ?>

<div class="main-content">
    <div class="header-section">
        <div>
            <div style="background: var(--primary); color: white; padding: 4px 12px; border-radius: 50px; font-size: 10px; font-weight: 800; display: inline-block; margin-bottom: 10px;">ADMINISTRATOR</div>
            <h2>Ringkasan Ekosistem</h2>
        </div>
        <div style="background: white; padding: 10px 20px; border-radius: 15px; border: 1px solid #e2e8f0; font-weight: 700; font-size: 12px;">
            <i class="far fa-calendar-alt" style="margin-right: 8px; color: var(--primary);"></i> 
            <?= date('d M Y'); ?>
        </div>
    </div>
    
    <div class="stats-grid">
        <div class="stat-card" style="background: var(--dark-navy); border: none;">
            <div class="stat-label" style="color: rgba(255,255,255,0.5);">
                <div class="icon-box" style="background: rgba(59,130,246,0.2); color: #3b82f6;"><i class="fas fa-vault"></i></div>
                PROFIT ADMIN (10%)
            </div>
            <div class="stat-value" style="color: white;">Rp <?= number_format($total_komisi, 0, ',', '.'); ?></div>
        </div>

        <div class="stat-card">
            <div class="stat-label">
                <div class="icon-box" style="background: #eff6ff; color: var(--primary);"><i class="fas fa-chart-line"></i></div>
                VOLUME (GMV)
            </div>
            <div class="stat-value">Rp <?= number_format($total_gmv, 0, ',', '.'); ?></div>
        </div>

        <div class="stat-card">
            <div class="stat-label">
                <div class="icon-box" style="background: #fefce8; color: #ca8a04;"><i class="fas fa-user-gear"></i></div>
                MITRA AKTIF
            </div>
            <div class="stat-value"><?= $total_tukang; ?> Tukang</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">
                <div class="icon-box" style="background: #f0fdf4; color: #16a34a;"><i class="fas fa-spinner"></i></div>
                ORDER AKTIF
            </div>
            <div class="stat-value"><?= $pesanan_aktif; ?> Pesanan</div>
        </div>
    </div>

    <div class="monitor-card">
        <div class="monitor-header">
            <h3>Monitoring Transaksi Terbaru</h3>
            <i class="fas fa-ellipsis-h" style="color: #cbd5e1;"></i>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Partner Tukang</th>
                    <th>Layanan Jasa</th>
                    <th>Total Bayar</th>
                    <th>Profit Admin</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $q_trx = mysqli_query($conn, "SELECT p.*, u.nama as customer, t.nama as artisan 
                                             FROM pesanan p 
                                             JOIN users u ON p.user_id = u.id 
                                             JOIN users t ON p.tukang_id = t.id 
                                             ORDER BY p.id DESC LIMIT 5");
                if(mysqli_num_rows($q_trx) == 0) {
                    echo '<tr><td colspan="6" style="text-align:center; padding:40px; color:#94a3b8;">Belum ada transaksi terekam.</td></tr>';
                }
                while($row = mysqli_fetch_assoc($q_trx)) {
                    $status_badge = ($row['status'] == 'selesai') ? 'status-selesai' : 'status-proses';
                ?>
                <tr>
                    <td><b><?= $row['customer']; ?></b></td>
                    <td><?= $row['artisan']; ?></td>
                    <td><?= $row['jasa']; ?></td>
                    <td>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                    <td style="color: var(--primary); font-weight: 800;">Rp <?= number_format($row['komisi_admin'], 0, ',', '.'); ?></td>
                    <td><span class="badge <?= $status_badge; ?>"><?= $row['status']; ?></span></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    // Premium GSAP Animations
    window.onload = () => {
        gsap.to(".stat-card", { opacity: 1, y: 0, stagger: 0.15, duration: 0.8, ease: "power2.out" });
        gsap.to(".monitor-card", { opacity: 1, y: 0, duration: 1, delay: 0.6, ease: "power2.out" });
    };
</script>

</body>
</html>