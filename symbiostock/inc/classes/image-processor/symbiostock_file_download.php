<?php
ini_set( "memory_limit", "1024M" );
function symbiostock_content_header( $type )
{
    if ( $type == 'eps' ) {
        return 'Content-Type: application/postscript';
    } else if ( $type == 'jpg' ) {
        return 'Content-Type: image/jpeg';
    } else if ( $type == 'png' ) {
        return 'Content-Type: image/png';
    }
    if ( $type == 'zip' ) {
        return 'Content-Type: application/zip';
    }
    
}
if ( isset( $_POST[ 'download_file' ] ) ) {
    $parse_path = explode( 'wp-content', dirname( __FILE__ ) );
    require_once( $parse_path[ 0 ] . 'wp-load.php' );   
    //wp-load will give us access to wordpress's info / functions
    require_once( $parse_path[ 0 ] . 'wp-load.php' );    
    
        
    //get the user's purchased products
    $user_products = symbiostock_get_user_files( trim( $_POST[ 'symbiostock_current' ] ) );
    
    //set up our variables from post, so we know what we are downloading
    $file_and_selection = explode( '_', $_POST[ 'download_file' ] );
    
    //get info of product, so we know size options to deliver
    $product_info = symbiostock_post_meta( $file_and_selection[ 0 ] );
    
    $size_info = unserialize( $product_info[ 'size_info' ][ 0 ] );
    
    $size_width  = $size_info[ $file_and_selection[ 1 ] ][ 'width' ];
    $size_height = $size_info[ $file_and_selection[ 1 ] ][ 'height' ];
    
    //check to see if user has purchased product, if so, proceed
    
    if ( isset( $user_products[ $file_and_selection[ 0 ] ][ 'size_name' ][ $file_and_selection[ 1 ] ] ) ) {
        
        $selection = $file_and_selection[ 1 ];
        
        $type = $user_products[ $file_and_selection[ 0 ] ][ 'type' ];
        
        $file_name = $file_and_selection[ 0 ] . '-' . $file_and_selection[ 1 ] . '.' . $type;
        
        header( 'Content-Disposition: attachment; filename="' . $file_name . '"' );
        
        header( symbiostock_content_header( $user_products[ 'type' ] ) );
        
        header( "Cache-Control: no-cache, must-revalidate" );
        
        header( "Expires: Mon, 20 Dec 1998 01:00:00 GMT" );
        
        header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
        
        header( "Pragma: no-cache" );
        
        if ( $selection == 'bloggee' || $selection == 'small' || $selection == 'medium' ) {
            
            //dynamically resize and deliver image
           
            $type == 'jpg' ? $quality = 99 : $quality = 9;            
            
            $image = wp_get_image_editor(  ABSPATH . 'symbiostock_rf/' . $file_and_selection[ 0 ] . '.' . $type );            
            $image->resize( $size_width, $size_height );            
            $image->set_quality( $quality );            
            $image->stream();
            
        } else if ( $selection == 'vector' || $selection == 'zip' || $selection == 'large') {
            
            echo file_get_contents( ABSPATH . 'symbiostock_rf/' . $file_and_selection[ 0 ] . '.' . $type );
            
        }
        
    }
    
}
?>