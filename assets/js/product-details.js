document.addEventListener('DOMContentLoaded', () => {
    const descriptionContent = document.getElementById('description-content');
    const toggleBtn = document.getElementById('toggle-description-btn');
    const maxLines = 15;

    const originalHeight = descriptionContent.scrollHeight;
    const lineHeight = parseInt(window.getComputedStyle(descriptionContent).lineHeight, 10);
    const maxHeight = lineHeight * maxLines;

    const thumbnailContainer = document.querySelector('.product-image-strip-container');
    const thumbnails = document.querySelectorAll('.thumbnail');
    const mainImage = document.getElementById('mainImage');
    const productImageContainer = document.querySelector('.product-image');

    if (originalHeight > maxHeight) {
        toggleBtn.textContent = toggleBtn.getAttribute('data-button-more');
        descriptionContent.style.maxHeight = `${maxHeight}px`;
        descriptionContent.style.overflow = 'hidden';

        toggleBtn.addEventListener('click', () => {
            if (descriptionContent.style.maxHeight === `${maxHeight}px`) {
                descriptionContent.style.maxHeight = `${originalHeight}px`;
                toggleBtn.textContent = toggleBtn.getAttribute('data-button-less');
            } else {
                descriptionContent.style.maxHeight = `${maxHeight}px`;
                toggleBtn.textContent = toggleBtn.getAttribute('data-button-more');
            }
        });
    } else {
        toggleBtn.style.display = 'none';
    }

    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', function() {
            const newSrc = this.getAttribute('data-large-src');
            mainImage.setAttribute('src', newSrc);

            const prev = thumbnailContainer.querySelector('.active');
            prev.classList.remove('active');
            thumbnail.classList.add('active');
        });
    });

// Obsługa przesuwania myszką na komputerze
    productImageContainer.addEventListener('mousemove', (e) => {
        handleImageMove(e.pageX, e.pageY, productImageContainer);
        mainImage.style.transform = 'scale(2)';
    });

    productImageContainer.addEventListener('mouseleave', () => {
        mainImage.style.transform = 'scale(1)';
        mainImage.style.transformOrigin = 'center center';
    });


    productImageContainer.addEventListener('touchstart', (e) => {
        const touch = e.touches[0];
        handleImageMove(touch.pageX, touch.pageY, productImageContainer);
        mainImage.style.transform = 'scale(2)';
    });

    productImageContainer.addEventListener('touchmove', (e) => {
        e.preventDefault();
        const touch = e.touches[0];
        handleImageMove(touch.pageX, touch.pageY, productImageContainer);
    });

    productImageContainer.addEventListener('touchend', () => {
        mainImage.style.transform = 'scale(1)';
        mainImage.style.transformOrigin = 'center center';
    });

    function handleImageMove(pageX, pageY, container) {
        const { left, top, width, height } = container.getBoundingClientRect();
        const x = (pageX - left) / width * 100;
        const y = (pageY - top) / height * 100;

        mainImage.style.transformOrigin = `${x}% ${y}%`;
    }
});
