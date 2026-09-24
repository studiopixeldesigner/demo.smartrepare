// Gestion du clic sur le logo du header
const logoLink = document.querySelector('.header__wrapper a[href="index.html"]');
if (logoLink) {
    logoLink.addEventListener('click', (e) => {
        const isHomePage = window.location.pathname.endsWith('index.html') || window.location.pathname === '/';
        
        if (isHomePage) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
            // Supprime l'éventuel #services de l'URL sans recharger
            history.pushState('', document.title, window.location.pathname);
        }
    });
}

// Désactive la restauration automatique du scroll par le navigateur
if ('scrollRestoration' in history) {
  history.scrollRestoration = 'manual';
}

// Force le retour en haut au chargement de la page
window.addEventListener('DOMContentLoaded', () => {
  window.scrollTo(0, 0);
});

const burger = document.getElementById('burger-menu');
    const nav = document.getElementById('nav-menu');

    burger.addEventListener('click', () => {
        burger.classList.toggle('active');
        nav.classList.toggle('active');
    });

    document.addEventListener('DOMContentLoaded', () => {
    const track = document.getElementById('services-track');
    const cards = document.querySelectorAll('.service-card');
    const btnLeft = document.getElementById('slide-left');
    const btnRight = document.getElementById('slide-right');

    if (!track || cards.length === 0) return;

    let currentIndex = 0;

    // Amène la carte ciblée directement au centre
    function centerCard(index) {
    currentIndex = (index + cards.length) % cards.length;
    const card = cards[currentIndex];
    
    // Calcule la position pour centrer la carte dans la piste sans toucher au scroll vertical de la page
    const targetScroll = card.offsetLeft - (track.clientWidth / 2) + (card.clientWidth / 2);
    
    track.scrollTo({
        left: targetScroll,
        behavior: 'smooth'
    });
}

    // Détermine en temps réel quelle carte passe par le centre de l'écran
    function updateActiveOnScroll() {
        const trackRect = track.getBoundingClientRect();
        const trackCenter = trackRect.left + trackRect.width / 2;

        let closestIndex = 0;
        let minDistance = Infinity;

        cards.forEach((card, index) => {
            const cardRect = card.getBoundingClientRect();
            const cardCenter = cardRect.left + cardRect.width / 2;
            const distance = Math.abs(trackCenter - cardCenter);

            if (distance < minDistance) {
                minDistance = distance;
                closestIndex = index;
            }
        });

        currentIndex = closestIndex;

        cards.forEach((card, i) => {
            card.classList.toggle('is-active', i === closestIndex);
        });
    }

    // Événements sur les flèches
    btnRight?.addEventListener('click', () => centerCard(currentIndex + 1));
    btnLeft?.addEventListener('click', () => centerCard(currentIndex - 1));

    // Mettre à jour la classe active pendant le défilement (manuel ou automatique)
    track.addEventListener('scroll', updateActiveOnScroll, { passive: true });

    // Placement initial
    track.scrollLeft = 0;
    updateActiveOnScroll();
});

// Empêcher le clic droit sur tout le site
document.addEventListener('contextmenu', function(e) {
    e.preventDefault();
});