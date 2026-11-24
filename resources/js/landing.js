const navigation = document.querySelector('#landing-nav');
const menuButton = document.querySelector('[aria-controls="landing-nav"]');

if (navigation && menuButton) {
    const links = [...navigation.querySelectorAll('a')];

    function setMenu(open) {
        navigation.dataset.open = String(open);
        menuButton.setAttribute('aria-expanded', String(open));
        menuButton.setAttribute('aria-label', open ? 'Tutup menu navigasi' : 'Buka menu navigasi');
    }

    function updateCurrentSection() {
        const aliases = { '#guru': '#fasilitas', '#prestasi': '#berita' };
        const hash = window.location.hash || '#beranda';
        const section = aliases[hash] || hash;

        for (const link of links) {
            if (link.hash === section) {
                link.setAttribute('aria-current', 'location');
            } else {
                link.removeAttribute('aria-current');
            }
        }
    }

    menuButton.hidden = false;
    setMenu(false);
    if (navigation.hasAttribute('data-section-nav')) {
        updateCurrentSection();
        window.addEventListener('hashchange', updateCurrentSection);
    }

    menuButton.addEventListener('click', () => setMenu(navigation.dataset.open !== 'true'));
    navigation.addEventListener('click', (event) => {
        if (event.target.closest('a')) {
            setMenu(false);
        }
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && navigation.dataset.open === 'true') {
            setMenu(false);
            menuButton.focus();
        }
    });
}

const detail = document.querySelector('#landing-detail');

if (detail) {
    const title = detail.querySelector('#landing-detail-title');
    const description = detail.querySelector('[data-landing-detail-description]');

    for (const trigger of document.querySelectorAll('[data-landing-detail]')) {
        trigger.addEventListener('click', () => {
            title.textContent = trigger.dataset.title;
            description.textContent = trigger.dataset.description;
            detail.showModal();
        });
    }
}
