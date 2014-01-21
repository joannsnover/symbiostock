<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package symbiostock
 * @since symbiostock 1.0
 */
//
// edited jas 2013-12-13 transfer to 2.9.9 and add class thumbnail to minipic to get styling of other thumbs on site
// edited jas 2013-09-19 add image number to download button
// edited jas 2013-09-20 make minipic in purchased images a link
//
if ( !defined('DONOTCACHEPAGE') ){
    define('DONOTCACHEPAGE',true);
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <h1 class="entry-title"><i class="icon-user">&nbsp;</i><i class="icon-shopping-cart">&nbsp;</i><?php the_title(); ?>
        <br /><small><?php echo stripslashes(get_option('symbiostock_customer_area_greeting')); ?></small></h1>
  
    </header><!-- .entry-header -->
<div class="row">
    <div class="col-md-6">
    <?php         
        $cart_display = new symbiostock_cart();                   
        $cart_display->display_customer_cart();                    
    ?>
    </div>
    <div class="span6">
     
        <?php edit_post_link( __( 'Edit', 'symbiostock' ), '<span class="edit-link">', '</span>' ); ?>
        <?php the_content(); ?>
    </div><!-- .entry-content -->
    </div>
    
    </article><!-- #post-<?php the_ID(); ?> -->
       
        
        <?php 
        
        //update_user_meta($current_user->ID , 'symbiostock_purchased_products', '');
        
        $user_products = symbiostock_get_user_files($user_id='');
        
        if(!empty($user_products)){
            
            global $current_user;
             get_currentuserinfo();            
            
            $file_download_script = WP_CONTENT_URL . '/symbiostock_file_download.php';
            ?> <hr /><h2><i class="icon-download"> <?php _e('Your Licensed Images/Files', 'symbiostock') ?></i></h2>
            <form class="" action="<?php echo $file_download_script; ?>" method="post">
            <input type="hidden" name="symbiostock_current" value="<?php echo $current_user->ID; ?>" />
            <?php            
    
            foreach($user_products as $product => $info){
                        
        
                //gather relevant data
                
                $product_info = symbiostock_post_meta($product);            
                            
                $sizes = (unserialize($product_info['size_info'][0]));
                        
                //jas begin Make minipic a link (from  display_customer_cart( ) in cart.php)
                echo '<div class="purchased_file well">' .
                $product_info['id'] . ' <br />
                
                    <a title="" href="' . get_permalink( $product ) . '"><img class="thumbnail" width="' . $sizes['thumb']['width'] . '" height="' . $sizes['thumb']['height'] . '" alt="img ' . $product . '" src="' . $product_info['symbiostock_minipic'][0] . '" /></a>';
                //jas end
                $size_info = unserialize($product_info['size_info'][0]);
                
                $size_name = $info['size_name'];
                
                echo '<br class="clearfix" />
                <strong><hr /><button class="btn" name="download_file" value="'.$product.'_'.$info['size_name'].'" type="submit"><i class="icon-download"> </i> ' . $info['type'] . ', ' . ucwords($info['size_name']) . '</button></strong><br />' . $size_info[$size_name]['pixels'] . '<br />' . $size_info[$size_name]['dpi'] . '<br class="clearfix" /></div>';
                
                //make the row                                        
                
            }
                    
        }
        ?>
        </form>
        <br class="clearfix" />
        </div>    