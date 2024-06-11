document.addEventListener('DOMContentLoaded', () => {
    const navItems = document.querySelectorAll('.nav-item');
    const cardItems = document.querySelectorAll('.card-element');
    const navLinks = document.querySelectorAll('.nav-link');
    const navLinksMobile = document.querySelectorAll('.nav-link-mobile');
    const navLinksFooter = document.querySelectorAll('.nav-link-footer');
    const navMobile = document.querySelector('.mobile-nav');
    let isClickScrolling = false;

    const setActiveLink = (dataBox) => {
        navLinks.forEach(navLink => {
            navLink.classList.toggle('active', navLink.getAttribute('data-box') === dataBox);
        });
    };

    const scrollToSection = (dataBox, alignToTop = false) => {
        isClickScrolling = true;
        cardItems.forEach(cardItem => {
            if (cardItem.getAttribute('data-box') === dataBox) {
                const offsetTop = cardItem.offsetTop === 100
                    ? cardItem.offsetTop - 100
                    : cardItem.offsetTop - 75;
                const windowHeight = window.innerHeight;
                const targetPosition = alignToTop
                    ? offsetTop
                    : offsetTop - (windowHeight / 2) + (cardItem.offsetHeight / 2);

                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });

                const observer = new IntersectionObserver((entries) => {
                    if (entries[0].isIntersecting) {
                        setActiveLink(dataBox);
                        observer.disconnect();
                        isClickScrolling = false;
                    }
                }, { threshold: 0.5 });
                observer.observe(cardItem);
            }
        });
    };

    const handleNavItemClick = (navItem, alignToTop = false) => {
        const navDataBox = navItem.getAttribute('data-box');
        scrollToSection(navDataBox, alignToTop);
    };

    navItems.forEach(navItem => {
        navItem.addEventListener('click', (event) => handleNavItemClick(event.currentTarget));
    });

    navLinksMobile.forEach(navLinkMobile => {
        navLinkMobile.addEventListener('click', (event) => {
            const navMobileDataBox = event.currentTarget.getAttribute('data-box');
            scrollToSection(navMobileDataBox, true);
            navMobile.classList.remove('is-active');
        });
    });

    navLinksFooter.forEach(navLinkFooter => {
        navLinkFooter.addEventListener('click', (event) => {
            const navFooterDataBox = event.currentTarget.getAttribute('data-box');
            scrollToSection(navFooterDataBox, true);
        });
    });

    const updateNavLinksOnScroll = () => {
        if (isClickScrolling) return;
        const top = window.scrollY;
        const windowHeight = window.innerHeight;
        const bottom = top + windowHeight;
        let currentSection = null;

        cardItems.forEach((cardItem, index) => {
            const cardDataBox = cardItem.getAttribute('data-box');
            const offsetTop = cardItem.offsetTop;
            const offsetBottom = offsetTop + cardItem.offsetHeight;
            const isLastSection = index === cardItems.length - 1;

            if ((top >= offsetTop - windowHeight / 2 && top < offsetBottom - windowHeight / 2) ||
                (isLastSection && (bottom >= offsetBottom || top >= offsetTop))) {
                currentSection = cardDataBox;
            }
        });

        if (!currentSection) {
            currentSection = cardItems[0].getAttribute('data-box');
        }

        setActiveLink(currentSection);
    };

    window.addEventListener('scroll', updateNavLinksOnScroll);

    updateNavLinksOnScroll();
});
