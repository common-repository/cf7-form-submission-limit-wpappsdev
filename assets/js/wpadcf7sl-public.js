(function($) {
    'use strict';

    document.addEventListener('wpcf7mailsent', function(event) {
        let afterSubmission = '';
        let reloadDelay = 5;
        let redirectPage = '';
        let formDetail = event.detail;
        let formInputs = formDetail.inputs;

        formInputs.reverse();
        formInputs.forEach(function(item, index) {
            if (item.name == "wpadcf7sl_after_submission") {
                afterSubmission = item.value;
            }
            if (item.name == "wpadcf7sl_reload_delay") {
                reloadDelay = item.value;
            }
            if (item.name == "wpadcf7sl_redirect_page") {
                redirectPage = item.value;
            }
        });

        if (afterSubmission == 'redirectpage') {
            if (redirectPage != '') {
                window.location.assign(redirectPage);
            }
        }

        if (afterSubmission == 'reloadpage') {
            setTimeout(function() {
                location.reload();
            }, (reloadDelay * 1000));
        }
    }, false);

})(jQuery);