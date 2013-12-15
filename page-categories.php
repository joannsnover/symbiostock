<?php
/*
Template Name: Categories Page
*/
/**
 * The template for displaying home landing page.
 *
 * @package symbiostock
 * @since symbiostock 1.0
 *
 * jas edited 11-10-2013 make a class categories-list for just the categories list as the ID content and class site-content are used for lots of pages
 */
get_header(); ?>
        <div class=row>
    
            <div id="primary" class="content-area col-md-12">
                <div id="content" class="site-content categories-list" role="main">
                <?php
                //add support for YOAST SEO
                if ( function_exists('yoast_breadcrumb') ) {
                yoast_breadcrumb('<span class="text-info" id="breadcrumbs">','</span><hr />');
                } ?>                     
                    
                
                    <?php 
                    ss_list_categories();
                    ?>
                        
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>                    
                        <div class="entry-content">
                            <?php the_content(); ?>
                            <?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'symbiostock' ), 'after' => '</div>' ) ); ?>
                            <?php edit_post_link( __( 'Edit', 'symbiostock' ), '<span class="edit-link">', '</span>' ); ?>
                        </div><!-- .entry-content -->
                    </article><!-- #post-<?php the_ID(); ?> --> 
                </div><!-- #content .site-content -->
            </div><!-- #primary .content-area -->
        
        </div>        
<?php get_footer(); ?>