// JavaScript Document
//pulse effects for user guiding
(function ($) {
    var methods = {
        init: function (options) {
            var settings = {
                color: $(this).css("background-color"),
                reach: 20,
                speed: 1000,
                pause: 0,
                glow: true,
                repeat: true,
                onHover: false
            };
            $(this).css({
                "-moz-outline-radius": $(this).css("border-top-left-radius"),
                "-webkit-outline-radius": $(this).css("border-top-left-radius"),
                "outline-radius": $(this).css("border-top-left-radius")
            });

            if (options) {
                $.extend(settings, options);
            }
            settings.color = $("<div style='background:" + settings.color + "'></div>").css("background-color");
            if (settings.repeat !== true && !isNaN(settings.repeat) && settings.repeat > 0) {
                settings.repeat -= 1;
            }

            return this.each(function () {
                if (settings.onHover) {
                    $(this).bind("mouseover", function () {
                        pulse(settings, this, 0);
                    })
                        .bind("mouseout", function () {
                            $(this).pulsate("destroy");
                        });
                } else {
                    pulse(settings, this, 0);
                }
            });
        },
        destroy: function () {
            return this.each(function () {
                clearTimeout(this.timer);
                $(this).css("outline", 0);
            });
        }
    };

    var pulse = function (options, el, count) {
        var reach = options.reach,
            count = count > reach ? 0 : count,
            opacity = (reach - count) / reach,
            colorarr = options.color.split(","),
            color = "rgba(" + colorarr[0].split("(")[1] + "," + colorarr[1] + "," + colorarr[2].split(")")[0] + "," + opacity + ")",
            cssObj = {
                "outline": "2px solid " + color
            };
        if (options.glow) {
            cssObj["box-shadow"] = "0px 0px " + parseInt((count / 1.5)) + "px " + color;
            userAgent = navigator.userAgent || '';
            if (/(chrome)[ \/]([\w.]+)/.test(userAgent.toLowerCase())) {
                cssObj["outline-offset"] = count + "px";
                cssObj["outline-radius"] = "100 px";
            }
        } else {
            cssObj["outline-offset"] = count + "px";
        }
        $(el).css(cssObj);

        var innerfunc = function () {
            if (count >= reach && !options.repeat) {
                $(el).pulsate("destroy");
                return false;
            } else if (count >= reach && options.repeat !== true && !isNaN(options.repeat) && options.repeat > 0) {
                options.repeat = options.repeat - 1;
            } else if (options.pause && count >= reach) {
                pause(options, el, count + 1);
                return false;
            }
            pulse(options, el, count + 1);
        };

        el.timer = setTimeout(innerfunc, options.speed / reach);
    };

    var pause = function (options, el, count) {
        innerfunc = function () {
            pulse(options, el, count);
        };
        setTimeout(innerfunc, options.pause);
    };

    $.fn.pulsate = function (method) {
        // Method calling logic
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.pulsate');
        }

    };
})(jQuery);


//navigation menu
jQuery(document).ready(function ($) {

    $('.dropdown-toggle ul').css('display', 'block').slideUp(0);
    $('.dropdown-toggle').hover(
        function () {
            //drops submenu
            $(this).children('ul').stop(true, true).fadeIn("slow");
        },
        function () {
            //retracts submenu
            $(this).children('ul').stop(true, true).fadeOut("slow");
        });


    //carousel
    $('.carousel').carousel()


    //disable image link, but keep it for SEO
    $('#stock-image-preview').css('cursor', 'default');
    $('#stock-image-preview').click(function (e) {
        e.preventDefault();
    });

    //Product Ajax
    //if product selection changes...
    $(document).on("click", 'input[name="product"]', function (event) {
    	
    	var price = $(this).data("price");
    	
    	var c=confirm('Add size "' + $('input[name="product"]:checked').parent().text() + '" ('+price +') to cart?');
    	
    	if(c){	    	
	        var process = 'product_cart_action';
	        var product_selection = $('input[name="product"]:checked').val();
	        // Initialise the request
	        $.post(ajaxurl, {
	            action: 'symbiostock_process',
	            product_selection: product_selection
	        }, function (response) {
	            $("#symbiostock_product_form").replaceWith(response);
	        });
	
	        $('.license_area').pulsate({
	            color: "#09f", // set the color of the pulse
	            reach: 20, // how far the pulse goes in px
	            speed: 1000, // how long one pulse takes in ms
	            pause: 50, // how long the pause between pulses is in ms
	            glow: false, // if the glow should be shown too
	            repeat: 1, // will repeat forever if true, if given a number will repeat for that many times
	            onHover: false // if true only pulsate if user hovers over the element
	        });
    	} else {
    		
    		return false;
    		
    	}
        

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

    $('#aggree_to_EULA').live('click', function () {

        if ($(this).is(':checked')) {

            $('#symbiostock_pay_now').removeAttr('disabled');
        } else {
            $('#symbiostock_pay_now').attr("disabled", "true");
        }
    });

    $('#ss_primary_search').pulsate({
        color: "#09f", // set the color of the pulse
        reach: 5, // how far the pulse goes in px
        speed: 500, // how long one pulse takes in ms
        pause: 50, // how long the pause between pulses is in ms
        glow: false, // if the glow should be shown too
        repeat: 3, // will repeat forever if true, if given a number will repeat for that many times
        onHover: false // if true only pulsate if user hovers over the element
    });

    //window sizing and responsive adjustments for devices
    function symbiostock_adjust_body_padding() {

        var nav_height = $('#ss_fixed_nav').height() + 10;

        $("body").css({
            paddingTop: nav_height
        });

    }
    if ($('#ss_fixed_nav').hasClass('navbar-fixed-top')) {

        symbiostock_adjust_body_padding();

        $(window).on('resize', function () {

            symbiostock_adjust_body_padding();

        });
    }

    //a small fix to compensate for wordpress's widget layout in regards to bootstrap 3 nesting requirements
    $(function () {
        $('.panel-heading').each(function () {
            $(this).insertBefore($(this).closest('.panel-body'));
        });
    });

    //add some classes to elements to take advantage of bootstrap 3 (cuts down on stylesheet size)

    $(function () {
        $('#secondary ul').addClass('nav nav-pills nav-stacked');
        $('input[type=text], input[type=email], select, input[type=password], textarea').addClass('form-control');
        $('input[type=submit], input[type=reset], input[type=button]').addClass('btn btn-default form-control');

    });

});