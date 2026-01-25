<?php include 'header.php'; ?>

<main>
    <section class="hero-wrapper">
        <div class="hero-teks">
            <h2>SELAMAT DATANG DI MULTIVERSE SAYA</h2>
            <h1>MAHASISWA<br>EKONOMI SYARIAH</h1>
            <p style="font-size: 1.3rem; margin-bottom: 40px; font-style: italic;">
                "Menjunjung tinggi nilai integritas syariah dalam setiap langkah profesionalisme."
            </p>
            
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 40px;">
                <div style="border-left: 3px solid var(--merah-spidey); padding-left: 15px;">
                    <h3 style="font-family: 'Bangers'; font-size: 2rem;">3.97</h3>
                    <p>IPK Kumulatif</p>
                </div>
                <div style="border-left: 3px solid var(--merah-spidey); padding-left: 15px;">
                    <h3 style="font-family: 'Bangers'; font-size: 2rem;">2+ Thn</h3>
                    <p>Pengalaman Kerja</p>
                </div>
                <div style="border-left: 3px solid var(--merah-spidey); padding-left: 15px;">
                    <h3 style="font-family: 'Bangers'; font-size: 2rem;">Staff</h3>
                    <p>Terbaik HMJ</p>
                </div>
            </div>

            <a href="about.php" class="tombol-spidey">JELAJAHI PROFIL</a>
        </div>

        <div class="hero-foto">
            <div class="foto-bingkai" id="fotoProfil">
                <img src="foto.png" alt="Dana Alpaiji" id="imgElement">
            </div>
            <p style="position: absolute; bottom: -40px; font-family: 'Bangers'; color: var(--merah-spidey);">Klik foto untuk efek Spidey-Sense!</p>
        </div>
    </section>
</main>

<script>
    // Logika Hitam Putih Pas Klik
    const foto = document.getElementById('fotoProfil');
    const img = document.getElementById('imgElement');
    
    foto.addEventListener('mousedown', () => {
        img.classList.add('hitam-putih');
        Swal.fire({
            title: 'SPIDEY-SENSE AKTIF!',
            text: 'Dana Alpaiji siap menghadapi tantangan profesional.',
            confirmButtonColor: '#DE1A1A'
        });
    });

    foto.addEventListener('mouseup', () => {
        setTimeout(() => {
            img.classList.remove('hitam-putih');
        }, 500);
    });
</script>

<?php include 'footer.php'; ?>