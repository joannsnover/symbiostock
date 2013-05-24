<?php
class symbiostock_featured_images extends WP_Widget{
    
    public function __construct() {
        //widget actual process
        
        parent::__construct(
        
            'symbiostock_featured_images',
            
            'Featured Images',
            
            array( 'description' => __( 'Symbiostock featured images below content area.' ) )
        
        );
        
    }
        
    public function form( $instance ) {
        
        //outputs the options form on Admin screen
        
        $title = (isset( $instance[ 'title' ])) ? $instance[ 'title' ] : 'Featured Images';
        
        ?>
<p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>">
        <?php _e( 'Title: ' ); ?>
    </label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value ="<?php echo esc_attr( $title ); ?>" />
    <br />
    <br />
    Symbiostock installs with a <em><strong>Symbiostock Featured Images</strong></em> category. Images in this category will show up where this widget is added. 6 images suggested max.
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
        
        if ( !empty( $title ) ) echo $before_title . $title . $after_title;
        
		$featured_images_id = get_option('symbiostock_featured_images', '');
		
		if(!empty($featured_images_id)){
		        
			$args = array(
			
			'post_type' => 'image',       
			'posts_per_page' => 4,
			'tax_query' => array(
				array(
						'taxonomy' => 'image-type',
						'field' => 'id',
						'terms' => $featured_images_id,
						)
					)				
			);
        
		} else {
						
			echo '<p>Featured Images category does not exist. Has it been deleted? Please re-activate theme and place featured images into "Symbiostock Featured Images" category.</p>';
			
			}
        $featuredWidget = new WP_Query($args);
        
		?>
<div class="row-fluid front-page-featured">
    <?php 
		
        while ( $featuredWidget->have_posts() ) : 
            $featuredWidget->the_post(); 
								
			?>
    <div class="widget-featured symbiostock-featured search-result">
        <div class="inner-featured">
            <div class="thumb"> <a title="<?php the_title(); ?>" href="<?php the_permalink(); ?>">
                <?php if ( has_post_thumbnail() ) { the_post_thumbnail(  ); } ?>
                </a> </div>
      <!--  <a title="<?php the_title(); ?>" href="<?php the_permalink(); ?>">
            <?php the_title(); ?>
            </a>
            <?php  the_excerpt( ); ?>
            
            <p class="entry-date"><i class="icon-calendar"> </i>
                <?php the_time('F j, Y'); ?>
            </p>-->
            
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
            
            'Latest Images',
            
            array( 'description' => __( 'Displays your latest images on whatever widget you assign it to.' ) )
        
        );
        
    }
        
    public function form( $instance ) {
        
        //outputs the options form on Admin screen
        
        $title = (isset( $instance[ 'title' ])) ? $instance[ 'title' ] : 'Latest Images';
        
        ?>
<p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>">
        <?php _e( 'Title: ' ); ?>
    </label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value ="<?php echo esc_attr( $title ); ?>" />
    <br />
    <br />
    Displays your latest images!
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
        
        if ( !empty( $title ) ) echo $before_title . $title . ' ' . symbiostock_feed('rss_url', 'icon', 'new-images') . $after_title;
                
        $args = array(        
			'post_type' => 'image',       
			'showposts' => 6,		
		);	
        
        $featuredWidget = new WP_Query($args);
        
		?>
<div class="row-fluid front-page-featured">
    <?php 
		
        while ( $featuredWidget->have_posts() ) : 
            $featuredWidget->the_post(); 
								
			?>
    <div class="widget-featured symbiostock-latest search-result">
        <div class="inner-latest">
            <div class="thumb"> <a title="<?php the_title(); ?>" href="<?php the_permalink(); ?>">
                <?php if ( has_post_thumbnail() ) { the_post_thumbnail(  ); } ?>
                </a> </div>
            
            <p class="entry-date"><i class="icon-calendar"> </i>
                <?php the_time('F j, Y'); ?>
            </p>
            
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
            
            'Mobile Navigation',
            
            array( 'description' => __( 'Dropdown menu of main navigation for devices with small screens.' ) )
        
        );
        
    }
        
    public function form( $instance ) {
        
        //outputs the options form on Admin screen
        
        $title = (isset( $instance[ 'title' ])) ? $instance[ 'title' ] : 'Mobile Navigation';
        
        ?>
<p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>">
        <?php _e( 'Title: ' ); ?>
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
            
            'Latest Images Slider (Large)',
            
            array( 'description' => __( 'Shows latest images (preview size).' ) )
        
        );
        
    }
        
    public function form( $instance ) {
        
        //outputs the options form on Admin screen
        
        $title = (isset( $instance[ 'title' ])) ? $instance[ 'title' ] : 'Latest Images';
        
        ?>
<p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>">
        <?php _e( 'Title: ' ); ?>
    </label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value ="<?php echo esc_attr( $title ); ?>" /><br />
    <p>This widget displays sliding previews of <strong>latest</strong> images. Also utilizes <strong>shortcodes</strong> for your freedom of use: <?php echo sshelp('shortcodes', 'See Shortcodes Help'); ?></p>
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
            
            'Latest Images Slider (Small)',
            
            array( 'description' => __( 'Shows latest images (minipic size).' ) )
        
        );
        
    }
        
    public function form( $instance ) {
        
        //outputs the options form on Admin screen
        
        $title = (isset( $instance[ 'title' ])) ? $instance[ 'title' ] : 'Latest Images';
        
        ?>
<p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>">
        <?php _e( 'Title: ' ); ?>
    </label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value ="<?php echo esc_attr( $title ); ?>" /><br />
    <p>This widget displays sliding minipics of <strong>latest</strong> images. Also utilizes <strong>shortcodes</strong> for your freedom of use: <?php echo sshelp('shortcodes', 'See Shortcodes Help'); ?></p>
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
            
            'Featured Images Slider (Large)',
            
            array( 'description' => __( 'Shows featured images (preview size).' ) )
        
        );
        
    }
        
    public function form( $instance ) {
        
        //outputs the options form on Admin screen
        
        $title = (isset( $instance[ 'title' ])) ? $instance[ 'title' ] : 'Featured Images';
        
        ?>
<p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>">
        <?php _e( 'Title: ' ); ?>
    </label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value ="<?php echo esc_attr( $title ); ?>" /><br />
    <p>This widget displays sliding previews of <strong>featured</strong> images. Also utilizes <strong>shortcodes</strong> for your freedom of use: <?php echo sshelp('shortcodes', 'See Shortcodes Help'); ?></p>
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
            
            'Featured Images Slider (Small)',
            
            array( 'description' => __( 'Shows featured images (minipic size).' ) )
        
        );
        
    }
        
    public function form( $instance ) {
        
        //outputs the options form on Admin screen
        
        $title = (isset( $instance[ 'title' ])) ? $instance[ 'title' ] : 'Featured Images';
        
        ?>
<p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>">
        <?php _e( 'Title: ' ); ?>
    </label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value ="<?php echo esc_attr( $title ); ?>" /><br />
    <p>This widget displays sliding minipics of <strong>featured</strong> images. Also utilizes <strong>shortcodes</strong> for your freedom of use: <?php echo sshelp('shortcodes', 'See Shortcodes Help'); ?></p>
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
            
            'Symbiostock Network (Detailed)',
            
            array( 'description' => __( 'Detailed list of your site network members.' ) )
        
        );
        
    }
        
    public function form( $instance ) {
        
        //outputs the options form on Admin screen
        
        $title = (isset( $instance[ 'title' ])) ? $instance[ 'title' ] : 'Network Members';
        
        ?>
<p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>">
        <?php _e( 'Title: ' ); ?>
    </label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value ="<?php echo esc_attr( $title ); ?>" /><br />
    <p>Displays your network members in a detailed listing.</p>
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
            
            'Symbiostock Network (Simple)',
            
            array( 'description' => __( 'simple list of your site network members.' ) )
        
        );
        
    }
        
    public function form( $instance ) {
        
        //outputs the options form on Admin screen
        
        $title = (isset( $instance[ 'title' ])) ? $instance[ 'title' ] : 'Network Members';
        
        ?>
<p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>">
        <?php _e( 'Title: ' ); ?>
    </label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value ="<?php echo esc_attr( $title ); ?>" /><br />
    <p>Displays your network members in a simple listing.</p>
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
            
            'Symbiostock Network Directory',
            
            array( 'description' => __( 'Network directory, automatically scanned / created.' ) )
        
        );
        
    }
        
    public function form( $instance ) {
      
        
        ?><p>A prominent widget which links to your site's Symbiostock directory (an area automatically created and maintained by your site).</p><?php
                
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

//similar images widget STILL UNDER CONSTRUCTION

class symbiostock_similar_images extends WP_Widget{
    
    public function __construct() {
        
        //widget actual process
        
        parent::__construct(
        
            'symbiostock_similar_images',
            
            'Symbiostock - Similar Images',
            
            array( 'description' => __( 'Shows related images on a given image page.' ) )
        
        );
        
    }
        
    public function form( $instance ) {
        
        //outputs the options form on Admin screen
        
        $title = (isset( $instance[ 'title' ])) ? $instance[ 'title' ] : 'Similar Images';
        
        ?>
<p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>">
        <?php _e( 'Title: ' ); ?>
    </label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value ="<?php echo esc_attr( $title ); ?>" />
</p>
<?php
                
        }    
        
    public function widget( $args, $instance )
{
    
    //get current image id
    global $wp_query;
    
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
			echo $before_title . $title . $after_title;
		
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
				
			?> <div class="row-fluid front-page-featured"> <?php
			if($related_images){
				foreach ( (array) $related_images as $image ) {	
				
				$attachment_id = get_post_meta($image->ID, 'symbiostock_minipic_id');
				
				?>		
					<div class="widget-featured symbiostock-similar search-result">
						<div class="inner-featured">
							<div class="thumb">                           
								<a title="<?php echo $image->post_title; ?>" href="<?php echo get_permalink( $image->ID ); ?>">
									<?php echo wp_get_attachment_image( $attachment_id[0] ); ?>
								</a> 
							</div>
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