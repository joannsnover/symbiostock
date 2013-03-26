jQuery(document).ready(function ($) {
    //for changing tabs
    $('.nav-tab').live('click', function (e) {
        var tab = jQuery(this).attr("id");
        data = {
            action: 'symbiostock_admin_form_submit',
            symbiostock_nonce: symbiostock_admin_vars.symbiostock_nonce,
            tab: tab
        }
        $.post(ajaxurl, data, function (response) {
            $('#symbiostock_admin_form_loader').hide();
            $('#symbiostock_admin_form_submit').attr('disabled', false);
            $("#symbiostock_admin").replaceWith(response);
        })
        return false;
    });
    //serializing and posting form data
    $('#symbiostock_admin_form').live('submit', function () {
        $('#symbiostock_admin_form_submit').attr('disabled', true);
        $('#symbiostock_admin_form_loader').show();
        data = $(this).serialize() + '&action=' + 'symbiostock_admin_form_submit' + '&symbiostock_nonce=' + symbiostock_admin_vars.symbiostock_nonce + '&tab=' + $('.nav-tab-active').attr('id')
        $.post(ajaxurl, data, function (response) {
            $('#symbiostock_admin_form_loader').hide();
            $('#symbiostock_admin_form_submit').attr('disabled', false);
            $("#symbiostock_admin").replaceWith(response);
        })
        return false;
    });
    //adding new rows / new slides
    $('#add_row').live('click', function () {
        var row_number = $('.slider-data').length;
        if (row_number < 10) {
            var img = 'symbiostock_slide_image_';
            var desc = 'symbiostock_slide_description_';
            var dupe = $('.slider-data:last');
            $(dupe).clone().attr('value', '').insertAfter('.slider-data:last').hide().fadeIn(750);
            var new_row = $('.slider-data:last');
            new_row.find('[id*="' + img + (row_number - 1) + '"]').attr('id', img + row_number).val('');
            new_row.find('[name*="' + img + (row_number - 1) + '"]').attr('name', img + (row_number));
            new_row.find('[for*="' + img + (row_number - 1) + '"]').attr('for', img + (row_number));
            new_row.find('[id*="' + desc + (row_number - 1) + '"]').attr('id', desc + row_number).val('');
            new_row.find('[name*="' + desc + (row_number - 1) + '"]').attr('name', desc + (row_number));
            new_row.find('[for*="' + desc + (row_number - 1) + '"]').attr('for', desc + (row_number));
        }
        return false;
    });
    $('#remove').live('click', function () {
        var count = 0;
        var img = 'symbiostock_slide_image_';
        var desc = 'symbiostock_slide_description_';
        $(this).closest('.slider-data').fadeOut(750, function () {
            $(this).remove();
            $('.slider-data').each(function () {
                $(this).find("[id^='" + img + "']").prop("id", img + count);
                $(this).find("[name^=" + img + "]").prop("name", img + count);
                $(this).find("[for^=" + img + "]").prop("for", img + count);
                $(this).find("[id^='" + desc + "']").prop("id", desc + count);
                $(this).find("[name^=" + desc + "]").prop("name", desc + count);
                $(this).find("[for^=" + desc + "]").prop("for", desc + count);
                count++;
            });
        });
        return false;
    });
	
		//unlimited sales cheat-code: UP UP DOWN DOWN LEFT RIGHT LEFT RIGHT B A B A ENTER
		//...on symbiostock admin screen...
		var kkeys = [], konami = "38,38,40,40,37,39,37,39,66,65,66,65,13";
	$(document).keydown(function(e) {
	  kkeys.push( e.keyCode );
	  if ( kkeys.toString().indexOf( konami ) >= 0 ){
	
		alert('Cheat Enabled: Unlimited Sales!');
	
	  }
	});
});