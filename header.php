<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dana Alpaiji | Portofolio Profesional</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<div id="cursor-kustom"></div>

<div id="preloader">
    <div class="web-loading"></div>
    <h1 style="font-family: 'Bangers'; color: white; margin-left: 20px;">DANA ALPAIJI...</h1>
</div>

<nav>
    <a href="index.php" class="logo-brand">DANA<span>ALPAIJI</span></a>
    <ul class="nav-menu">
        <li><a href="index.php">Beranda</a></li>
        <li><a href="about.php">Identitas</a></li>
        <li><a href="experience.php">Pengalaman</a></li>
        <li><a href="skills.php">Keahlian</a></li>
        <li><a href="contact.php">Kontak</a></li>
    </ul>
</nav>

<script>
    // Gerakan Kursor Kustom
    const kursor = document.getElementById('cursor-kustom');
    document.addEventListener('mousemove', e => {
        kursor.style.left = e.clientX + 'px';
        kursor.style.top = e.clientY + 'px';
    });

    // Menghilangkan Preloader
    window.addEventListener('load', () => {
        setTimeout(() => {
            document.getElementById('preloader').style.display = 'none';
        }, 1500);
    });

    // Efek Komik Otomatis
    function buatTeksKomik() {
        const kata = ['THWIP!', 'POW!', 'ZAP!', 'BOOM!', 'SLURP!'];
        const el = document.createElement('div');
        el.className = 'efek-komik';
        el.innerText = kata[Math.floor(Math.random() * kata.length)];
        el.style.left = Math.random() * 80 + 10 + 'vw';
        el.style.top = Math.random() * 80 + 10 + 'vh';
        document.body.appendChild(el);
        setTimeout(() => el.remove(), 1000);
    }
    setInterval(buatTeksKomik, 4000);
</script>