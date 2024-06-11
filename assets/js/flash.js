document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        let alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            alert.classList.remove('show');
            setTimeout(function() {
                alert.remove();
            }, 500);
        });
    }, 20000);

    document.querySelectorAll('.btn-close').forEach(function(button) {
        button.addEventListener('click', function(event) {
            let alert = event.target.closest('.alert');
            alert.classList.remove('show');
            setTimeout(function() {
                alert.remove();
            }, 500);n
        });
    });
});
