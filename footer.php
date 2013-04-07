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
               <?php symbiostock_website_copyright(); ?>
   </div>          
</footer><!-- #colophon .site-footer -->
<?php include_once('modal-login.php'); ?>
<?php wp_footer(); ?>
</body>
</html>