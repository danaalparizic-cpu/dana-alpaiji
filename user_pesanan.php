<?php
include 'config.php';

// Proteksi halaman
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("location:index.php?status=error&pesan=Akses Ditolak!&icon=error"); exit;
}

$id_user = $_SESSION['id'];

// Logika User Menyetujui Harga
if(isset($_GET['aksi']) && $_GET['aksi'] == 'setuju_harga') {
    $id_p = mysqli_real_escape_string($conn, $_GET['id']);
    mysqli_query($conn, "UPDATE pesanan SET status = 'diterima' WHERE id = '$id_p'");
    header("location:user_pesanan.php?status=success&pesan=Harga disetujui! Pekerjaan dimulai.&icon=success");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya | Si Tukang</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

    <style>
        :root { --primary: #3b82f6; --dark: #0f172a; --bg: #f8fafc; --text-muted: #64748b; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: var(--bg); color: var(--dark); }

        /* Navbar Sinkron */
        nav { 
            background: rgba(15, 23, 42, 0.9); backdrop-filter: blur(15px);
            padding: 15px 8%; display: flex; justify-content: space-between; 
            align-items: center; position: sticky; top: 0; z-index: 1000;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .nav-brand { color: white; font-weight: 800; font-size: 20px; letter-spacing: -1px; }
        .nav-brand i { color: var(--primary); }
        .nav-links { display: flex; gap: 20px; }
        .nav-links a { color: rgba(255,255,255,0.6); text-decoration: none; font-weight: 600; font-size: 11px; text-transform: uppercase; transition: 0.4s; }
        .nav-links a.active { color: white; }

        .container { max-width: 800px; margin: 40px auto; padding: 0 20px; }
        .section-title { font-size: 22px; font-weight: 800; margin-bottom: 25px; letter-spacing: -0.5px; }

        /* Card Pesanan Premium */
        .order-card {
            background: white; border-radius: 24px; padding: 25px; 
            border: 1px solid #f1f5f9; margin-bottom: 20px;
            display: flex; justify-content: space-between; align-items: center;
            opacity: 0; transform: translateY(20px);
            transition: 0.4s;
        }
        .order-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.05); }

        .order-info h4 { font-size: 16px; font-weight: 800; margin-bottom: 8px; }
        .order-info p { color: var(--text-muted); font-size: 12px; margin-bottom: 4px; display: flex; align-items: center; gap: 8px; }

        /* Badge Status Sinkron Alur */
        .status-badge { padding: 6px 14px; border-radius: 50px; font-size: 10px; font-weight: 800; text-transform: uppercase; display: inline-block; margin-bottom: 12px; }
        .bg-waiting { background: #eff6ff; color: #3b82f6; } /* Menunggu Estimasi */
        .bg-negotiate { background: #fffbeb; color: #d97706; } /* Harga Masuk */
        .bg-active { background: #f0fdf4; color: #15803d; } /* Berjalan */
        .bg-done { background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; } /* Selesai */

        .price-tag { font-size: 18px; font-weight: 800; color: var(--primary); margin-bottom: 15px; }

        /* Buttons Sinkron */
        .btn-action { 
            border: none; padding: 12px 20px; border-radius: 12px; font-weight: 800; 
            cursor: pointer; font-size: 11px; transition: 0.3s; text-decoration: none; display: block; text-align: center;
        }
        .btn-approve { background: var(--dark); color: white; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.2); }
        .btn-finish { background: #10b981; color: white; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2); }
        .btn-rating { background: #ffc107; color: #000; }

        .empty-state { text-align: center; padding: 80px 20px; color: var(--text-muted); }
    </style>
</head>
<body>

<nav>
    <div class="nav-brand"><i class="fas fa-hammer"></i> si tukang.</div>
    <div class="nav-links">
        <a href="dashboard_user.php">Eksplor</a>
        <a href="user_pesanan.php" class="active">Pesanan</a>
        <a href="user_profil.php">Profil</a>
        <a href="logout.php">Keluar</a>
    </div>
</nav>

<div class="container">
    <h2 class="section-title">Status Pekerjaan</h2>

    <div class="order-list">
        <?php 
        $q_pesanan = mysqli_query($conn, "SELECT p.*, u.nama as nama_tukang 
                                         FROM pesanan p 
                                         JOIN users u ON p.tukang_id = u.id 
                                         WHERE p.user_id = '$id_user' 
                                         ORDER BY p.id DESC");

        if(mysqli_num_rows($q_pesanan) == 0) {
            echo '<div class="empty-state animate__animated animate__fadeIn">
                    <i class="fas fa-receipt" style="font-size:50px; margin-bottom:20px; opacity:0.3;"></i>
                    <p>Belum ada riwayat pesanan.</p>
                  </div>';
        }

        while($d = mysqli_fetch_assoc($q_pesanan)) {
        ?>
        <div class="order-card">
            <div class="order-info">
                <?php if($d['status'] == 'selesai'): ?>
                    <span class="status-badge bg-done">Pekerjaan Selesai</span>
                <?php elseif($d['harga'] == 0): ?>
                    <span class="status-badge bg-waiting">Menunggu Estimasi Harga</span>
                <?php elseif($d['status'] == 'proses'): ?>
                    <span class="status-badge bg-negotiate">Konfirmasi Penawaran Harga</span>
                <?php else: ?>
                    <span class="status-badge bg-active">Pekerjaan Sedang Berjalan</span>
                <?php endif; ?>

                <h4><?= $d['jasa']; ?></h4>
                <p><i class="fas fa-user-gear"></i> <?= $d['nama_tukang']; ?></p>
                <p><i class="fas fa-credit-card"></i> <?= strtoupper($d['metode_bayar']); ?></p>
                <p><i class="far fa-clock"></i> <?= date('d M, H:i', strtotime($d['created_at'])); ?></p>
            </div>

            <div style="text-align: right; min-width: 160px;">
                <?php if($d['harga'] > 0): ?>
                    <div class="price-tag">Rp <?= number_format($d['harga'], 0, ',', '.'); ?></div>
                    
                    <?php if($d['status'] == 'proses'): ?>
                        <a href="user_pesanan.php?aksi=setuju_harga&id=<?= $d['id']; ?>" class="btn-action btn-approve" onclick="return confirm('Setujui harga ini?')">
                            Setujui Harga
                        </a>
                    <?php elseif($d['status'] == 'diterima'): ?>
                        <button class="btn-action btn-finish" onclick="konfirmasiSelesai(<?= $d['id']; ?>)">
                            Selesaikan
                        </button>
                    <?php elseif($d['status'] == 'selesai'): ?>
                        <button class="btn-action btn-rating" onclick="beriRating(<?= $d['id']; ?>)">
                            <i class="fas fa-star"></i> Review
                        </button>
                    <?php endif; ?>

                <?php else: ?>
                    <div style="background:#f1f5f9; padding:10px; border-radius:10px; font-size:10px; color:#94a3b8; text-align:center;">
                        Menganalisa Foto...
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

<script>
    // GSAP Sinkron dengan Dashboard
    window.onload = () => {
        gsap.to(".order-card", { opacity: 1, y: 0, stagger: 0.15, duration: 0.8, ease: "power2.out" });
    };

    function konfirmasiSelesai(id) {
        Swal.fire({
            title: 'Pekerjaan Beres?',
            text: "Pastikan tukang sudah bekerja sesuai standar Si Tukang.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            confirmButtonText: 'Ya, Selesai!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Mengarah ke proses_user.php untuk hitung komisi admin 10%
                window.location.href = 'proses_user.php?aksi=selesai&id=' + id;
            }
        });
    }

    function beriRating(idPesanan) {
        Swal.fire({
            title: 'Beri Rating',
            html: `
                <select id="rating-score" class="swal2-input">
                    <option value="5">⭐⭐⭐⭐⭐ Sangat Baik</option>
                    <option value="4">⭐⭐⭐⭐ Baik</option>
                    <option value="3">⭐⭐⭐ Cukup</option>
                </select>
                <textarea id="rating-comment" class="swal2-textarea" placeholder="Tulis testimoni..."></textarea>
            `,
            confirmButtonText: 'Kirim',
            confirmButtonColor: '#0f172a',
            preConfirm: () => {
                return { 
                    id_pesanan: idPesanan,
                    rating: document.getElementById('rating-score').value,
                    ulasan: document.getElementById('rating-comment').value,
                    aksi: 'kirim_rating'
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // AJAX ke proses_user.php
                const formData = new FormData();
                for (const key in result.value) { formData.append(key, result.value[key]); }
                
                fetch('proses_user.php', { method: 'POST', body: formData })
                .then(() => Swal.fire('Terima Kasih!', 'Ulasan Anda sangat berharga.', 'success'));
            }
        });
    }
</script>

</body>
</html>