<?php
/*
Template Name: Categories Page
*/
/**
 * The template for displaying home landing page.
 *
 * @package symbiostock
 * @since symbiostock 1.0
 */
get_header(); ?>
		<div class="row-fluid">
    
            <div id="primary" class="content-area span12">
                <div id="content" class="site-content" role="main">
    				<div class="hero-unit">
                    <h2>Image Categories</h2><hr />
                    <?php while ( have_posts() ) : the_post(); 
										
					$symbiostock_terms = get_terms( 'image-type', 'orderby=count&hide_empty=0' );
					$count = count($symbiostock_terms); $i=0;
					if ($count > 0) {
						$cape_list = '<p class="my_term-archive">';
						$term_list = '';
						foreach ($symbiostock_terms as $term) {
							$i++;
							$term_list .= '<a href="/image-type/' . $term->slug . '" title="' . sprintf(__('View all images  under %s', 'my_localization_domain'), $term->name) . '">' . $term->name . '</a> (' . $term->count. ')<br />';
							if ($count != $i) $term_list .= ' &middot; '; else $term_list .= '</p>';
						}
						echo $term_list;
					}			
					
					?>
					</div>               
                        
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <header class="entry-header">
                          
                        </header><!-- .entry-header -->
                    
                        <div class="entry-content">
                            <?php the_content(); ?>
                            <?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'symbiostock' ), 'after' => '</div>' ) ); ?>
                            <?php edit_post_link( __( 'Edit', 'symbiostock' ), '<span class="edit-link">', '</span>' ); ?>
                        </div><!-- .entry-content -->
                    </article><!-- #post-<?php the_ID(); ?> -->
                    
                    <?php endwhile; // end of the loop. ?>
    
                </div><!-- #content .site-content -->
            </div><!-- #primary .content-area -->
        
		</div>        
<?php get_footer(); ?>