<?php
include 'config.php';

// Proteksi halaman
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'tukang') {
    header("location:index.php?status=error&pesan=Akses Ditolak!&icon=error"); exit;
}

$id_tukang = $_SESSION['id'];

// 1. Hitung Total Pendapatan Kotor (90% dari jasa yang sudah berstatus 'selesai')
$q_pendapatan = mysqli_query($conn, "SELECT SUM(harga * 0.9) as total FROM pesanan WHERE tukang_id = '$id_tukang' AND status = 'selesai'");
$total_masuk = mysqli_fetch_assoc($q_pendapatan)['total'] ?? 0;

// 2. Hitung Total Dana yang SUDAH DITARIK dari tabel penarikan
$q_tarik = mysqli_query($conn, "SELECT SUM(nominal) as total FROM penarikan WHERE tukang_id = '$id_tukang' AND status = 'berhasil'");
$total_keluar = mysqli_fetch_assoc($q_tarik)['total'] ?? 0;

// 3. Saldo Berjalan / Sisa Saldo (Pendapatan - Penarikan)
$saldo_sekarang = $total_masuk - $total_keluar;

// 4. Hitung Total Komisi Admin (10%) untuk laporan transparansi
$q_komisi = mysqli_query($conn, "SELECT SUM(komisi_admin) as total FROM pesanan WHERE tukang_id = '$id_tukang' AND status = 'selesai'");
$total_komisi = mysqli_fetch_assoc($q_komisi)['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dompet Partner | Si Tukang</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

    <style>
        :root { --primary: #3b82f6; --dark: #0f172a; --bg: #f8fafc; --success: #10b981; --danger: #ef4444; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: var(--bg); color: var(--dark); }

        nav { 
            background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(15px);
            padding: 15px 8%; display: flex; justify-content: space-between; 
            align-items: center; position: sticky; top: 0; z-index: 1000;
        }
        .nav-brand { color: white; font-weight: 800; font-size: 20px; letter-spacing: -1px; }
        .nav-links { display: flex; gap: 20px; }
        .nav-links a { color: rgba(255,255,255,0.6); text-decoration: none; font-weight: 600; font-size: 11px; text-transform: uppercase; transition: 0.4s; }
        .nav-links a.active { color: white; border-bottom: 2px solid var(--primary); padding-bottom: 5px; }

        .container { max-width: 850px; margin: 40px auto; padding: 0 20px; }

        /* Wallet Card Premium */
        .wallet-card {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            border-radius: 30px; padding: 35px; color: white;
            position: relative; overflow: hidden; margin-bottom: 30px;
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.2);
            opacity: 0; transform: translateY(30px);
        }
        .wallet-card small { font-weight: 700; color: rgba(255,255,255,0.5); font-size: 10px; text-transform: uppercase; letter-spacing: 1.5px; }
        .wallet-card h1 { font-size: 38px; font-weight: 800; margin: 10px 0; letter-spacing: -1px; }
        
        .btn-withdraw {
            background: var(--primary); color: white; border: none; padding: 12px 25px;
            border-radius: 14px; font-weight: 800; cursor: pointer; transition: 0.3s;
            margin-top: 15px; font-size: 11px; text-transform: uppercase;
        }
        .btn-withdraw:hover { transform: scale(1.05); box-shadow: 0 10px 20px rgba(59,130,246,0.3); }

        /* Multi History Sections */
        .history-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .history-section { background: white; border-radius: 24px; padding: 25px; border: 1px solid #f1f5f9; opacity: 0; height: fit-content; }
        .history-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .history-header h3 { font-size: 14px; font-weight: 800; text-transform: uppercase; color: var(--dark); }

        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 10px; color: #94a3b8; font-size: 9px; text-transform: uppercase; letter-spacing: 1px; }
        td { padding: 12px 10px; font-size: 12px; border-bottom: 1px solid #f8fafc; }
        
        .income { color: var(--success); font-weight: 800; }
        .expense { color: var(--danger); font-weight: 800; }
        .swal-label { display: block; text-align: left; font-size: 10px; font-weight: 800; color: #94a3b8; margin-top: 12px; text-transform: uppercase; }
    </style>
</head>
<body>

<nav>
    <div class="nav-brand">si tukang.</div>
    <div class="nav-links">
        <a href="dashboard_tukang.php">Pekerjaan</a>
        <a href="tukang_dompet.php" class="active">Dompet</a>
        <a href="tukang_profil.php">Profil</a>
        <a href="logout.php">Keluar</a>
    </div>
</nav>

<div class="container">
    <div class="wallet-card">
        <small>Total Saldo Anda</small>
        <h1>Rp <?= number_format($saldo_sekarang, 0, ',', '.'); ?></h1>
        <div style="display:flex; gap:25px; margin-top:10px;">
            <p style="font-size: 10px; color: rgba(255,255,255,0.5);">Telah Ditarik: <br><b style="color:white; font-size:12px;">Rp <?= number_format($total_keluar, 0, ',', '.'); ?></b></p>
            <p style="font-size: 10px; color: rgba(255,255,255,0.5);">Biaya Layanan Admin: <br><b style="color:white; font-size:12px;">Rp <?= number_format($total_komisi, 0, ',', '.'); ?></b></p>
        </div>
        <button class="btn-withdraw" onclick="tarikDana()">Tarik Saldo Sekarang</button>
    </div>

    <div class="history-grid">
        <div class="history-section" id="section-in">
            <div class="history-header"><h3>Pendapatan Jasa</h3></div>
            <table>
                <thead>
                    <tr><th>Jasa</th><th style="text-align: right;">Bersih</th></tr>
                </thead>
                <tbody>
                    <?php 
                    $q_in = mysqli_query($conn, "SELECT * FROM pesanan WHERE tukang_id = '$id_tukang' AND status = 'selesai' ORDER BY id DESC LIMIT 5");
                    while($h = mysqli_fetch_assoc($q_in)) {
                    ?>
                    <tr>
                        <td><div style="font-weight:700;"><?= $h['jasa']; ?></div><small style="color:#94a3b8"><?= date('d/m/y', strtotime($h['created_at'])); ?></small></td>
                        <td class="income" style="text-align:right;">+<?= number_format($h['harga']*0.9, 0, ',', '.'); ?></td>
                    </tr>
                    <?php } if(mysqli_num_rows($q_in) == 0) echo '<tr><td colspan="2" style="text-align:center; padding:20px; color:#94a3b8;">Belum ada pendapatan.</td></tr>'; ?>
                </tbody>
            </table>
        </div>

        <div class="history-section" id="section-out">
            <div class="history-header"><h3>Penarikan Dana</h3></div>
            <table>
                <thead>
                    <tr><th>Metode</th><th style="text-align: right;">Nominal</th></tr>
                </thead>
                <tbody>
                    <?php 
                    $q_out = mysqli_query($conn, "SELECT * FROM penarikan WHERE tukang_id = '$id_tukang' ORDER BY id DESC LIMIT 5");
                    while($p = mysqli_fetch_assoc($q_out)) {
                    ?>
                    <tr>
                        <td><div style="font-weight:700;"><?= $p['metode']; ?></div><small style="color:#94a3b8"><?= date('d/m/y', strtotime($p['created_at'])); ?></small></td>
                        <td class="expense" style="text-align:right;">-<?= number_format($p['nominal'], 0, ',', '.'); ?></td>
                    </tr>
                    <?php } if(mysqli_num_rows($q_out) == 0) echo '<tr><td colspan="2" style="text-align:center; padding:20px; color:#94a3b8;">Belum ada penarikan.</td></tr>'; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    window.onload = () => {
        gsap.to(".wallet-card", { opacity: 1, y: 0, duration: 1, ease: "power4.out" });
        gsap.to(".history-section", { opacity: 1, duration: 0.8, stagger: 0.2, delay: 0.4 });
    };

    function tarikDana() {
        const saldoSistem = <?= $saldo_sekarang ?>;
        if(saldoSistem < 10000) return Swal.fire('Saldo Kurang', 'Minimal penarikan Rp 10.000', 'error');

        Swal.fire({
            title: 'Konfirmasi Penarikan',
            html: `
                <div style="text-align:left">
                    <label class="swal-label">Pilih Gateway</label>
                    <select id="metode" class="swal2-input" style="font-size:13px">
                        <option value="DANA">DANA (Otomatis)</option>
                        <option value="OVO">OVO (Otomatis)</option>
                        <option value="Bank BCA">Bank BCA (Real-time)</option>
                    </select>
                    <label class="swal-label">Nomor Tujuan</label>
                    <input type="number" id="nomor" class="swal2-input" placeholder="08xxxx / Rekening" style="font-size:13px">
                    <label class="swal-label">Nominal Tarik</label>
                    <input type="number" id="nominal" class="swal2-input" placeholder="Maks: ${saldoSistem}" style="font-size:13px">
                </div>
            `,
            confirmButtonText: 'Proses Transfer',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            preConfirm: () => {
                const n = document.getElementById('nominal').value;
                if(!n || n < 10000) return Swal.showValidationMessage('Minimal Rp 10.000');
                if(n > saldoSistem) return Swal.showValidationMessage('Saldo tidak mencukupi');
                return { 
                    metode: document.getElementById('metode').value,
                    nomor: document.getElementById('nomor').value,
                    nominal: n 
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Memproses Gateway...',
                    html: 'Sedang melakukan verifikasi akun tujuan.',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });

                const form = new FormData();
                form.append('nominal', result.value.nominal);
                form.append('metode', result.value.metode);
                form.append('nomor', result.value.nomor);

                fetch('proses_tarik.php', { method: 'POST', body: form })
                .then(res => res.text())
                .then(data => {
                    if(data.trim() === "success") {
                        Swal.fire('Transfer Berhasil!', 'Dana telah dikirim ke akun Anda.', 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Gagal', 'Terjadi gangguan sistem gateway.', 'error');
                    }
                });
            }
        });
    }
</script>

</body>
</html>