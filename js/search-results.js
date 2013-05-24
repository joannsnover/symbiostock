
jQuery(document).ready(function ($) {
			
    function pageFunctions() {
        var symbiostock_loading_image = symbiostock_large_loader;
        $('#search_selection_symbiostock_preview').attr("src", symbiostock_loading_image);
        $(".modal_activate").click(function () {
            $('#search_selection_symbiostock_preview').attr("src", symbiostock_loading_image);
            var imagevals = new Array(
                'price_bloggee',
                'price_small',
                'price_medium',
                'price_large',
                'price_vector',
                'price_zip',
                'discount_percent',
                'exclusive',
                'symbiostock_transparency',
                'size_eps',
                'size_zip',
                'extensions',
                'title',
                'bloggee',
                'small',
                'medium',
                'large',
                'author');
            var prfx = 'search_selection_';
            var clicked_id = ($(this).closest('div').attr('id'));
            var id_array = clicked_id.split("_");
            var id = id_array[0] + '_' + id_array[1];
            var src = $('#' + id + '_symbiostock_preview').val();
            var permalink = $('#' + id + '_permalink').val();
            $('#search_selection_symbiostock_preview').attr("src", src);
            $('#search_selection_permalink').attr("href", permalink);
            for (var i = 0; i < imagevals.length; i++) {
                var val = $('#' + id + '_' + imagevals[i]).val();
                $('#' + prfx + imagevals[i]).text(val);
            }
            e.preventDefault();
        });
        $(".search-result").hover(function () {
            $(this).children(".sscntrl").fadeIn();
        }, function () {
            $(this).children(".sscntrl").fadeOut();
        });
    }
    pageFunctions()
    jQuery(document).on("click", ".network_results a.page-numbers", function (e) {
		var symbiostock_loading_image = symbiostock_large_loader;
		
        var network_box = ($(this).closest('.network_results').attr('id'));
        var site_order = network_box.split("_")
        var get_page = $(this).attr('href');
        var get_vars = $('#' + network_box + '_vars').val();
        var network_query = $(this).attr('data-networklink');
        var start_count = $('#network_site_' + site_order[2] + '_start_count').val()
		
		$("#"+network_box+' .network_results_container').empty().append('<img class="symbiostock_loader" src="'+symbiostock_loading_image+'" />');
		
        // Initialise the request
        $.post(ajaxurl, {
            action: 'symbiostock_process',
            symbiostock_network_query: network_query,
            symbiostock_site_order: site_order[2],
            symbiostock_start_count: start_count,
			ajax_request: 1
        }, function (response) {
			
			$("#" + network_box).replaceWith(response);
			$("#" + network_box + ' .search-result').hide();	
			
			$("#" + network_box + ' .search-result').each(function () {
				$(this).fadeIn('slow')
			});	

			pageFunctions()

        });
        e.preventDefault();
        return false;
    });	
	$('.site_load_ajax').each(function () {
		var network_box = ($(this).closest('.network_results').attr('id'));
		var site_order = network_box.split("_")
        //var get_page = $(this).attr('href');
        var get_vars = $('#' + network_box + '_vars').val();
        var network_query = $(this).attr('data-search');
        var start_count = $('#network_site_' + site_order[2] + '_start_count').val()
		
        // Initialise the request
        $.post(ajaxurl, {
            action: 'symbiostock_process',
            symbiostock_network_query: network_query,
            symbiostock_site_order: site_order[2],
            symbiostock_start_count: start_count,
			ajax_request: 1
        }, function (response) {
				
			$("#" + network_box).replaceWith(response);
			$("#" + network_box + ' .search-result').hide();	
			
			$("#" + network_box + ' .search-result').each(function () {
				$(this).fadeIn('slow')
			});	
				
			pageFunctions()
        });	
		
	});		
	
});