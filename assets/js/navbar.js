document.addEventListener('DOMContentLoaded', () => {
    const productsDropdownBtn = document.querySelector('.dropdown-btn');
    const productsDropdownContent = document.querySelector('.dropdown-content');
    const navLinks = document.querySelectorAll('.navigation a');
    const languageBtn = document.querySelector('.nav-lang button');
    const languageDropdown = document.querySelector('.flag-dropdown');
    const navigation = document.querySelector('#page-navbar .navigation');
    const mobileNavBtn = document.querySelector('.mobile-menu-btn');
    const body = document.querySelector('body');
    const overlay = document.querySelector('.navbar-overlay');

    productsDropdownBtn.addEventListener('click', (e) => {
        if (window.innerWidth >= 1000) {
            e.stopPropagation();
            productsDropdownContent.classList.toggle('active');
        }
    });

    document.addEventListener('click', (e) => {
        if (!languageDropdown.contains(e.target) && !languageBtn.contains(e.target)) {
            languageDropdown.classList.remove('active');
        }
    });

    const currentURL = window.location.href;
    navLinks.forEach((link) => {
        if (link.href === currentURL) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });

    languageBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        languageDropdown.classList.toggle('active');
    });

    mobileNavBtn.addEventListener('click', () => {
        navigation.classList.toggle('active');
        mobileNavBtn.classList.toggle('active');
        overlay.classList.toggle('active');
        body.classList.toggle('no-scroll');
    });

    document.addEventListener('click', (e) => {
        if (!navigation.contains(e.target) && !mobileNavBtn.contains(e.target)) {
            navigation.classList.remove('active');
            mobileNavBtn.classList.remove('active');
            overlay.classList.remove('active');
            body.classList.remove('no-scroll');
        }
    });

    navigation.addEventListener('click', (e) => {
        e.stopPropagation();
    });
});
