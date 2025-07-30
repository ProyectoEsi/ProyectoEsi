
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth'
            });
        }
    });
});


window.addEventListener('scroll', function() {
    const header = document.querySelector('.header');
    if (window.scrollY > 100) {
        header.style.background = 'rgba(28, 28, 28, 0.98)';
    } else {
        header.style.background = 'rgba(28, 28, 28, 0.95)';
    }
});


document.querySelector('.contact-form').addEventListener('submit', function(e) {
    e.preventDefault();
    alert('¡Gracias por tu mensaje! Te contactaremos pronto.');
});





document.querySelectorAll('.news-button').forEach(button => {
    button.addEventListener('click', function() {
        alert('Leyendo más sobre: ' + this.closest('.news-card').querySelector('.news-title').textContent);
    });
});


document.querySelector('.join-button').addEventListener('click', function() {
    alert('¡Gracias por tu interés! Te contactaremos para más información sobre cómo unirte.');
});


document.querySelector('.read-more').addEventListener('click', function() {
    alert('Más información sobre la cooperativa próximamente...');
});