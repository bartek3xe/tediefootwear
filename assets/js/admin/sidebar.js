document.addEventListener('DOMContentLoaded', () => {
    const subMenuLinks = document.querySelectorAll('#leftside-navigation .sub-menu > a');

    subMenuLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            const nextSubMenu = this.nextElementSibling;

            if (nextSubMenu && nextSubMenu.nodeType === 1) {
                document.querySelectorAll('#leftside-navigation ul ul').forEach(subMenu => {
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
