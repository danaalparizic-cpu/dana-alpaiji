/* -------------------------------------------------------------------------- */
/* CORE ENGINE v17.0 - DANA ALPAIJI (FINAL SUPREME EDITION)                   */
/* -------------------------------------------------------------------------- */
document.addEventListener("DOMContentLoaded", () => {
    
    const hub = {
        loader: document.getElementById('loader'),
        fill: document.querySelector('.load-fill'),
        cursor: document.querySelector('.cursor-sword'),
        header: document.querySelector('.header'),
        revealItems: document.querySelectorAll('.reveal-left, .reveal-right'),
        parallax: document.querySelectorAll('.parallax-mouse'),
        sections: document.querySelectorAll('section'),
        navLinks: document.querySelectorAll('.nav-link')
    };

    /* 01. LOADER SYSTEM */
    let p = 0;
    const interval = setInterval(() => {
        p += Math.floor(Math.random() * 15) + 3;
        if(p >= 100) {
            p = 100;
            clearInterval(interval);
            setTimeout(() => {
                hub.loader.style.transform = "translateX(100%)";
                setTimeout(() => { 
                    hub.loader.style.display = "none";
                    document.querySelectorAll('#home .hero-title, #home .hero-desc').forEach(el => el.classList.add('active'));
                }, 800);
            }, 500);
        }
        hub.fill.style.width = p + "%";
    }, 130);

    /* 02. MOUSE TRACKING (3D PARALLAX) */
    document.addEventListener('mousemove', (e) => {
        const { clientX: x, clientY: y } = e;
        
        if(hub.cursor) {
            hub.cursor.style.left = x + 'px';
            hub.cursor.style.top = y + 'px';
        }

        hub.parallax.forEach(el => {
            const speed = el.getAttribute('data-speed') || 0.05;
            const xMove = (window.innerWidth / 2 - x) * speed;
            const yMove = (window.innerHeight / 2 - y) * speed;
            el.style.transform = `translate(${xMove}px, ${yMove}px)`;
        });
    });

    /* 03. SCROLL LOGIC (RUMBLE & AUTO NAV) */
    let lastST = 0;
    window.addEventListener('scroll', () => {
        const st = window.pageYOffset;

        // Rumble on fast scroll
        if (Math.abs(st - lastST) > 85) {
            document.body.classList.add('shake-active');
            setTimeout(() => document.body.classList.remove('shake-active'), 120);
        }
        lastST = st;

        // Header Style
        if(st > 60) hub.header.classList.add('scrolled');
        else hub.header.classList.remove('scrolled');

        // Reveal effect
        hub.revealItems.forEach(item => {
            if (item.getBoundingClientRect().top < window.innerHeight - 100) {
                item.classList.add('active');
            }
        });

        // Auto Navigation Active
        let id = "";
        hub.sections.forEach(s => {
            if (st >= (s.offsetTop - 350)) id = s.getAttribute('id');
        });
        hub.navLinks.forEach(l => {
            l.classList.remove('aktif');
            if (l.getAttribute('href').includes(id)) l.classList.add('aktif');
        });
    });

    /* 04. POPUPS */
    window.accessData = () => Swal.fire({ 
        title: 'DATA ACCESSED', 
        text: 'Mengakses intelijen Dana Alpaiji...', 
        icon: 'info', 
        background: '#000', 
        color: '#fff', 
        confirmButtonColor: '#007bff' 
    });

    window.verifyLog = () => Swal.fire({ 
        title: 'VERIFIED', 
        text: 'Data IPK 3.97 tervalidasi secara sistem.', 
        icon: 'success', 
        background: '#000', 
        color: '#fff', 
        confirmButtonColor: '#ff0000' 
    });
});