<?php
class symbiostock_featured_images extends WP_Widget{
    
    public function __construct() {
        //widget actual process
        
        parent::__construct(
        
            'symbiostock_featured_images',
            
            __('Featured Images', 'symbiostock'),
            
            array( 'description' => __( 'Symbiostock featured images below content area.', 'symbiostock' ) )
        
        );
        
    }
        
    public function form( $instance ) {
        
        //outputs the options form on Admin screen
        
        $title = (isset( $instance[ 'title' ])) ? $instance[ 'title' ] : __('Featured Images', 'symbiostock');
        
        ?>
<p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>">
        <?php _e( 'Title: ', 'symbiostock' ); ?>
    </label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value ="<?php echo esc_attr( $title ); ?>" />
    <br />
    <br />
    <?php _e( 'Symbiostock installs with a <em><strong>Symbiostock Featured Images</strong></em> category. Images in this category will show up where this widget is added. 6 images suggested max.', 'symbiostock') ?>
</p>
<?php
                
        }    
        
    public function update( $new_instance, $old_instance ) {
        
        //process widget options to be saved
        
        $instance = array();
        
        $instance['title'] = strip_tags( $new_instance['title'] );
        
        return $instance;
        
        }
        
    public function widget( $args, $instance ){
        
        //outputs the content of the widget   
        
        
        extract( $args );
        
        $title = apply_filters( 'widget_title', $instance[ 'title' ] );
        
        echo $before_widget;
        
        if ( !empty( $title ) ) echo $before_title . '<i class="icon-star-empty"> </i> ' . $title . $after_title;
        
        $featured_images_id = get_option('symbiostock_featured_images', '');
        
        if(!empty($featured_images_id)){
                
            $args = array(
            
            'post_type' => 'image',
            'order' => 'asc',                         
            'posts_per_page' => -1,
            'caller_get_posts' => 1,
            'tax_query' => array(
                array(
                        'taxonomy' => 'image-type',
                        'field' => 'id',
                        'terms' => $featured_images_id,
                        )
                    )                
            );
        
        } else {
                        
            echo __('<p>Featured Images category does not exist. Has it been deleted? Please re-activate theme and place featured images into "Symbiostock Featured Images" category.</p>', 'symbiostock');
            
            }
        $featuredWidget = new WP_Query($args);
        
        ?>
<div class="row  ">
    <?php 
        
        while ( $featuredWidget->have_posts() ) : 
            $featuredWidget->the_post(); 
                                
            ?>
    <div class="widget-featured symbiostock-featured search-result">
        <div class="inner-featured">
            <div class="thumb"> 
                <a title="<?php the_title(); ?>" href="<?php the_permalink(); ?>">
                    <?php if ( has_post_thumbnail() ) { the_post_thumbnail(  ); } ?>
                </a> 
            </div>
            
        </div>
    </div>
    <?php
        endwhile;   
        
        ?>
</div>
<?php 
        
        wp_reset_postdata();
        
        echo $after_widget; 
        
    }
    
}
register_widget( 'symbiostock_featured_images' );

class symbiostock_latest_images extends WP_Widget{
    
    public function __construct() {
        //widget actual process
        
        parent::__construct(
        
            'symbiostock_latest_images',
            
            __('Latest Images', 'symbiostock'),
            
            array( 'description' => __( 'Displays your latest images on whatever widget you assign it to.', 'symbiostock' ) )
        
        );
        
    }
        
    public function form( $instance ) {
        
        //outputs the options form on Admin screen
        
        $title = (isset( $instance[ 'title' ])) ? $instance[ 'title' ] : __('Latest Images', 'symbiostock');
        
        ?>
<p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>">
        <?php _e( 'Title: ', 'symbiostock' ); ?>
    </label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value ="<?php echo esc_attr( $title ); ?>" />
    <br />
    <br />
    <?php _e( 'Displays your latest images!', 'symbiostock') ?>
</p>
<?php
                
        }    
        
    public function update( $new_instance, $old_instance ) {
        
        //process widget options to be saved
        
        $instance = array();
        
        $instance['title'] = strip_tags( $new_instance['title'] );
        
        return $instance;
        
        }
        
    public function widget( $args, $instance ){
        
        //outputs the content of the widget           
        
        extract( $args );
        
        $title = apply_filters( 'widget_title', $instance[ 'title' ] );
        
        echo $before_widget;
        
        if ( !empty( $title ) ) echo $before_title . '<i class="icon-eye-open"> </i> ' .  $title . ' ' . symbiostock_feed('rss_url', 'icon', 'new-images') . $after_title;
                
        $args = array(        
            'post_type' => 'image',       
            'showposts' => 6,        
        );    
        
        $featuredWidget = new WP_Query($args);
        
        ?>
<div class="row  ">
    <?php 
        
        while ( $featuredWidget->have_posts() ) : 
            $featuredWidget->the_post(); 
                                
            ?>
    <div class="widget-featured symbiostock-latest search-result">
        <div class="inner-latest">
            <div class="thumb"> <a title="<?php the_title(); ?>" href="<?php the_permalink(); ?>">
                <?php if ( has_post_thumbnail() ) { the_post_thumbnail(  ); } ?>
                </a> 
            </div>
        </div>
    </div>
    <?php
        endwhile;   
        
        ?>
</div>
<?php 
        
        wp_reset_postdata();
        
        echo $after_widget; 
        
    }
    
}
register_widget( 'symbiostock_latest_images' );
class symbiostock_mobile_navigation extends WP_Widget{
    
    public function __construct() {
        
        //widget actual process
        
        parent::__construct(
        
            'symbiostock_mobile_nav',
            
            __('Mobile Navigation', 'symbiostock'),
            
            array( 'description' => __( 'Dropdown menu of main navigation for devices with small screens.', 'symbiostock' ) )
        
        );
        
    }
        
    public function form( $instance ) {
        
        //outputs the options form on Admin screen
        
        $title = (isset( $instance[ 'title' ])) ? $instance[ 'title' ] : __('Mobile Navigation', 'symbiostock');
        
        ?>
<p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>">
        <?php _e( 'Title: ', 'symbiostock' ); ?>
    </label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value ="<?php echo esc_attr( $title ); ?>" />
</p>
<?php
                
        }    
        
    public function update( $new_instance, $old_instance ) {
        
        //process widget options to be saved
        
        $instance = array();
        
        $instance['title'] = strip_tags( $new_instance['title'] );
        
        return $instance;
        
        }
        
    public function widget( $args, $instance ){
        
        //outputs the content of the widget
        
        extract( $args );
        
        $title = apply_filters( 'widget_title', $instance[ 'title' ] );
        
        echo $before_widget;
        
         symbiostock_mobile_menu();
        
        echo $after_widget; 
        
    }
    
}
register_widget( 'symbiostock_mobile_navigation' );

//LATEST IMAGES SLIDER
class symbiostock_latest_images_preview_slider extends WP_Widget{
    
    public function __construct() {
        
        //widget actual process
        
        parent::__construct(
        
            'symbiostock_latest_images_preview_slider',
            
            __('Latest Images Slider (Large)', 'symbiostock'),
            
            array( 'description' => __( 'Shows latest images (preview size).', 'symbiostock' ) )
        
        );
        
    }
        
    public function form( $instance ) {
        
        //outputs the options form on Admin screen
        
        $title = (isset( $instance[ 'title' ])) ? $instance[ 'title' ] : __('Latest Images', 'symbiostock');
        
        ?>
<p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>">
        <?php _e( 'Title: ' ); ?>
    </label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value ="<?php echo esc_attr( $title ); ?>" /><br />
    <p><?php _e( 'This widget displays sliding previews of <strong>latest</strong> images. Also utilizes <strong>shortcodes</strong> for your freedom of use: ', 'symbiostock') ?> <?php echo sshelp('shortcodes', __('See Shortcodes Help', 'symbiostock')); ?></p>
</p>
<?php
                
        }    
        
    public function widget( $args, $instance )
{
        
    //outputs the content of the widget
    
    extract( $args );
    
    $tags = wp_get_object_terms( $image_ID, 'image-tags' );
    
    $title = apply_filters( 'widget_title', $instance[ 'title' ] );
    
        
        echo $before_widget;
    
        if ( !empty( $title ) )
            echo $before_title . $title . $after_title;
        
        symbiostock_image_slider( 'sscarousel_previews_latest', 'preview', 'latest' );
    
        echo '<div class="clearfix"></div>';
        
        echo $after_widget;
    
}
    
}
register_widget( 'symbiostock_latest_images_preview_slider' );

//LATEST IMAGES SLIDER (Small)
class symbiostock_latest_images_minipic_slider extends WP_Widget{
    
    public function __construct() {
        
        //widget actual process
        
        parent::__construct(
        
            'symbiostock_latest_images_minipic_slider',
            
            __('Latest Images Slider (Small)', 'symbiostock'),
            
            array( 'description' => __( 'Shows latest images (minipic size).', 'symbiostock' ) )
        
        );
        
    }
        
    public function form( $instance ) {
        
        //outputs the options form on Admin screen
        
        $title = (isset( $instance[ 'title' ])) ? $instance[ 'title' ] : __('Latest Images', 'symbiostock');
        
        ?>
<p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>">
        <?php _e( 'Title: ', 'symbiostock' ); ?>
    </label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value ="<?php echo esc_attr( $title ); ?>" /><br />
    <p><?php _e( 'This widget displays sliding minipics of <strong>latest</strong> images. Also utilizes <strong>shortcodes</strong> for your freedom of use:', 'symbiostock') ?>
     <?php echo sshelp('shortcodes', __('See Shortcodes Help', 'symbiostock')); ?></p>
</p>
<?php
                
        }    
        
    public function widget( $args, $instance )
{
        
    //outputs the content of the widget
    
    extract( $args );
    
    $tags = wp_get_object_terms( $image_ID, 'image-tags' );
    
    $title = apply_filters( 'widget_title', $instance[ 'title' ] );
    
        
        echo $before_widget;
    
        if ( !empty( $title ) )
            echo $before_title . $title . $after_title;
        
        symbiostock_image_slider( 'sscarousel_minipics_latest', 'minipic', 'latest' );
    
        echo '<div class="clearfix"></div>';
        
        echo $after_widget;
    
}
    
}
register_widget( 'symbiostock_latest_images_minipic_slider' );

//featured IMAGES SLIDER
class symbiostock_featured_images_preview_slider extends WP_Widget{
    
    public function __construct() {
        
        //widget actual process
        
        parent::__construct(
        
            'symbiostock_featured_images_preview_slider',
            
            __('Featured Images Slider (Large)', 'symbiostock'),
            
            array( 'description' => __( 'Shows featured images (preview size).', 'symbiostock' ) )
        
        );
        
    }
        
    public function form( $instance ) {
        
        //outputs the options form on Admin screen
        
        $title = (isset( $instance[ 'title' ])) ? $instance[ 'title' ] : __('Featured Images', 'symbiostock');
        
        ?>
<p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>">
        <?php _e( 'Title: ', 'symbiostock' ); ?>
    </label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value ="<?php echo esc_attr( $title ); ?>" /><br />
    <p><?php _e( 'This widget displays sliding previews of <strong>featured</strong> images. Also utilizes <strong>shortcodes</strong> for your freedom of use:', 'symbiostock') ?>
     <?php echo sshelp('shortcodes', __('See Shortcodes Help', 'symbiostock')); ?></p>
</p>
<?php
                
        }    
        
    public function widget( $args, $instance )
{
        
    //outputs the content of the widget
    
    extract( $args );
    
    $tags = wp_get_object_terms( $image_ID, 'image-tags' );
    
    $title = apply_filters( 'widget_title', $instance[ 'title' ] );
    
        
        echo $before_widget;
    
        if ( !empty( $title ) )
            echo $before_title . $title . $after_title;
        
        symbiostock_image_slider( 'sscarousel_previews_featured', 'preview', 'featured' );
    
        echo '<div class="clearfix"></div>';
        
        echo $after_widget;
    
}
    
}
register_widget( 'symbiostock_featured_images_preview_slider' );

//featured IMAGES SLIDER
class symbiostock_featured_images_minipic_slider extends WP_Widget{
    
    public function __construct() {
        
        //widget actual process
        
        parent::__construct(
        
            'symbiostock_featured_images_minipic_slider',
            
            __('Featured Images Slider (Small)', 'symbiostock'),
            
            array( 'description' => __( 'Shows featured images (minipic size).', 'symbiostock' ) )
        
        );
        
    }
        
    public function form( $instance ) {
        
        //outputs the options form on Admin screen
        
        $title = (isset( $instance[ 'title' ])) ? $instance[ 'title' ] : 'Featured Images';
        
        ?>
<p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>">
        <?php _e( 'Title: ', 'symbiostock' ); ?>
    </label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value ="<?php echo esc_attr( $title ); ?>" /><br />
    <p><?php _e( 'This widget displays sliding minipics of <strong>featured</strong> images. Also utilizes <strong>shortcodes</strong> for your freedom of use:', 'symbiostock') ?> <?php echo sshelp('shortcodes', __('See Shortcodes Help', 'symbiostock')); ?></p>
</p>
<?php
                
        }    
        
    public function widget( $args, $instance )
{
        
    //outputs the content of the widget
    
    extract( $args );
    
    $tags = wp_get_object_terms( $image_ID, 'image-tags' );
    
    $title = apply_filters( 'widget_title', $instance[ 'title' ] );
    
        
        echo $before_widget;
    
        if ( !empty( $title ) )
            echo $before_title . $title . $after_title;
        
        symbiostock_image_slider( 'sscarousel_minipics_featured', 'minipic', 'featured' );
    
        echo '<div class="clearfix"></div>';
        
        echo $after_widget;
    
}
    
}
register_widget( 'symbiostock_featured_images_minipic_slider' );


//Network Members Detailed Widget
class symbiostock_network_members extends WP_Widget{
    
    public function __construct() {
        
        //widget actual process
        
        parent::__construct(
        
            'symbiostock_network_members',
            
            __('Symbiostock Network (Detailed)', 'symbiostock'),
            
            array( 'description' => __( 'Detailed list of your site network members.', 'symbiostock' ) )
        
        );
        
    }
        
    public function form( $instance ) {
        
        //outputs the options form on Admin screen
        
        $title = (isset( $instance[ 'title' ])) ? $instance[ 'title' ] : __('Network Members', 'symbiostock');
        
        ?>
<p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>">
        <?php _e( 'Title: ', 'symbiostock' ); ?>
    </label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value ="<?php echo esc_attr( $title ); ?>" /><br />
    <p><?php _e( 'Displays your network members in a detailed listing.', 'symbiostock') ?></p>
</p>
<?php
                
        }    
        
    public function widget( $args, $instance )
{
        
    //outputs the content of the widget
    
    extract( $args );
    
    $title = apply_filters( 'widget_title', $instance[ 'title' ] );
    
        
        echo $before_widget;
    
        if ( !empty( $title ) )
            echo $before_title . $title . $after_title;
        
        $networks = new network_manager();
        $networks->list_all_networks();
    
        echo '<div class="clearfix"></div>';
        
        echo $after_widget;
    
}
    
}
register_widget( 'symbiostock_network_members' );


//Network Members simple Widget
class symbiostock_network_members_simple extends WP_Widget{
    
    public function __construct() {
        
        //widget actual process
        
        parent::__construct(
        
            'symbiostock_network_members_simple',
            
            __('Symbiostock Network (Simple)', 'symbiostock'),
            
            array( 'description' => __( 'simple list of your site network members.', 'symbiostock' ) )
        
        );
        
    }
        
    public function form( $instance ) {
        
        //outputs the options form on Admin screen
        
        $title = (isset( $instance[ 'title' ])) ? $instance[ 'title' ] : __('Network Members', 'symbiostock');
        
        ?>
<p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>">
        <?php _e( 'Title: ', 'symbiostock' ); ?>
    </label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value ="<?php echo esc_attr( $title ); ?>" /><br />
    <p><?php _e( 'Displays your network members in a simple listing.', 'symbiostock') ?></p>
</p>
<?php
                
        }    
        
    public function widget( $args, $instance )
{
        
    //outputs the content of the widget
    
    extract( $args );
    
    $title = apply_filters( 'widget_title', $instance[ 'title' ] );
    
        
        echo $before_widget;
    
        if ( !empty( $title ) )
            echo $before_title . $title . $after_title;
        
        $networks = new network_manager();
        $networks->list_all_networks(true);
    
        echo '<div class="clearfix"></div>';
        
        echo $after_widget;
    
}
    
}
register_widget( 'symbiostock_network_members_simple' );

//Network Directory Widget
class symbiostock_network_directory extends WP_Widget{
    
    public function __construct() {
        
        //widget actual process
        
        parent::__construct(
        
            'symbiostock_network_directory',
            
            __('Symbiostock Network Directory', 'symbiostock'),
            
            array( 'description' => __( 'Network directory, automatically scanned / created.', 'symbiostock' ) )
        
        );
        
    }
        
    public function form( $instance ) {
      
        
        ?><p><?php _e( 'A prominent widget which links to your site\'s Symbiostock directory (an area automatically created and maintained by your site).', 'symbiostock') ?></p><?php
                
        }    
        
    public function widget( $args, $instance )
{
        
    //outputs the content of the widget
    
        extract( $args );
            
        if ( !empty( $title ) )
            echo $before_title . $title . $after_title;
        
        get_template_part('sub-search');
    
        echo '<div class="clearfix"></div>';        
    
}
    
}
register_widget( 'symbiostock_network_directory' );

//similar images widget 

class symbiostock_similar_images extends WP_Widget{
    
    public function __construct() {
        
        //widget actual process
        
        parent::__construct(
        
            'symbiostock_similar_images',
            
            __('Symbiostock - Similar Images', 'symbiostock'),
            
            array( 'description' => __( 'Shows related images on a given image page.', 'symbiostock' ) )
        
        );
        
    }
        
    public function form( $instance ) {
        
        //outputs the options form on Admin screen
        
        $title = (isset( $instance[ 'title' ])) ? $instance[ 'title' ] : __('Similar Images', 'symbiostock');
        
        ?>
<p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>">
        <?php _e( 'Title: ', 'symbiostock' ); ?>
    </label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value ="<?php echo esc_attr( $title ); ?>" />
</p>
<?php
                
        }    
        
    public function widget( $args, $instance )
{
    
    //get current image id
    global $wp_query;
    
    $wp_query->set( 'posts_per_page' , 144 );
    
    $post = $wp_query->post;
    
    $image_ID = $post->ID;
    
    //outputs the content of the widget
    
    extract( $args );
    
    $tags = wp_get_object_terms( $image_ID, 'image-tags' );
    
    $title = apply_filters( 'widget_title', $instance[ 'title' ] );
    
    $post_type = get_post_type();
        
        if($post_type=='image'){
        
            echo $before_widget;
    
        if ( !empty( $title ) )
            echo $before_title . '<i class="icon-bullseye"> </i> ' .  $title . $after_title;
        
        //this related images code was derived from here: http://www.wprecipes.com/how-to-show-related-posts-without-a-plugin        
                                    
                    
            $args = array(
                'post_types'     => 'image', // string or array with multiple post type names
                'posts_per_page' => 12, // return 5 posts
                'order'          => 'DESC',
                'orderby'        => '',
                'exclude_terms'  => '', // array with term IDs
                'exclude_posts'  => array($post->ID), // array with post IDs
                'limit_posts'    => -1, // don't limit posts
                'limit_year'     => '',
                'limit_month'    => '',
                'fields'         => 'all', // return post objects 
            );
            
            $taxonomies = array( 'image-tags' );
            
            if( function_exists( 'km_rpbt_related_posts_by_taxonomy' ) ) {
                    
                $related_images = km_rpbt_related_posts_by_taxonomy( $post->ID, $taxonomies, $args  );
            }        
                
            ?> <div class=""> <?php
            if($related_images){
                foreach ( (array) $related_images as $image ) {    
                
                $attachment_id = get_post_meta($image->ID, 'symbiostock_minipic_id');
                
                ?> 
                    <div class="col-md-6">
                        <div class="similars-container">                                                                            
                        <a class="thumbnail" title="<?php echo $image->post_title; ?>" href="<?php echo get_permalink( $image->ID ); ?>">
                            <?php echo wp_get_attachment_image( $attachment_id[0], 'full' ); ?>
                        </a> 
                        </div>                                            
                    </div>   
                <?php                 
                }
                
            }
            ?> </div> <?php
            wp_reset_postdata();
            
        echo '<div class="clearfix"></div>';
        
        echo $after_widget;
        
        }
    
}
  
}
register_widget( 'symbiostock_similar_images' );


?>