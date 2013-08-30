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
    $("#submit").live('click', function () {
        $("form#symbiostock_admin_form").submit(function () {
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
    });
    
    //re-assigns rows after something changes in list
    function recalc_rows(){
        var count = 0;
        var site = 'symbiostock_network_site_';
        var desc = 'symbiostock_network_description_';
        $('.network-data').each(function () {
            $(this).find("[id^='" + site + "']").prop("id", site + count);
            $(this).find("[name^=" + site + "]").prop("name", site + count);
            $(this).find("[for^=" + site + "]").prop("for", site + count);
            $(this).find("[id^='" + desc + "']").prop("id", desc + count);
            $(this).find("[name^=" + desc + "]").prop("name", desc + count);
            $(this).find("[for^=" + desc + "]").prop("for", desc + count);
            count++;
        });        
    }
    
    //adding new rows / new networks
    $('#add_row').live('click', function () {
        var row_number = $('.network-data').length;
        if (row_number < 10) {
            var site = 'symbiostock_network_site_';
            var desc = 'symbiostock_network_description_';
            var dupe = $('.network-data:last');
            $(dupe).clone().attr('value', '').insertAfter('.network-data:last').hide().fadeIn(750);
            var new_row = $('.network-data:last');
            new_row.find('[id*="' + site + (row_number - 1) + '"]').attr('id', site + row_number).val('');
            new_row.find('[name*="' + site + (row_number - 1) + '"]').attr('name', site + (row_number));
            new_row.find('[for*="' + site + (row_number - 1) + '"]').attr('for', site + (row_number));
            new_row.find('[id*="' + desc + (row_number - 1) + '"]').attr('id', desc + row_number).val('');
            new_row.find('[name*="' + desc + (row_number - 1) + '"]').attr('name', desc + (row_number));
            new_row.find('[for*="' + desc + (row_number - 1) + '"]').attr('for', desc + (row_number));
            new_row.find('[id*="symbiostock_info_' + (row_number - 1) + '"]').empty();
            new_row.find('[class*="get_csv"]').replaceWith('<strong>Add member: </strong>');
        }
        return false;
    });
    //move rows up or down, changing order of network
    $(".network_up,.network_down").live('click', function(){
                
        var row_number = $('.network-data').length;
                
        if($('.network-data').length == 1){
            return;
            }        
        
        var row = $(this).parents("tr:first");
        if ($(this).is(".network_up")) {
            //if this is the first one, we don't want to allow the user to push "up"
            if($(this).parents("tr:first").is(':first-child')){return;}
            if($(this).parent().attr('id') == 'symbiostock_network_description_0'){return;}            
            row.hide().insertBefore(row.prev()).fadeIn();            
        } else {
            //if this is the last one, we don't want to allow the user to push "down"
            if($(this).parents("tr:first").is(':last-child')){return;}
            row.hide().insertAfter(row.next()).fadeIn();
        }
        recalc_rows();
    });
    
    $('#remove').live('click', function () {

        $(this).closest('.network-data').fadeOut(750, function () {
            $(this).remove();
            recalc_rows()
        });
        return false;
    });
    
    //unlimited sales cheat-code: UP UP DOWN DOWN LEFT RIGHT LEFT RIGHT B A B A ENTER
    //...on symbiostock admin screen...
    var kkeys = [],
        konami = "38,38,40,40,37,39,37,39,66,65,66,65,13";
    $(document).keydown(function (e) {
        kkeys.push(e.keyCode);
        if (kkeys.toString().indexOf(konami) >= 0) {
            alert('Cheat Enabled: Unlimited Sales!');
        }
    });
    
    //help area highlight
    document.getElementById(window.location.hash.substring(1)).style.backgroundColor = "#FF3";
});