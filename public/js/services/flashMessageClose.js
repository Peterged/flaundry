window.addEventListener('change', function (e) {
    if (e.target.matches('.flash-message-alert')) {
        e.target.querySelector('.flash-message-close').addEventListener('click', function () {
            e.target.classList.add('flash-message-hidden');
            setTimeout(function () {
                e.target.remove();
            }, 500);
        });
    }
});

let flashMessages = document.querySelectorAll('.flash-message-alert');

flashMessages.forEach(function (flashMessage) {
    flashMessage.querySelector('.flash-message-close').addEventListener('click', function () {
        flashMessage.classList.add('flash-message-hidden');
        setTimeout(function () {
            flashMessage.remove();
        }, 500);
    });
});