document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('.product-categories-container');
    let isDown = false;
    let startX;
    let scrollLeft;
    let isDragging = false;

    container.addEventListener('mousedown', (e) => {
        if (e.target.tagName === 'A') {
            e.preventDefault();
        }
        isDown = true;
        isDragging = false;
        container.classList.add('active');
        startX = e.pageX - container.offsetLeft;
        scrollLeft = container.scrollLeft;
        container.style.cursor = 'grab';
    });

    container.addEventListener('mouseleave', () => {
        if (isDown) {
            isDown = false;
            container.classList.remove('active');
            container.style.cursor = 'grab';
        }
    });

    container.addEventListener('mouseup', (e) => {
        if (isDown) {
            isDown = false;
            container.classList.remove('active');
            container.style.cursor = 'grab';
            if (!isDragging && e.target.tagName === 'A') {
                window.location.href = e.target.href;
            }
        }
    });

    container.addEventListener('mousemove', (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.pageX - container.offsetLeft;
        const walk = (x - startX);
        if (Math.abs(walk) > 5) {
            isDragging = true;
            container.scrollLeft = scrollLeft - walk;
        }
    });

    container.addEventListener('click', (e) => {
        if (isDragging) {
            e.preventDefault();
        }
    });

    let touchStartX = 0;
    let touchScrollLeft = 0;
    let touchDragging = false;

    container.addEventListener('touchstart', (e) => {
        if (e.target.tagName === 'A') {
            e.preventDefault();
        }
        touchStartX = e.touches[0].pageX - container.offsetLeft;
        touchScrollLeft = container.scrollLeft;
        touchDragging = false;
    });

    container.addEventListener('touchmove', (e) => {
        const x = e.touches[0].pageX - container.offsetLeft;
        const walk = (x - touchStartX);
        if (Math.abs(walk) > 5) {
            touchDragging = true;
            container.scrollLeft = touchScrollLeft - walk;
        }
    });

    container.addEventListener('touchend', (e) => {
        if (!touchDragging && e.target.tagName === 'A') {
            window.location.href = e.target.href;
        }
    });

    container.addEventListener('click', (e) => {
        if (touchDragging) {
            e.preventDefault();
        }
    });
});
