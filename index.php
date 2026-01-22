<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Si Tukang | Professional Services</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <style>
        :root {
            --primary: #0056FF; /* Biru Modern */
            --secondary: #FFC107; /* Kuning Aksen */
            --bg-light: #F4F7FE;
            --text-dark: #1A1A1A;
            --transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-dark);
            overflow-x: hidden;
        }

        /* Background Decor */
        .bg-decor {
            position: fixed;
            top: 0; right: 0;
            width: 50%; height: 100%;
            background: var(--primary);
            clip-path: polygon(25% 0%, 100% 0%, 100% 100%, 0% 100%);
            z-index: -1;
            opacity: 0.05;
        }

        /* Navbar Minimalis */
        nav {
            padding: 25px 10%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            position: sticky; top: 0; z-index: 100;
        }

        .logo { font-weight: 700; font-size: 1.5rem; color: var(--primary); letter-spacing: -1px; }

        /* Main Section */
        .hero-container {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 50px;
            padding: 60px 10%;
            min-height: 85vh;
            align-items: center;
        }

        .hero-text h1 {
            font-size: 4rem;
            line-height: 1.1;
            margin-bottom: 20px;
            font-weight: 800;
            letter-spacing: -2px;
        }

        .hero-text p {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        /* Info Chips (Informatif) */
        .chips { display: flex; gap: 10px; margin-bottom: 20px; }
        .chip {
            padding: 6px 15px;
            background: #E0EBFF;
            color: var(--primary);
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        /* Glassmorphism Card */
        .auth-card {
            background: #FFFFFF;
            padding: 40px;
            border-radius: 24px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.05);
            border: 1px solid rgba(255,255,255,0.8);
            transition: var(--transition);
        }

        .tabs {
            display: flex;
            background: #F0F2F5;
            padding: 5px;
            border-radius: 12px;
            margin-bottom: 30px;
        }

        .tab-btn {
            flex: 1;
            padding: 10px;
            border: none;
            background: none;
            cursor: pointer;
            font-family: 'Inter';
            font-weight: 600;
            border-radius: 8px;
            transition: var(--transition);
        }

        .tab-active { background: white; color: var(--primary); box-shadow: 0 4px 10px rgba(0,0,0,0.05); }

        /* Form Styling */
        .input-group { margin-bottom: 18px; }
        .input-group label { display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 8px; color: #555; }
        .input-group input, select {
            width: 100%;
            padding: 14px;
            border: 1.5px solid #EAEAEA;
            border-radius: 12px;
            transition: var(--transition);
            font-size: 0.95rem;
        }

        .input-group input:focus { border-color: var(--primary); outline: none; background: #F9FBFF; }

        .btn-action {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: var(--transition);
            margin-top: 10px;
        }

        .btn-login { background: var(--primary); color: white; }
        .btn-reg { background: var(--secondary); color: #000; }
        
        .btn-action:hover { opacity: 0.9; transform: scale(1.02); }

        /* Smooth Hide/Show */
        .fade-content { animation: fadeIn 0.5s; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .hidden { display: none; }
    </style>
</head>
<body>

<div class="bg-decor"></div>

<nav>
    <div class="logo">si tukang.</div>
    <div style="font-size: 0.9rem; font-weight: 500; color: #888;">Area Serang & Sekitarnya</div>
</nav>

<main class="hero-container">
    <div class="hero-text animate__animated animate__fadeInLeft">
        <div class="chips">
            <span class="chip">Real-time Dashboard</span>
            <span class="chip">E-Wallet Ready</span>
            <span class="chip">Verified Artisans</span>
        </div>
        <h1>Panggil Tukang<br><span style="color: var(--primary);">Semudah</span> Chatting.</h1>
        <p>Platform PKM-K yang menghubungkan Anda dengan teknisi AC, bangunan, dan elektronik profesional di Serang dengan sistem bagi hasil transparan.</p>
        
        <div style="display: flex; gap: 30px; margin-top: 50px;">
            <div><h3 style="color: var(--primary);">0%</h3><p style="font-size: 0.8rem;">Admin Fee User</p></div>
            <div style="width: 1px; background: #DDD;"></div>
            <div><h3 style="color: var(--primary);">15+</h3><p style="font-size: 0.8rem;">Kategori Jasa</p></div>
            <div style="width: 1px; background: #DDD;"></div>
            <div><h3 style="color: var(--primary);">Realtime</h3><p style="font-size: 0.8rem;">Update Status</p></div>
        </div>
    </div>

    <div class="auth-card animate__animated animate__fadeInRight">
        <div class="tabs">
            <button class="tab-btn tab-active" id="btnTabLogin" onclick="toggleAuth('login')">Masuk</button>
            <button class="tab-btn" id="btnTabReg" onclick="toggleAuth('reg')">Daftar</button>
        </div>

        <form id="loginForm" class="fade-content" action="proses_auth.php" method="POST">
            <input type="hidden" name="aksi" value="login">
            <div class="input-group">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="nama@email.com" required>
            </div>
            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn-action btn-login">Masuk ke Dashboard</button>
        </form>

        <form id="regForm" class="hidden" action="proses_auth.php" method="POST">
            <input type="hidden" name="aksi" value="daftar">
            <div class="input-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" placeholder="Dana Alpaiji" required>
            </div>
            <div class="input-group">
                <label>Email & No. HP</label>
                <div style="display: flex; gap: 10px;">
                    <input type="email" name="email" placeholder="email@com" style="flex: 2;" required>
                    <input type="text" name="no_hp" placeholder="08..." style="flex: 1;" required>
                </div>
            </div>
            <div class="input-group">
                <label>Bergabung Sebagai</label>
                <select name="role">
                    <option value="user">Pengguna (Butuh Jasa)</option>
                    <option value="tukang">Tukang (Pemberi Jasa)</option>
                </select>
            </div>
            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Min. 6 Karakter" required>
            </div>
            <button type="submit" class="btn-action btn-reg">Buat Akun Sekarang</button>
        </form>
    </div>
</main>

<script>
    function toggleAuth(type) {
        const loginForm = document.getElementById('loginForm');
        const regForm = document.getElementById('regForm');
        const btnL = document.getElementById('btnTabLogin');
        const btnR = document.getElementById('btnTabReg');

        if(type === 'login') {
            loginForm.classList.remove('hidden');
            loginForm.classList.add('fade-content');
            regForm.classList.add('hidden');
            btnL.classList.add('tab-active');
            btnR.classList.remove('tab-active');
        } else {
            regForm.classList.remove('hidden');
            regForm.classList.add('fade-content');
            loginForm.classList.add('hidden');
            btnR.classList.add('tab-active');
            btnL.classList.remove('tab-active');
        }
    }

    // SweetAlert Handling
    <?php if(isset($_GET['status'])): ?>
        Swal.fire({
            icon: '<?php echo $_GET['icon']; ?>',
            title: '<?php echo $_GET['pesan']; ?>',
            confirmButtonColor: '#0056FF',
            showClass: { popup: 'animate__animated animate__fadeInDown' },
            hideClass: { popup: 'animate__animated animate__fadeOutUp' }
        });
    <?php endif; ?>
</script>

</body>
</html>