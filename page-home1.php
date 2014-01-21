<?php
/*
Template Name: HOME: Top Widget / Content->Widget / 3 CTAs)
*/
/**
 * The template for displaying home landing page.
 *
 * @package symbiostock
 * @since symbiostock 1.0
 */
get_header(); ?>
        <div class="home row">
    
            <div id="primary" class="content-area col-md-12">
            
                <?php if(is_active_sidebar( 'home-page-above-content' )): ?>
                <div class="panel panel-default ss-widget-12">
                    <?php dynamic_sidebar( 'Home Page (Above Content)' ) ?>
                </div>
                <?php endif; ?>
            
                <div id="content" class="site-content home-content row" role="main">
    
                    <?php while ( have_posts() ) : the_post(); ?>
                    <div class="col-md-6">
                        <div id="post-<?php the_ID(); ?>" <?php post_class(' panel panel-default'); ?>>
                            <header class="entry-header panel-heading">
                                <h1 class="entry-title panel-title"><?php the_title(); ?></h1>
                            </header><!-- .entry-header -->
                            <div class="panel-body">
                                <?php the_content(); ?>
                               
                                <?php edit_post_link( __( 'Edit', 'symbiostock' ), '<span class="edit-link">', '</span>' ); ?>
                            </div><!-- .entry-content -->
                        </div><!-- #post-<?php the_ID(); ?> -->
                    </div>
                    <div class="col-md-6">
                        <div class="panel panel-default ss-widget-6">
                        
                        <?php dynamic_sidebar( 'home-page-beside-content' ) ?>
                        
                        </div>
                    </div>                
    
                    <?php endwhile; // end of the loop. ?>
    
                </div><!-- #content .site-content -->
            
            
            <?php if(is_dynamic_sidebar( 'home-page-above-content' )): ?>
            <div class="panel panel-default ss-widget-12">
           
            <?php dynamic_sidebar( 'home-page-below-content' ) ?>
            
            </div>
            <?php endif; ?>
            
            
            <?php if(                     
                     is_active_sidebar( 'cta-1' ) ||
                     is_active_sidebar( 'cta-2' ) ||
                     is_active_sidebar( 'cta-3' )
                     ): ?>
            
            <div class="row">
                
                <div class="col-md-4">
                    <div class="panel panel-default">
                    <?php dynamic_sidebar( 'cta-1' ) ?>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="panel panel-default">
                    <?php dynamic_sidebar( 'cta-2' ) ?>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="panel panel-default">
                    <?php dynamic_sidebar( 'cta-3' ) ?>
                    </div>
                </div>
                
            </div>
            
            <?php endif; ?>
         
            </div><!-- #primary .content-area -->
            
        </div>        
        
        
<?php get_footer(); ?>