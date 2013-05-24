<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package symbiostock
 * @since symbiostock 1.0
 */
get_header(); ?>
		<div class="row-fluid">
    
            <div id="primary" class="content-area span7">
                <div id="content" class="site-content" role="main">
                
				<?php
                //add support for YOAST SEO
                if ( function_exists('yoast_breadcrumb') ) {
                yoast_breadcrumb('<span class="text-info" id="breadcrumbs">','</span><hr />');
                } ?>                   
    				
					<?php symbiostock_csv_symbiocard_box($symbiocard_location = '', $compact = false) ?>
                    
                    <?php dynamic_sidebar( 'Author Page (Below Content)' ) ?>
    
                </div><!-- #content .site-content -->
            </div><!-- #primary .content-area -->
        
		
        	<div class="span5">
			<?php dynamic_sidebar( 'Author Page (Sidebar)' ) ?>
			</div>
		</div>        
<?php get_footer(); ?>