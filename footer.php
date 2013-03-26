<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package symbiostock
 * @since symbiostock 1.0
 */
?>
	</div><!-- #main .site-main -->
	</div>
    
<footer id="colophon" class="site-footer" role="contentinfo">
   	<div class="container">
		<div class="site-info row-fluid">
      
            <div class="span4 footer_section">
            <?php dynamic_sidebar('Footer Left'); ?>
 			<?php symbiostock_credit_links('footer'); ?>
            </div>
            <div class="span4 footer_section">
            <?php dynamic_sidebar('Footer Middle'); ?>
            </div>
            <div class="span4 footer_section">
            <?php dynamic_sidebar('Footer Right'); ?>
			
            </div>
                    
		</div><!-- .site-info -->
        
	</div><!-- #page .hfeed .site -->
    
   <div class="footer_info">       
				 
                <?php do_action( 'symbiostock_credits' ); ?>
                <a href="http://wordpress.org/" title="<?php esc_attr_e( 'A Semantic Personal Publishing Platform', 'symbiostock' ); ?>" rel="generator"><?php printf( __( 'Proudly powered by %s', 'symbiostock' ), 'WordPress' ); ?></a>
                <span class="sep"> | </span>
                <?php printf( __( 'Theme: %1$s by %2$s.', 'symbiostock' ), 'SYMBIOSTOCK', '<a href="http://www.clipartillustration.com/" rel="designer">Leo Blanchette</a>' ); ?>
   </div>          
</footer><!-- #colophon .site-footer -->
<?php include_once('modal-login.php'); ?>
<?php wp_footer(); ?>
</body>
</html>