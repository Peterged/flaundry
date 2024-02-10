window.addEventListener('change', function (e) {
    if (e.target.matches('.flash-message-alert')) {
        this.setTimeout(function () {
            e.target.remove();
        }, 3000, e);
    }
});

let flashMessages = document.querySelectorAll('.flash-message-alert');

flashMessages.forEach(function (flashMessage) {
    setTimeout(function () {
        flashMessage.classList.add('flash-message-hidden');
        setTimeout(function () {
            flashMessage.remove();
        }, 500);
    }, 4500);
});

flashMessages.forEach(function (flashMessage) {
    flashMessage.querySelector('.flash-message-close').addEventListener('click', function () {
        flashMessage.classList.add('flash-message-hidden');
        setTimeout(function () {
            flashMessage.remove();
        }, 500);
    });
});
// Path: public/js/services/flashMessage.js