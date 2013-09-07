<?php
/**
 * The template for displaying image category pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package symbiostock
 * @since symbiostock 1.0
 */

get_header(); ?>
    <div class=row>
        <section id="primary" class="content-area">
            <div id="content" class="site-content col-md-12" role="main">
            
            <?php
            //add support for YOAST SEO
             if ( function_exists('yoast_breadcrumb') ) {
            yoast_breadcrumb('<colspan class="text-info" id="breadcrumbs">','</colspan>');
            } ?>
    
            
            <?php
                        
            $term = get_term_by( 'slug', get_query_var( 'Collection' ),  'Collection' );        
            $title = $term->name;            
                    
            ?>
            <h1 class="results_for">Images in Collection: <strong><?php echo  ucwords($title)  ?></strong><?php echo symbiostock_feed('rss_url','icon', 'Collection') ?></h1>;
            <?php
            
                $args = array(
                   'post_type' => 'image',
                   'posts_per_page' => -1,
                   'tax_query' => array(
                      array(
                         'taxonomy' => 'Collection',                         
                         'terms' => array($term->term_id),            
                      )
                   )
                );
        
                // The Query
                $query = new WP_Query( $args );
                
                // The Loop
                if ( $query->have_posts() ) {
                    while ( $query->have_posts() ) {
                        $query->the_post();
                    $minipic = get_post_meta(get_the_id(), 'symbiostock_minipic');                            
                    ?>
                    <div class="search-result">
                        <div class="ss-result-inner">
                            <img class="<?php  ?>" alt="<?php echo $image->ID ?>" src="<?php echo $minipic[0] ?>" />                        
                        </div>                                        
                    </div>        
                    <?php    
                    
                    }
                } else {
                    // no posts found
                }
                /* Restore original Post Data */
                wp_reset_postdata();
                        
                ?>
                   
            </div><!-- #content .site-content -->
        </section><!-- #primary .content-area -->
    
    </div>
    <div class="clearfix">&nbsp;</div>    

    
<?php get_footer(); ?>