const DELAY_DURING_CLOSE = 500;

window.addEventListener('change', function (e) {
    if (e.target.matches('.flash-message-alert')) {
        e.target.querySelector('.flash-message-close').addEventListener('click', function () {
            e.target.classList.add('flash-message-hidden');
            setTimeout(function () {
                e.target.remove();
            }, DELAY_DURING_CLOSE);
        });
    }
});

let flashMessages = document.querySelectorAll('.flash-message-alert');

flashMessages.forEach(function (flashMessage) {
    flashMessage.querySelector('.flash-message-close').addEventListener('click', function () {
        flashMessage.classList.add('flash-message-hidden');
        setTimeout(function () {
            flashMessage.remove();
        }, DELAY_DURING_CLOSE);
    });
});
