// Smooth scrolling for anchor links
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Handle fixed navbar padding
    const navbar = document.querySelector('.navbar.fixed-top');
    if (navbar) {
        document.body.style.paddingTop = navbar.offsetHeight + 'px';
    }

    // Handle transparent navbar color change on scroll
    const transparentNavbar = document.querySelector('.navbar.bg-transparent');
    if (transparentNavbar) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                transparentNavbar.style.backgroundColor = getComputedStyle(document.documentElement)
                    .getPropertyValue('--bs-body-bg');
            } else {
                transparentNavbar.style.backgroundColor = 'transparent';
            }
        });
    }

    // Initialize Bootstrap tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Add scroll animations
    const animatedElements = document.querySelectorAll('.animate-on-scroll');
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate__animated', entry.target.dataset.animation);
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    animatedElements.forEach(el => observer.observe(el));
});
