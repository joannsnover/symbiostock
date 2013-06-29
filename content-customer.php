<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package symbiostock
 * @since symbiostock 1.0
 */
if ( !defined('DONOTCACHEPAGE') ){
	define('DONOTCACHEPAGE',true);
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title"><i class="icon-user">&nbsp;</i><i class="icon-shopping-cart">&nbsp;</i><?php the_title(); ?>
        <br /><small><?php echo stripslashes(get_option('symbiostock_customer_area_greeting')); ?></small></h1>
  
	</header><!-- .entry-header -->
<div class="row-fluid">
    <div class="span6">
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
			?> <hr /><h2><i class="icon-download"> Your Licensed Images/Files</i></h2>
			<form class="" action="<?php echo $file_download_script; ?>" method="post">
            <input type="hidden" name="symbiostock_current" value="<?php echo $current_user->ID; ?>" />
			<?php			
	
			foreach($user_products as $product => $info){
						
		
				//gather relevant data
				
				$product_info = symbiostock_post_meta($product);			
							
				$sizes = (unserialize($product_info['size_info'][0]));
						
									
				echo '<div class="purchased_file well">
				
					<img width="' . $sizes['thumb']['width'] . '" height="' . $sizes['thumb']['height'] . '" alt="img ' . $product . '" src="' . $product_info['symbiostock_minipic'][0] . '" />';
				
				$size_info = unserialize($product_info['size_info'][0]);
				
				$size_name = $info['size_name'];
				
				echo '<br class="clearfix" />
				<strong><hr /><button class="btn" name="download_file" value="'.$product.'_'.$info['size_name'].'" type="submit"><i class="icon-download"> </i> ' . $info['type'] . ', ' . ucwords($info['size_name']) . '</button></strong><br />' . $size_info[$size_name]['pixels'] . '<br />' . $size_info[$size_name]['dpi'] . '<br class="clearfix" /></div>' ;
				
				//make the row										
				
			}
					
		}
		?>
        </form>
        <br class="clearfix" />
		</div>    