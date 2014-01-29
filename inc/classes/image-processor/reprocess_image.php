<?php
/**
 * Reprocesses image.
 *
 * This is the function responsible for reprocessing images after they've been uploaded.
 * 
 * @package symbiostock
 * @subpackage image-processing
 * 
 * @param int $post_id Image number.
 * @param bool $promo Whether or not its a promo image.
 * @param number $size Size in pixels.
 */
function symbiostock_reprocess_image( $post_id, $promo = false, $size = 590 )
{
    global $post;
    global $typenow;
    $post_type_bulk = $typenow;
    
    if ( $post->post_type == 'image'
            || $post_type_bulk == 'image'
                    && !isset( $_POST[ 'symbiostock_update_images' ] ) )
        //sometimes people have images of obnoxiously huge size, so we up memory to obnoxiously huge limit
        ini_set( "memory_limit" , "1024M" );
    //set the time limit for five minutes in case theres a lot of images
    set_time_limit( 300 );

    $attachment_id = get_post_meta( $post_id , 'symbiostock_preview_id' );

    $file_attachment_path = get_attached_file( $attachment_id[ 0 ] );

    include_once( symbiostock_CLASSROOT
            . 'image-processor/symbiostock_image_processor.php' );

    $watermark_path = symbiostock_get_watermark_path( );

    $stockdir = symbiostock_STOCKDIR;

    $tmp = symbiostock_TMPROOT;

    if ( file_exists( $stockdir . $post_id . '.jpg' ) )
    {
        $file = $stockdir . $post_id . '.jpg';
        $meta = true;
        $ext = '.jpg';
    } else if ( file_exists( $stockdir . $post_id . '.png' ) )
    {

        //if this is a promo image, we must abort because png files don't do IPTC
        if ( $promo == true )
            return;

        $file = $stockdir . $post_id . '.png';
        $meta = false;
        $ext = '.png';
    } else
    {
        return;
    }

    //first generate a new preview, then save it to tmp
    $image = wp_get_image_editor( $file );
    $image->resize( $size , $size );
    $image->set_quality( 100 );
    $image->save( $tmp . $post_id . '.jpg' );

    if ( $promo == false )
    {
        //watermark the image
        symbiostock_watermark_image( $tmp . $post_id . '.jpg' ,
        $tmp . $post_id . '.jpg' , $watermark_path );
    }

    //update its meta
    symbiostock_update_meta( $file , $tmp . $post_id . '.jpg' ,
    $tmp . $post_id . '.jpg' , $post_id );
    //copy it

    if ( $promo == true )
    {

        //if promo it sits in our protected directory as an assumed promo image (passed through protected URL)
        if ( !copy( $tmp . $post_id . '.jpg' ,
                $stockdir . $post_id . '_promo.jpg' ) )
        {
            echo "failed to copy $file...\n";
        }
        } else
            {
            //if it is watermarked, we over-write its preview
                if ( !copy( $tmp . $post_id . '.jpg' , $file_attachment_path ) )
                {
                        echo __("failed to copy", 'symbiostock') . "$file...\n";
                }
                }

                //delete temp image
                if ( file_exists( $tmp . $post_id . '.jpg' ) )
                {
                unlink( $tmp . $post_id . '.jpg' );
} elseif ( file_exists( $tmp . $post_id . '.png' ) )
{
unlink( $tmp . $post_id . '.jpg' );
}
}