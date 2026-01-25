<footer>
        <h2 style="font-family: 'Bangers'; font-size: 2.5rem; color: var(--merah-spidey);">DANA ALPAIJI</h2>
        <p style="max-width: 600px; margin: 20px auto; opacity: 0.8;">
            Mahasiswa Ekonomi Syariah yang berfokus pada manajemen organisasi dan kepemimpinan berintegritas.
        </p>
        
        <div style="margin: 30px 0; display: flex; justify-content: center; gap: 20px;">
            <a href="#" style="color: white; text-decoration: none;">Instagram</a>
            <a href="#" style="color: white; text-decoration: none;">LinkedIn</a>
            <a href="#" style="color: white; text-decoration: none;">WhatsApp</a>
        </div>

        <p style="font-size: 0.8rem; opacity: 0.5; margin-top: 40px;">
            &copy; 2026 Dana Alpaiji Portfolio. Dibuat dengan Konsep Miles Morales Spider-Man.
        </p>
    </footer>

    <script>
        const links = document.querySelectorAll('a, button, .foto-bingkai');
        links.forEach(l => {
            l.addEventListener('mouseenter', () => {
                kursor.style.transform = 'scale(2.5)';
                kursor.style.backgroundColor = 'rgba(222, 26, 26, 0.2)';
            });
            l.addEventListener('mouseleave', () => {
                kursor.style.transform = 'scale(1)';
                kursor.style.backgroundColor = 'transparent';
            });
        });
    </script>
</body>
</html>