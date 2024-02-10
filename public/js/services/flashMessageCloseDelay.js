const MINIMUM_DELAY_BEFORE_CLOSE = 1000;
const STANDARD_DELAY_BEFORE_CLOSE = 3000;
const DELAY_DURING_CLOSE = 500;

window.addEventListener('change', function (e) {
    if (e.target.matches('.flash-message-alert')) {
        let delay = parseInt(e.target.getAttribute('data-delay')) || STANDARD_DELAY_BEFORE_CLOSE;
        this.setTimeout(function () {
            e.target.remove();
        }, delay, e);
    }
});

let flashMessages = document.querySelectorAll('.flash-message-alert');

flashMessages.forEach(function (flashMessage) {
    let delay = parseInt(flashMessage.getAttribute('data-delay')) || STANDARD_DELAY_BEFORE_CLOSE;
    setTimeout(function () {
        flashMessage.classList.add('flash-message-hidden');
        setTimeout(function () {
            flashMessage.remove();
        }, DELAY_DURING_CLOSE);
    }, delay);
});

flashMessages.forEach(function (flashMessage) {
    flashMessage.querySelector('.flash-message-close').addEventListener('click', function () {
        flashMessage.classList.add('flash-message-hidden');
        setTimeout(function () {
            flashMessage.remove();
        }, DELAY_DURING_CLOSE);
    });
});
// Path: public/js/services/flashMessage.js
