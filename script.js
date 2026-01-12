document.addEventListener("DOMContentLoaded", () => {
    const loader = document.getElementById('loader');
    
    // SHATTER TRANSITION
    setTimeout(() => {
        loader.classList.add('shattering');
        setTimeout(() => {
            loader.style.transition = "0.7s cubic-bezier(1, 0, 0, 1)";
            loader.style.transform = "scale(10)";
            loader.style.opacity = "0";
            setTimeout(() => { loader.style.display = 'none'; }, 700);
        }, 500);
    }, 2500);

    // REVEAL ON SCROLL
    const reveals = document.querySelectorAll('.reveal');
    const scrollHandler = () => {
        reveals.forEach(el => {
            const top = el.getBoundingClientRect().top;
            if (top < window.innerHeight - 100) {
                el.classList.add('active');
            }
        });
    };
    window.addEventListener('scroll', scrollHandler);
    scrollHandler();

    // AUTO ACTIVE NAV
    const sections = document.querySelectorAll('section');
    const navLinks = document.querySelectorAll('.navbar a');
    window.onscroll = () => {
        let current = "";
        sections.forEach(sec => {
            if (window.scrollY >= (sec.offsetTop - 150)) {
                current = sec.getAttribute('id');
            }
        });
        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href').includes(current)) {
                link.classList.add('active');
            }
        });
    };
});