(function($) {
    'use strict';

    jQuery(document).ready(function($) {
        let limitType = $('select[name=wpadcf7sl-limit-type]').val();
        if (typeof limitType !== 'undefined') {
            $('.wpadcf7sl-limit-type').hide();
            $('.if-show-limit-type-' + limitType).show();
            $('#' + limitType).show();
        }

        $('select[name=wpadcf7sl-limit-type]').on('change', function() {
            var value = $(this).val();
            $('.wpadcf7sl-limit-type').hide();
            $('.if-show-limit-type-' + value).show();
            $('.wpadcf7sl-desc p').hide();
            $('#' + value).show();
        });

        let afterSubmission = $('select[name=wpadcf7sl-after-submission]').val();
        if (typeof afterSubmission !== 'undefined' && afterSubmission !== '') {
            $('#' + afterSubmission).show();
        }

        $('select[name=wpadcf7sl-after-submission]').on('change', function() {
            var afterSubmission = $(this).val();
            $('.wpadcf7sl-after-submission').hide();
            $('#' + afterSubmission).show();
        });

        if ($('#wpadcf7sl-reset-limit-disable').is(':checked')) {
            $('.if-show-reset-limit-enable').hide();
            $('.if-show-reset-limit-enable').find('input, select').attr('disabled', 'disabled');
        }

        $('#wpadcf7sl-reset-limit-disable').on('change', function() {
            var value = $(this).val();
            if (this.checked) {
                $('.if-show-reset-limit-enable').hide();
                $('.if-show-reset-limit-enable').find('input, select').attr('disabled', 'disabled');
            } else {
                $('.if-show-reset-limit-enable').show();
                $('.if-show-reset-limit-enable').find('input, select').removeAttr('disabled');
            }
        });

        $('input[name=wpadcf7sl-instant-reset]').on('click', function(e) {
            e.preventDefault();

            var formId = $(this).data('formid');
            var limitType = $('select[name=wpadcf7sl-limit-type]').val();

            var data = {
                action: 'reset_submission_limit',
                formId: formId,
                limitType: limitType,
                _nonce: wpadcf7sl_admin.nonce,
            };

            $.ajax(wpadcf7sl_admin.ajaxurl, {
                method: 'post',
                data: data,
                beforeSend: function() {
                    jQuery("#cf7_submission_limit").waitMe({ effect: 'ios' });
                },
                success: function(response) {
                    if (response.success) {
                        alert(wpadcf7sl_admin.success);
                    } else {
                        alert(wpadcf7sl_admin.error);
                    }
                    jQuery("#cf7_submission_limit").waitMe('hide');
                }
            })
        });
    });

})(jQuery);