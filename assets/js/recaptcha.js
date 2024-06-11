function onReCaptchaSuccess(token) {
    document.querySelector('input[name="contact[recaptcha]"]').value = token;
}

function onReCaptchaExpired() {
    document.querySelector('input[name="contact[recaptcha]"]').value = '';
}

window.onReCaptchaSuccess = onReCaptchaSuccess;
window.onReCaptchaExpired = onReCaptchaExpired;
