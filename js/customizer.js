/**
 * This file adds some LIVE to the Theme Customizer live preview. To leverage
 * this, set your custom settings to 'postMessage' and then add your handling
 * here. Your javascript should grab settings from customizer controls, and 
 * then make any necessary changes to the page using jQuery.
 */
( function( $ ) {

    // Update the site title in real time...
    wp.customize( 'blogname', function( value ) {
        value.bind( function( newval ) {
            $( '#site-title a' ).html( newval );
        } );
    } );
    
    //Update the site description in real time...
    wp.customize( 'blogdescription', function( value ) {
        value.bind( function( newval ) {
            $( '.site-description' ).html( newval );
        } );
    } );

    //Update site title color in real time...
    wp.customize( 'header_textcolor', function( value ) {
        value.bind( function( newval ) {
            $('#site-title a').css('color', newval );
        } );
    } );

    //Update site background color...
    wp.customize( 'background_color', function( value ) {
        value.bind( function( newval ) {
            $('body').css('background-color', newval );
        } );
    } );
    
    //Update container background color...
    wp.customize( 'container_background_color', function( value ) {
        value.bind( function( newval ) {
            $('.container').css('background-color', newval );
        } );
    } );    
    
    //image container

    wp.customize( 'image_container', function( value ) {
        value.bind( function( newval ) {
            $('.image_container').css('background-color', newval );
        } );
    } );
    
    //bio box

    wp.customize( 'bio_box', function( value ) {
        value.bind( function( newval ) {
            $('.bio_box').css('background-color', newval );
        } );
    } );
    
    //Update widget background color...
    wp.customize( 'image_page_side', function( value ) {
        value.bind( function( newval ) {
            $('.image_page_side').css('background-color', newval );
        } );
    } );

    wp.customize( 'image_page_bottom', function( value ) {
        value.bind( function( newval ) {
            $('.image_page_bottom').css('background-color', newval );
        } );
    } );

    wp.customize( 'image_page_bottom_fullwidth', function( value ) {
        value.bind( function( newval ) {
            $('.image_page_bottom_fullwidth').css('background-color', newval );
        } );
    } );

    //Update link color...
    wp.customize( 'link_color', function( value ) {
        value.bind( function( newval ) {
            $('#content a:link').css('color', newval );
        } );
    } );

    //Update h color...
    wp.customize( 'h_color', function( value ) {
        value.bind( function( newval ) {
            $('h1, h2, h3, h4, h5, h6, h1 a:link, h2 a:link, h3 a:link, h4 a:link, h5 a:link, h6 a:link').css('color', newval );
        } );
    } );
    
    //Update panel header color...
    wp.customize( 'ph1_color', function( value ) {        
                            
        value.bind( function( newval ) {
            
            var api = wp.customize,
                val= api.instance('ph0_color').get();    
            
            $('.panel-default .panel-heading').css('background-image', 'linear-gradient(to bottom, ' + val + ' 0%, ' + newval + ' 100%)');
            
        } );
    } );

    wp.customize( 'ph0_color', function( value ) {        
        
        value.bind( function( newval ) {
            
            var api = wp.customize,
                val= api.instance('ph1_color').get();    
            
            $('.panel-default .panel-heading').css('background-image', 'linear-gradient(to bottom, ' + newval + ' 0%, ' + val + ' 100%)');
            
        } );
    } );
    
    //Update footer background color...
    wp.customize( 'f1', function( value ) {        
        
        value.bind( function( newval ) {
            
            var api = wp.customize,
                val= api.instance('f0').get();    
            
            $('footer').css('background-image', 'linear-gradient(to bottom, ' + val + ' 0%, ' + newval + ' 100%)');
            
        } );
    } );

    wp.customize( 'f0', function( value ) {        
        
        value.bind( function( newval ) {
            
            var api = wp.customize,
                val= api.instance('f1').get();    
            
            $('footer').css('background-image', 'linear-gradient(to bottom, ' + newval + ' 0%, ' + val + ' 100%)');
            
        } );
    } );

    //footer panel background color
    wp.customize( 'f-panels', function( value ) {
        value.bind( function( newval ) {
            $('footer .panel').css('background-color', newval );
        } );
    } );
    
    //Update p color...
    wp.customize( 'p_color', function( value ) {
        value.bind( function( newval ) {
            $('p').css('color', newval );
        } );
    } );
    
    
    //Update site title color in real time...
    wp.customize( 'symbiostock_options[link_textcolor]', function( value ) {
        value.bind( function( newval ) {
            $('a').css('color', newval );
        } );
    } );
    
} )( jQuery );