<?php include 'header.php'; ?>

<main>
    <section style="padding: 100px 10%;">
        <h2 class="judul-seksi">Kirim Sinyal (Kontak)</h2>
        
        <div class="hero-wrapper" style="min-height: auto; padding: 0; gap: 40px; align-items: stretch;">
            <div class="hero-teks" style="flex: 1;">
                <div class="kartu" style="height: 100%;">
                    <h3 style="font-family: 'Bangers'; font-size: 2rem; color: var(--merah-spidey); margin-bottom: 20px;">KONTAK LANGSUNG</h3>
                    <p style="margin-bottom: 15px;"><strong>📍 Domisili:</strong> Serang, Banten (Kampus UNTIRTA)</p>
                    <p style="margin-bottom: 15px;"><strong>📞 Telepon/WA:</strong> 0838-7106-8503</p>
                    <p style="margin-bottom: 15px;"><strong>📧 Email:</strong> danaalparizic@gmail.com</p>
                    <p style="margin-bottom: 30px;"><strong>🔗 LinkedIn:</strong> linkedin.com/in/danaalpaiji</p>
                    
                    <div style="background: var(--merah-spidey); padding: 20px; text-align: center;">
                        <h4 style="font-family: 'Bangers';">Tersedia untuk Kolaborasi!</h4>
                    </div>
                </div>
            </div>

            <div class="hero-teks" style="flex: 1.5;">
                <div class="kartu">
                    <form id="formSpidey">
                        <div style="margin-bottom: 20px;">
                            <label style="display: block; margin-bottom: 5px; font-family: 'Bangers';">NAMA LENGKAP</label>
                            <input type="text" id="nama" style="width: 100%; padding: 12px; background: #0A2540; border: 1px solid var(--merah-spidey); color: white; outline: none;" required>
                        </div>
                        <div style="margin-bottom: 20px;">
                            <label style="display: block; margin-bottom: 5px; font-family: 'Bangers';">EMAIL AKTIF</label>
                            <input type="email" id="email" style="width: 100%; padding: 12px; background: #0A2540; border: 1px solid var(--merah-spidey); color: white; outline: none;" required>
                        </div>
                        <div style="margin-bottom: 20px;">
                            <label style="display: block; margin-bottom: 5px; font-family: 'Bangers';">PESAN ANDA</label>
                            <textarea id="pesan" rows="5" style="width: 100%; padding: 12px; background: #0A2540; border: 1px solid var(--merah-spidey); color: white; outline: none;" required></textarea>
                        </div>
                        <button type="submit" class="tombol-spidey" style="width: 100%; cursor: none;">KIRIM PESAN KE DANA</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
    document.getElementById('formSpidey').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Animasi SweetAlert Miles Morales
        Swal.fire({
            title: 'THWIP! PESAN TERKIRIM',
            text: 'Terima kasih ' + document.getElementById('nama').value + ', Dana akan segera membalas sinyal Anda!',
            icon: 'success',
            confirmButtonText: 'MANTAP',
            confirmButtonColor: '#DE1A1A',
            background: '#051622',
            color: '#ffffff'
        });
        
        this.reset();
    });
</script>

<?php include 'footer.php'; ?>