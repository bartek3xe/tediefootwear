document.addEventListener('DOMContentLoaded', () => {
    const descriptionContent = document.getElementById('description-content');
    const toggleBtn = document.getElementById('toggle-description-btn');
    const maxLines = 15;

    const originalHeight = descriptionContent.scrollHeight;
    const lineHeight = parseInt(window.getComputedStyle(descriptionContent).lineHeight, 10);
    const maxHeight = lineHeight * maxLines;

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
});
