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
            <footer id="colophon" class="well" role="contentinfo">   
                <div class="row">
                    <?php if(is_active_sidebar( 'footer-1-3' )): ?> 
                    <div class="col-md-4">  
                        <div class="panel">
                            <?php dynamic_sidebar('footer-1-3'); ?>                      
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if(is_active_sidebar( 'footer-2-3' )): ?> 
                    <div class="col-md-4"> 
                        <div class="panel">
                            <?php dynamic_sidebar('footer-2-3'); ?>            
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if(is_active_sidebar( 'footer-3-3' )): ?> 
                    <div class="col-md-4"> 
                        <div class="panel">
                            <?php dynamic_sidebar('footer-3-3'); ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    
                    <div class="col-md-12">
                    <?php symbiostock_credit_links('footer'); ?>
                    </div>
                                    
                    
                    <div class="col-md-12 footer_info">
                        <div class="">       
                        <?php symbiostock_website_copyright(); ?> 
                        </div>              
                    </div>                  
                </div>                          
            </footer><!-- #colophon .site-footer -->

     </div><!-- #main .site-main -->
</div>

<?php get_template_part('modal-login'); ?>
<?php wp_footer(); ?>

</body>
</html>