<?php
include 'config.php';

// Proteksi halaman: Hanya tukang yang boleh masuk
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'tukang') {
    header("location:index.php?status=error&pesan=Akses Ditolak!&icon=error"); exit;
}

$id_tukang = $_SESSION['id'];

// Hitung Statistik Tukang (Pendapatan bersih 90% setelah komisi admin 10%)
$q_selesai = mysqli_query($conn, "SELECT SUM(harga * 0.9) as total FROM pesanan WHERE tukang_id = '$id_tukang' AND status = 'selesai'");
$total_pendapatan = mysqli_fetch_assoc($q_selesai)['total'] ?? 0;

$q_aktif = mysqli_query($conn, "SELECT COUNT(*) as jml FROM pesanan WHERE tukang_id = '$id_tukang' AND status != 'selesai'");
$order_aktif = mysqli_fetch_assoc($q_aktif)['jml'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partner Dashboard | Si Tukang</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

    <style>
        :root { --primary: #3b82f6; --dark: #0f172a; --bg: #f8fafc; --success: #10b981; --warning: #f59e0b; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: var(--bg); color: var(--dark); }

        /* Navbar Partner Premium */
        nav { 
            background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(15px);
            padding: 15px 8%; display: flex; justify-content: space-between; 
            align-items: center; position: sticky; top: 0; z-index: 1000;
        }
        .nav-brand { color: white; font-weight: 800; font-size: 20px; letter-spacing: -1px; }
        .nav-brand span { color: var(--primary); font-size: 10px; border: 1px solid var(--primary); padding: 2px 8px; border-radius: 50px; margin-left: 8px; vertical-align: middle; font-weight: 700; }
        .nav-links { display: flex; gap: 20px; }
        .nav-links a { color: rgba(255,255,255,0.6); text-decoration: none; font-weight: 600; font-size: 11px; text-transform: uppercase; transition: 0.4s; }
        .nav-links a.active { color: white; border-bottom: 2px solid var(--primary); padding-bottom: 5px; }

        .container { max-width: 900px; margin: 40px auto; padding: 0 20px; }

        /* Stats Section */
        .stats-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 40px; }
        .stat-card { 
            background: white; padding: 25px; border-radius: 24px; border: 1px solid #f1f5f9; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.02); opacity: 0; transform: translateY(20px);
        }
        .stat-card small { color: #94a3b8; font-weight: 800; font-size: 10px; text-transform: uppercase; letter-spacing: 1px; }
        .stat-card h2 { font-size: 24px; font-weight: 800; margin-top: 5px; color: var(--dark); }

        /* Order Card Sinkron */
        .order-card { 
            background: white; border-radius: 24px; padding: 25px; border: 1px solid #f1f5f9; 
            margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;
            opacity: 0; transform: translateX(-30px); transition: 0.4s;
        }
        .order-card:hover { border-color: var(--primary); transform: translateX(5px); box-shadow: 0 15px 35px rgba(0,0,0,0.05); }

        .order-info h4 { font-size: 16px; font-weight: 800; margin-bottom: 10px; }
        .order-info p { color: #64748b; font-size: 12px; margin-bottom: 5px; display: flex; align-items: center; gap: 8px; }
        
        .badge { padding: 6px 14px; border-radius: 50px; font-size: 9px; font-weight: 800; text-transform: uppercase; margin-bottom: 12px; display: inline-block; }
        .bg-new { background: #eff6ff; color: #3b82f6; } /* Butuh Harga */
        .bg-wait { background: #fff7ed; color: #f59e0b; } /* Nunggu User */
        .bg-ready { background: #f0fdf4; color: #10b981; } /* Siap Dikerjakan */

        .btn-action { 
            background: var(--dark); color: white; border: none; padding: 12px 20px; 
            border-radius: 14px; font-weight: 800; cursor: pointer; transition: 0.4s; font-size: 11px;
            text-decoration: none; display: inline-block;
        }
        .btn-action:hover { background: var(--primary); transform: scale(1.05); box-shadow: 0 10px 20px rgba(59,130,246,0.2); }
        .btn-finish { background: var(--success); }

        .img-preview { width: 55px; height: 55px; border-radius: 14px; object-fit: cover; cursor: pointer; border: 2px solid #f1f5f9; transition: 0.3s; }
        .img-preview:hover { transform: scale(1.1); border-color: var(--primary); }
    </style>
</head>
<body>

<nav>
    <div class="nav-brand">si tukang. <span>PARTNER</span></div>
    <div class="nav-links">
        <a href="dashboard_tukang.php" class="active">Pekerjaan</a>
        <a href="tukang_dompet.php">Dompet</a>
        <a href="tukang_profil.php">Profil</a>
        <a href="logout.php" style="color: #fca5a5;">Keluar</a>
    </div>
</nav>

<div class="container">
    <div class="stats-grid">
        <div class="stat-card">
            <small><i class="fas fa-wallet" style="margin-right:5px;"></i> Gaji Bersih (90%)</small>
            <h2>Rp <?= number_format($total_pendapatan, 0, ',', '.'); ?></h2>
        </div>
        <div class="stat-card">
            <small><i class="fas fa-tasks" style="margin-right:5px;"></i> Antrean Jasa</small>
            <h2><?= $order_aktif; ?> Aktif</h2>
        </div>
    </div>

    <h3 style="font-weight: 800; font-size: 18px; margin-bottom: 25px; letter-spacing: -0.5px;">Manajemen Pekerjaan</h3>

    <div class="order-list">
        <?php 
        $q_list = mysqli_query($conn, "SELECT p.*, u.nama as pelanggan, u.no_hp 
                                      FROM pesanan p 
                                      JOIN users u ON p.user_id = u.id 
                                      WHERE p.tukang_id = '$id_tukang' AND p.status != 'selesai' 
                                      ORDER BY p.id DESC");

        if(mysqli_num_rows($q_list) == 0) {
            echo '<div style="text-align:center; padding:80px; background:white; border-radius:30px; color:#94a3b8; border:2px dashed #e2e8f0;">
                    <i class="fas fa-mug-hot" style="font-size:40px; margin-bottom:15px; display:block;"></i>
                    <p>Santai dulu, belum ada orderan baru.</p>
                  </div>';
        }

        while($row = mysqli_fetch_assoc($q_list)) {
        ?>
        <div class="order-card">
            <div class="order-info">
                <?php if($row['harga'] == 0): ?>
                    <span class="badge bg-new">Langkah 1: Beri Estimasi</span>
                <?php elseif($row['status'] == 'proses'): ?>
                    <span class="badge bg-wait">Langkah 2: Tunggu User Setuju</span>
                <?php else: ?>
                    <span class="badge bg-ready">Langkah 3: Kerjakan & Selesaikan</span>
                <?php endif; ?>

                <h4><?= $row['jasa']; ?></h4>
                <p><i class="fas fa-user-circle"></i> Pelanggan: <?= $row['pelanggan']; ?></p>
                <p><i class="fab fa-whatsapp" style="color:#25d366;"></i> <a href="https://wa.me/<?= $row['no_hp']; ?>" target="_blank" style="color:var(--dark); font-weight:700;">Chat WhatsApp</a></p>
                <p><i class="fas fa-image"></i> Bukti Kendala: 
                    <img src="assets/img/<?= $row['foto_kendala']; ?>" class="img-preview" onclick="viewImage('assets/img/<?= $row['foto_kendala']; ?>')">
                </p>
            </div>

            <div style="text-align: right; min-width: 180px;">
                <?php if($row['harga'] == 0): ?>
                    <button class="btn-action" onclick="inputHarga(<?= $row['id']; ?>)">
                        <i class="fas fa-calculator" style="margin-right:5px;"></i> Hitung Harga
                    </button>
                <?php elseif($row['status'] == 'proses'): ?>
                    <div style="font-weight: 800; font-size: 18px; color: var(--warning);">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></div>
                    <p style="font-size:10px; color:#94a3b8; margin-top:5px;">User belum menanggapi</p>
                <?php elseif($row['status'] == 'diterima'): ?>
                    <div style="font-weight: 800; font-size: 18px; color: var(--success); margin-bottom:12px;">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></div>
                    <a href="proses_tukang.php?aksi=selesai&id=<?= $row['id']; ?>" class="btn-action btn-finish" onclick="return confirm('Pekerjaan sudah beres?')">
                        <i class="fas fa-check-double"></i> Tandai Selesai
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

<script>
    // GSAP Staggered Entrance
    window.onload = () => {
        gsap.to(".stat-card", { opacity: 1, y: 0, stagger: 0.15, duration: 0.8, ease: "power2.out" });
        gsap.to(".order-card", { opacity: 1, x: 0, stagger: 0.15, duration: 0.8, delay: 0.4, ease: "power2.out" });
    };

    function viewImage(src) {
        Swal.fire({ 
            imageUrl: src, 
            imageAlt: 'Visual Kendala', 
            showConfirmButton: false,
            background: 'rgba(255,255,255,0.9)',
            backdrop: `rgba(15, 23, 42, 0.4) blur(4px)`
        });
    }

    function inputHarga(id) {
        Swal.fire({
            title: 'Tentukan Tarif Jasa',
            text: 'Harga yang kamu kirim akan ditinjau oleh user.',
            input: 'number',
            inputPlaceholder: 'Nominal dalam Rupiah...',
            showCancelButton: true,
            confirmButtonText: 'Kirim Harga',
            confirmButtonColor: '#0f172a',
            preConfirm: (harga) => {
                if (!harga || harga < 1000) return Swal.showValidationMessage('Masukkan harga yang valid!');
                const formData = new FormData();
                formData.append('id', id);
                formData.append('harga', harga);
                return fetch('proses_tukang.php?aksi=kirim_harga', {
                    method: 'POST',
                    body: formData
                }).then(res => res.text());
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Penawaran harga telah dikirim.', showConfirmButton: false, timer: 1500 });
                setTimeout(() => location.reload(), 1600);
            }
        });
    }
</script>

</body>
</html>