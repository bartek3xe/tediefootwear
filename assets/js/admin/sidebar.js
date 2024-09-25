document.addEventListener('DOMContentLoaded', () => {
    const subMenuLinks = document.querySelectorAll('#leftside-navigation .sub-menu > a');

    subMenuLinks.forEach((link) => {
        link.addEventListener('click', (e) => {
            const nextSubMenu = link.nextElementSibling;

            if (nextSubMenu && nextSubMenu.nodeType === 1) {
                document.querySelectorAll('#leftside-navigation ul ul').forEach((menu) => {
                    const subMenu = menu;

                    if (subMenu !== nextSubMenu) {
                        subMenu.style.display = 'none';
                    }
                });

                if (window.getComputedStyle(nextSubMenu).display === 'none') {
                    nextSubMenu.style.display = 'block';
                } else {
                    nextSubMenu.style.display = 'none';
                }

                e.stopPropagation();
            }
        });
    });
});
