<?php


class symbiostock_front_ajax
{


    function __construct()
    {
        add_action( 'wp_ajax_symbiostock_process' , array( &$this, 'process' ) );
        //for users logged out
        add_action( 'wp_ajax_nopriv_symbiostock_process' ,
                array( &$this, 'process' ) ); // executed when logged out
        if ( !isset( $_POST[ 'process' ] ) )
        {
            // Include the Ajax library on the front end
            add_action( 'wp_head' , array( &$this, 'add_ajax_library' ) );
        }
    }
    /**
     * Adds the WordPress Ajax Library to the frontend.
     */


    public function add_ajax_library()
    {
        $html = '<script type="text/javascript">';
        $html .= 'var ajaxurl = "' . admin_url( 'admin-ajax.php' ) . '"';
        $html .= '</script>';
        echo $html;
    } // end add_ajax_library    
    //main function for processing all of our ajaxy stuff


    public function process()
    {
        if ( isset( $_POST[ 'product_selection' ] ) )
        {
            $selection = explode( '_' , $_POST[ 'product_selection' ] );
            $product_post_meta = symbiostock_post_meta( $selection[ 0 ] );
            //set up the buying options from cart class
            $cart_options = new symbiostock_cart( $product_post_meta );
            $cart_options->add_item_to_cart( $selection );
            $cart_options->display_product_table( );
        }
        if ( isset( $_POST[ 'remove' ] ) )
        {
            $remove = explode( '_' , $_POST[ 'remove' ] );
            $cart_options = new symbiostock_cart( );
            $cart_options->remove_item_from_cart( $remove[ 1 ] );
            $cart_options->display_customer_cart( );
        }
        if ( isset( $_POST[ 'symbiostock_network_query' ] ) )
        {
            $network_results = new network_manager( );
            $network_results
                    ->network_page_query( $_POST[ 'symbiostock_network_query' ] );
        }
        do_action( 'ss_cart_ajax' );
        //say "die" at end of function, or bad things happen
        die( );
    }
}
$symbiostock_ajax = new symbiostock_front_ajax;
?>