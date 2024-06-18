document.addEventListener('DOMContentLoaded', () => {
    const productsDropdownBtn = document.querySelector('.dropdown-btn');
    const productsDropdownContent = document.querySelector('.dropdown-content');
    const navLinks = document.querySelectorAll('.navigation a');
    const languageBtn = document.querySelector('.nav-lang button');
    const languageDropdown = document.querySelector('.flag-dropdown');

    productsDropdownBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        productsDropdownContent.classList.toggle('active');
    });

    document.addEventListener('click', (e) => {
        if (!languageDropdown.contains(e.target) && !languageBtn.contains(e.target)) {
            languageDropdown.classList.remove('active');
        }
    });

    const currentURL = window.location.href;
    navLinks.forEach(link => {
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
});
