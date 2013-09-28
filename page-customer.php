<?php
/*
Template Name: Customer
*/
/**
 * This page encompasses the cart and general customer area
 *
 * @package symbiostock
 * @since symbiostock 1.0
 */
get_header(); ?>
        <div class="home row">
    
            <div id="primary" class="content-area col-md-12">
            
                <div id="content" class="site-content" role="main">
                
                <?php
                
                $symbiostock_paypal_complete = get_query_var('paypal_return_message');
                
                $symbiostock_admin_email = get_option('admin_email');
                
                if($symbiostock_paypal_complete == 1){
                    ?>
                    
                    <div class="alert alert-success">
                        <strong>Thank you for your purchase!</strong>
                    </div> 
                    <div class="alert alert-info">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong><em>Items still in your cart, and not in your download area?</em></strong><br /> Wait a few seconds and refresh the page. 
                    Sometimes paypal takes a few minutes to notify us. If you still have problems, please contact us.
                    </div>
                    
                    <?php
                    }
                
                ?>
    
                    <?php while ( have_posts() ) : the_post(); ?>
    
                        <?php get_template_part( 'content-customer', 'page' ); ?>
                    
    
                    <?php endwhile; // end of the loop. ?>
    
                </div><!-- #content .site-content -->
           
                   
            </div><!-- #primary .content-area -->
        
        </div>        
        
        
<?php get_footer(); ?>