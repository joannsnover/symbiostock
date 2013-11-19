<?php
/*
Template Name: Interactive Categories 
*/
/**
 * The template for displaying home landing page.
 *
 * @package symbiostock
 * @since symbiostock 1.0
 */
get_header(); ?>
        <div class=row>
    
            <div id="primary" class="content-area col-md-12">
                <div id="content" class="site-content" role="main">
                <?php
                //add support for YOAST SEO
                if ( function_exists('yoast_breadcrumb') ) {
                yoast_breadcrumb('<span class="text-info" id="breadcrumbs">','</span><hr />');
                } ?>                     
                                
                <div class="panel-group" id="accordion"> 
                       <?php 
                       $taxonomies = array(
                               'image-type',
                       );
                        $args = array(
                            'orderby'       => 'name', 
                            'order'         => 'ASC',
                            'hide_empty'    => true, 
                            'exclude'       => array(), 
                            'exclude_tree'  => array(), 
                            'include'       => array(),
                            'number'        => '', 
                            'fields'        => 'all', 
                            'slug'          => '', 
                            'parent'         => '',
                            'hierarchical'  => true, 
                            'child_of'      => 0, 
                            'get'           => '', 
                            'name__like'    => '',
                            'pad_counts'    => true, 
                            'offset'        => '', 
                            'search'        => '', 
                            'cache_domain'  => 'core'
                        ); 
                     $terms = get_terms( $taxonomies, $args); 
               
                              
                     $count = count($terms);
                     if ( $count > 0 ){
                         
                         $category_count = 0;
            
                         $id_key_pairs = array();
                         
                         
                         foreach($terms as $key => $term){ 
                             if($term->parent > 0){
                                 $id_key_pairs[$key]['child'] = $term->term_id;                                                                
                             } else {
                                 $id_key_pairs[$key]['child'] = 0;                                 
                             }                           
                         }                    
            
                         
                         
                         foreach ( $terms as $term ) {
                           $category_count++;
                           $link = get_term_link( $term->slug, 'image-type' );                             
                           
                           if($term->parent > 0){
                               $parent = 'data-parent="'.$term->parent.'"';
                               $child_icon = '<i class="icon-long-arrow-right"> </i> ';
                               $width = 12;
                               $panel_mod = 'panel-child';                                
                           } else {
                               $parent = '';  
                               $child_icon = '';  
                               $width = 12;  
                               $panel_mod = 'panel';                         
                           }
                           
                           ?>
                           
                           
                        <!-- PANEL GROUP -->   
                        <div id="panel_list_<?php echo $term->term_id; ?>" <?php echo $parent ?> class="<?php echo $panel_mod ?> ss-category-list col-md-<?php echo $width ?>">
                            <div class="panel-heading">
                                <p class="panel-title">                                                                                                   
                                    <a title="<?php echo $term->name ?>" href="<?php echo $link ?>">
                                        <?php echo $child_icon ?>
                                        <?php echo $term->name ?> (<?php echo $term->count ?>) 
                                    </a>
                                    <a title="<?php echo $term->name ?>" class="accordion-toggle pull-right" data-toggle="collapse" data-parent="#accordion" href="#category_<?php echo $category_count; ?>">
                                        <span class="label label-primary">
                                            <i class="icon-double-angle-down"> </i> 
                                        </span> 
                                    </a>                                     
                                </p>
                            </div>
                            <div id="category_<?php echo $category_count; ?>" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <!-- IMAGES --> 
                                    <?php 
                                    $args = array(
                                            'post_type' => 'image',
                                            'post_status' => 'publish',                               
                                            'posts_per_page' => 6,
                                            'caller_get_posts' => 6,
                                            'tax_query' => array(
                                            array(
                                                    'taxonomy' => 'image-type',
                                                    'field' => 'id',
                                                    'terms' => $term->term_id,
                                                    )
                                                ),
                                            'fields' => 'ids' );
                                    $count = 0;
                                    $cat_images = new WP_Query($args);
                                    
                                    while ( $cat_images->have_posts() && $count <= 5) :
                                    $cat_images->the_post();
            
                                    ?>
                                    <div class="col-md-2 image-category"> 
                                        <a class="thumbnail" title="<?php echo $term->name ?>" href="<?php echo $link ?>">
                                            <?php if ( has_post_thumbnail() ) { the_post_thumbnail( ); } ?>
                                        </a> 
                                    </div>
                                    <?php
                                    $count++;                            
                                    endwhile;   
                                    
                                    ?>
                                    <div class="clearfix"></div>
                                    
                                    <p class="text-center">
                                        <a title="<?php echo $term->name ?>" href="<?php echo $link ?>">
                                            <?php echo $term->name ?> <i class="icon-double-angle-right"> </i> 
                                        </a>
                                    </p>                             
                                    <!-- /IMAGES --> 
                                </div>
                            </div>
                        </div>
                        <!-- /PANEL GROUP -->  
                           <?php    
                            
                         }
                         
                     }
                        ?>    
                   </div> 
                   <!-- /list group -->
                        
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