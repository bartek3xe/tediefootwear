document.addEventListener('DOMContentLoaded', () => {
    const questionContainers = document.querySelectorAll('.question-container');

    questionContainers.forEach((container) => {
        container.addEventListener('click', () => {
            const paragraph = container.querySelector('p');

            paragraph.classList.toggle('active');
        });
    });
});
