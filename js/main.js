(function () {
    'use strict';

    let help = document.querySelector('#help-form-text');
    let fields = document.querySelectorAll('input, textarea, select');

    // for ( let field of Array.from(fields) ) {
    for (let field of [...fields]) {
        field.addEventListener('focus', () => {
            let text = event.target.getAttribute('data-help');
            help.textContent = text;
        });

        field.addEventListener('blur', () => {
            help.textContent = '';
        });
    }

    // close button
    let btnClose = document.querySelector('.close');

    if (btnClose) {
        btnClose.addEventListener('click', () => {
            // Close parent element on click
            btnClose.parentElement.style.display = 'none';
        });
    }
})();