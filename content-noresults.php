<?php
/**
 * @package symbiostock
 * @since symbiostock 1.0
 */ 
?>
    <div id="primary" class="content-area col-md-">
        <div id="content" class="site-content col-md-12" role="main">
            <article id="post-0" class="post error404 not-found">
                <header class="entry-header">
                    <h1 class="entry-title"><?php _e( 'No results found for ' . ucwords(urldecode (get_query_var('image-tags'))), 'symbiostock' ); ?></h1>
                </header><!-- .entry-header -->
                <div class="entry-content">
                    
           <div class="jumbotron">
                    <h2>No Results found. Try browsing the categories. Maybe you will find some hidden gems!</h2>                    
                   <?php
                   
                   //list terms in a given taxonomy using wp_list_categories (also useful as a widget if using a PHP Code plugin)
                    $taxonomy     = 'image-type';
                    $orderby      = 'name'; 
                    $show_count   = 1;      // 1 for yes, 0 for no
                    $pad_counts   = 1;      // 1 for yes, 0 for no
                    $hierarchical = 1;      // 1 for yes, 0 for no
                    $title        = '<h2 class="muted">Image Categories</h2><hr />';
                    
                    $args = array(
                      'taxonomy'     => $taxonomy,
                      'orderby'      => $orderby,
                      'show_count'   => $show_count,
                      'pad_counts'   => $pad_counts,
                      'hierarchical' => $hierarchical,
                      'title_li'     => $title,
                     
                    );
                    ?>
                    
                    <ul>
                    <?php wp_list_categories( $args ); ?>
                    </ul>                   
                    </div>    
                    
                </div><!-- .entry-content -->
            </article><!-- #post-0 .post .error404 .not-found -->
        </div><!-- #content .site-content -->
    </div><!-- #primary .content-area -->    