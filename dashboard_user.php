<?php
include 'config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("location:index.php?status=error&pesan=Akses Ditolak!&icon=error"); exit;
}

$id_user = $_SESSION['id'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Si Tukang</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

    <style>
        :root { 
            --primary: #3b82f6; 
            --dark: #0f172a; 
            --accent: #6366f1;
            --bg: #f8fafc; 
            --whatsapp: #25d366;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: var(--bg); color: var(--dark); overflow-x: hidden; }

        .bg-layer {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: radial-gradient(circle at 10% 20%, rgba(59, 130, 246, 0.05) 0%, transparent 50%),
                        radial-gradient(circle at 90% 80%, rgba(99, 102, 241, 0.05) 0%, transparent 50%);
            z-index: -1;
        }

        nav { 
            background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(15px);
            padding: 15px 8%; display: flex; justify-content: space-between; 
            align-items: center; position: sticky; top: 0; z-index: 1000;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .nav-brand { color: white; font-weight: 800; font-size: 20px; letter-spacing: -1px; display: flex; align-items: center; gap: 10px; }
        .nav-brand i { color: var(--primary); text-shadow: 0 0 10px var(--primary); }
        .nav-links { display: flex; gap: 20px; }
        .nav-links a { color: rgba(255,255,255,0.6); text-decoration: none; font-weight: 600; font-size: 11px; letter-spacing: 0.5px; transition: 0.4s; text-transform: uppercase; }
        .nav-links a.active { color: white; border-bottom: 2px solid var(--primary); padding-bottom: 5px; }

        .container { max-width: 900px; margin: 40px auto; padding: 0 20px; }

        .hero-section { margin-bottom: 40px; }
        .philosophy-tag { 
            display: inline-block; padding: 6px 16px; background: white; 
            color: var(--primary); border-radius: 50px; font-size: 10px; font-weight: 800; 
            margin-bottom: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border: 1px solid rgba(59, 130, 246, 0.1);
        }
        .hero-section h2 { font-size: 32px; font-weight: 800; letter-spacing: -1px; line-height: 1.2; opacity: 0; transform: translateY(30px); }

        .search-container { position: relative; margin-bottom: 45px; opacity: 0; }
        .search-bar { 
            width: 100%; padding: 20px 25px 20px 60px; border-radius: 24px; border: 1px solid #e2e8f0; 
            background: white; box-shadow: 0 15px 35px rgba(0,0,0,0.03); outline: none; transition: 0.4s;
        }
        .search-bar:focus { border-color: var(--primary); box-shadow: 0 15px 40px rgba(59,130,246,0.15); transform: scale(1.02); }
        .search-container i { position: absolute; left: 25px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 20px; }

        .category-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(135px, 1fr)); gap: 15px; margin-bottom: 50px; }
        .cat-card { 
            background: white; padding: 25px 15px; border-radius: 24px; text-align: center; 
            cursor: pointer; transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); 
            border: 1px solid #f1f5f9; opacity: 0;
        }
        .cat-card:hover { background: var(--primary); border-color: var(--primary); transform: translateY(-10px); }
        .cat-card:hover i, .cat-card:hover span { color: white; }
        .cat-card i { font-size: 26px; color: var(--primary); margin-bottom: 15px; display: block; transition: 0.3s; }
        .cat-card span { font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.8px; transition: 0.3s; }

        .section-label { font-size: 18px; font-weight: 800; margin-bottom: 25px; display: flex; align-items: center; gap: 10px; }
        .section-label::after { content: ""; height: 2px; flex: 1; background: #e2e8f0; border-radius: 2px; }

        .tukang-card { 
            background: white; border-radius: 28px; padding: 25px; display: flex; 
            align-items: center; gap: 20px; margin-bottom: 20px; border: 1px solid #f1f5f9;
            transition: 0.5s; opacity: 0; transform: scale(0.9);
        }
        .tukang-card:hover { transform: scale(1.03) translateY(-5px); box-shadow: 0 20px 40px rgba(0,0,0,0.06); border-color: var(--primary); }
        .tukang-img { width: 85px; height: 85px; border-radius: 22px; object-fit: cover; border: 3px solid #f8fafc; }
        .tukang-info { flex: 1; }
        .tukang-info h4 { font-size: 18px; font-weight: 800; margin-bottom: 4px; }
        .dist-tag { font-size: 11px; color: var(--primary); font-weight: 700; display: flex; align-items: center; gap: 5px; margin-bottom: 5px; }
        .tukang-info p { color: #64748b; font-size: 12px; font-weight: 500; }
        .star-rating { background: #fff7ed; color: #f59e0b; padding: 5px 12px; border-radius: 50px; font-size: 11px; font-weight: 800; display: inline-flex; align-items: center; gap: 6px; margin-top: 10px; }

        .btn-group { display: flex; flex-direction: column; gap: 8px; }
        .btn-order { 
            background: var(--dark); color: white; border: none; padding: 12px 22px; 
            border-radius: 14px; font-weight: 800; cursor: pointer; transition: 0.4s; font-size: 11px;
        }
        .btn-whatsapp { background: var(--whatsapp); color: white; text-decoration: none; text-align: center; }
        .btn-order:hover { transform: scale(1.05); filter: brightness(1.1); }

        .swal-custom-label { font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; text-align: left; display: block; margin-top: 15px; }
        .swal-custom-input { width: 100%; padding: 12px; border-radius: 12px; border: 1px solid #e2e8f0; margin-top: 6px; font-size: 13px; }
    </style>
</head>
<body>

<div class="bg-layer"></div>

<nav>
    <div class="nav-brand"><i class="fas fa-screwdriver-wrench"></i> si tukang.</div>
    <div class="nav-links">
        <a href="dashboard_user.php" class="active">Eksplor</a>
        <a href="user_pesanan.php">Pesanan Saya</a>
        <a href="user_profil.php">Profil</a>
        <a href="logout.php" style="color: #fca5a5;">Keluar</a>
    </div>
</nav>

<div class="container">
    <div class="hero-section">
        <div class="philosophy-tag animate__animated animate__fadeInDown">PROYEK PKM-K: SOLUSI JASA TERPERCAYA</div>
        <h2 id="welcome-text">Halo, <?= $_SESSION['nama']; ?>! <br>Cari Tukang Terdekatmu.</h2>
    </div>

    <div class="search-container">
        <input type="text" class="search-bar" placeholder="Cari layanan (AC, Bangunan, Listrik)...">
        <i class="fas fa-search"></i>
    </div>

    <div class="category-grid">
        <?php 
        $q_kat = mysqli_query($conn, "SELECT * FROM kategori");
        while($k = mysqli_fetch_assoc($q_kat)) {
            $icon = 'fa-tools';
            if(stripos($k['nama_kategori'], 'AC') !== false) $icon = 'fa-snowflake';
            elseif(stripos($k['nama_kategori'], 'Bangunan') !== false) $icon = 'fa-hammer';
            elseif(stripos($k['nama_kategori'], 'Elektronik') !== false) $icon = 'fa-bolt';
        ?>
        <div class="cat-card">
            <i class="fas <?= $icon; ?>"></i>
            <span><?= $k['nama_kategori']; ?></span>
        </div>
        <?php } ?>
    </div>

    <div class="section-label">Tukang Disekitarmu</div>
    
    <div class="tukang-list">
        <?php 
        $q_tukang = mysqli_query($conn, "SELECT * FROM users WHERE role='tukang' AND status_verifikasi='1'");
        while($t = mysqli_fetch_assoc($q_tukang)) {
            // Simulasi Jarak untuk demo PKM
            $jarak = rand(0, 4) . "." . rand(1, 9);
        ?>
        <div class="tukang-card">
            <img src="https://ui-avatars.com/api/?name=<?= $t['nama']; ?>&background=0f172a&color=fff&bold=true" class="tukang-img">
            <div class="tukang-info">
                <div class="dist-tag"><i class="fas fa-location-dot"></i> <?= $jarak; ?> KM DARI LOKASI</div>
                <h4><?= $t['nama']; ?></h4>
                <p><i class="fas fa-phone-alt" style="margin-right: 5px;"></i> <?= $t['no_hp']; ?></p>
                <div class="star-rating">
                    <i class="fas fa-certificate"></i> TERVERIFIKASI
                </div>
            </div>
            <div class="btn-group">
                <a href="https://wa.me/<?= $t['no_hp']; ?>" target="_blank" class="btn-order btn-whatsapp">
                    <i class="fab fa-whatsapp"></i> TANYA
                </a>
                <button class="btn-order" onclick="buatPesanan(<?= $t['id']; ?>, '<?= $t['nama']; ?>', '<?= $t['no_hp']; ?>')">
                    PESAN
                </button>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

<script>
    window.onload = () => {
        gsap.to("#welcome-text", { opacity: 1, y: 0, duration: 1, ease: "power4.out" });
        gsap.to(".search-container", { opacity: 1, duration: 0.8, delay: 0.3 });
        gsap.to(".cat-card", { opacity: 1, duration: 0.8, stagger: 0.1, delay: 0.4, ease: "back.out(1.7)" });
        gsap.to(".tukang-card", { opacity: 1, scale: 1, duration: 0.7, stagger: 0.15, delay: 0.6, ease: "power2.out" });
    };

    function buatPesanan(idTukang, namaTukang, noHp) {
        Swal.fire({
            title: `<h3 style="font-weight:800;">Pesan ${namaTukang}</h3>`,
            html: `
                <div style="text-align:left;">
                    <div style="background: #f0fdf4; padding: 12px; border-radius: 12px; margin-bottom: 20px; border: 1px solid #dcfce7;">
                        <p style="font-size: 10px; color: #15803d; font-weight: 800; margin-bottom: 5px; text-transform:uppercase;">Hubungi Mitra Jika Urgent:</p>
                        <a href="tel:${noHp}" style="color: var(--dark); font-weight: 800; font-size: 13px; text-decoration: none; display:flex; align-items:center; gap:8px;">
                            <i class="fas fa-phone-alt"></i> ${noHp} (Klik untuk Telepon)
                        </a>
                    </div>

                    <form id="formOrder" enctype="multipart/form-data">
                        <input type="hidden" name="tukang_id" value="${idTukang}">
                        <input type="hidden" name="buat_pesanan" value="1">
                        
                        <label class="swal-custom-label">Apa yang rusak?</label>
                        <input type="text" name="jasa" id="jasa" class="swal-custom-input" placeholder="Misal: Perbaikan pipa bocor" required>
                        
                        <label class="swal-custom-label">Metode Bayar</label>
                        <select name="metode_bayar" id="metode_bayar" class="swal-custom-input">
                            <option value="cash">💵 Cash (Bayar di Tempat)</option>
                            <option value="qris">📱 QRIS / Transfer</option>
                        </select>

                        <label class="swal-custom-label">Foto Kendala (Wajib)</label>
                        <input type="file" name="foto" id="foto" class="swal-custom-input" accept="image/*" style="border:none; padding-left:0;">
                    </form>
                </div>
            `,
            showCancelButton: true,
            confirmButtonColor: '#0f172a',
            confirmButtonText: 'Kirim Permintaan',
            cancelButtonText: 'Batal',
            preConfirm: () => {
                const jasa = Swal.getPopup().querySelector('#jasa').value;
                const foto = Swal.getPopup().querySelector('#foto').files[0];
                if (!jasa || !foto) {
                    Swal.showValidationMessage(`Harap lengkapi deskripsi dan foto!`);
                    return false;
                }
                return { formData: new FormData(document.getElementById('formOrder')) }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('proses_pesanan.php', { method: 'POST', body: result.value.formData })
                .then(res => res.text())
                .then(data => {
                    if(data.trim() === "success") {
                        Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Menunggu estimasi harga dari tukang.', showConfirmButton: false, timer: 2000 });
                        setTimeout(() => window.location.href = 'user_pesanan.php', 2000);
                    } else {
                        Swal.fire('Error', 'Terjadi kesalahan pada server.', 'error');
                    }
                });
            }
        });
    }
</script>

</body>
</html>