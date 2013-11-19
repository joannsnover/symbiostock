<?php 
function ss_list_categories(){
        
    ?>  <!-- panel group -->                    
        <h1>Image Categories &mdash; <span><?php bloginfo( 'name' ); ?></span></h1>
        <hr />
        
           <?php                       
            
           //list terms in a given taxonomy using wp_list_categories (also useful as a widget if using a PHP Code plugin)
           $taxonomy     = 'image-type';
           $orderby      = 'name';
           $show_count   = 1;      // 1 for yes, 0 for no
           $pad_counts   = 1;      // 1 for yes, 0 for no
           $hierarchical = 1;      // 1 for yes, 0 for no
           $title        = '';
           
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
       <?php 
}
 