// JavaScript Document
//navigation menu
jQuery(document).ready(function ($) {
		
	$('a').click(function () {
		if ($(this).attr('data-query'))
			$(this).attr('href',$(this).attr('href') + $(this).attr('data-query'));
		$(this).attr('data-query','');
	});		 

    $('.dropdown-toggle ul').css('display', 'block').slideUp(0);
    $('.dropdown-toggle').hover(
    function () {
        //drops submenu
        $(this).children('ul').stop(true,true).fadeIn("slow");
    },
    function () {
        //retracts submenu
        $(this).children('ul').stop(true,true).fadeOut("slow");
    });
	
	
    //carousel
    $('.carousel').carousel()
	
	if ($('#symbiostock_mobile_menu').length) {
		
		//window sizing and responsive adjustments for devices
		var window_size = $(window).width();
		var main_nav = $('#main-navigation').clone(true, true);
		var mobile_nav = $('.mobile_menu').clone(true, true).addClass('cloned span12');
		if (window_size < 1000) {
			$('#main-navigation').hide();
			$(mobile_nav).insertAfter('.main-navigation');
		}
		$(window).on('resize', function () {
			var resized = $(window).width();
			if (resized < 1000) {
				$('#main-navigation').hide();
				$(mobile_nav).insertAfter('.main-navigation');
			} else {
				$(mobile_nav).remove();
				$('#main-navigation').show();
			}
		});	
	}
	
	//disable image link, but keep it for SEO
	$('#stock-image-preview').css( 'cursor', 'default' );
	$('#stock-image-preview').click(function(e) {
    	e.preventDefault();
	});
	
    //Product Ajax
    //if product selection changes...
    $(document).on("click", 'input[name="product"]', function (event) {
        var process = 'product_cart_action';
        var product_selection = $('input[name="product"]:checked').val();
        // Initialise the request
        $.post(ajaxurl, {
            action: 'symbiostock_process',
            product_selection: product_selection
        }, function (response) {
            $("#symbiostock_product_form").replaceWith(response);
        });
    });
    //remove item from cart...
    $(document).on("click", '.remove_from_cart', function (event) {
        var process = 'remove_from_cart';
        var remove = $(this).attr('id');
        // Initialise the request
        $.post(ajaxurl, {
            action: 'symbiostock_process',
            remove: remove,
        }, function (response) {
            $("#symbiostock_cart").replaceWith(response);
        });
        return false;
        e.preventDefault();
    });
	
	$('#aggree_to_EULA').live('click', function(){
	
		if($(this).is(':checked')){
	
			$('#symbiostock_pay_now').removeAttr('disabled');
		}
		else {
			$('#symbiostock_pay_now').attr("disabled","true"); 
		}
	});
});