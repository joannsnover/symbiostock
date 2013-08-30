jQuery(document).ready(function ($) {
    //for changing tabs
    $('#clickme').live('click', function (e) {
        data = {
            action: 'symbiostock_process_images',
            symbiostock_nonce: symbiostock_processor_vars.symbiostock_nonce
   
        }
        $.post(ajaxurl, data, function (response) {
            $("#clickme").replaceWith(response);
        })
        return false;
    });
});