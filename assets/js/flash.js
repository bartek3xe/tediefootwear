document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach((alert) => {
            alert.classList.remove('show');
            setTimeout(() => {
                alert.remove();
            }, 500);
        });
    }, 10000);

    document.querySelectorAll('.btn-close').forEach((button) => {
        button.addEventListener('click', (event) => {
            const alert = event.target.closest('.alert');
            alert.classList.remove('show');
            setTimeout(() => {
                alert.remove();
            }, 500);
        });
    });
});
