<?php
class symbiostock_featured_images extends WP_Widget{
    
    public function __construct() {
        //widget actual process
        
        parent::__construct(
        
            'symbiostock_featured_images',
            
            'Symbiostock - Featured Images',
            
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
                
        $args = array(
        
        'post_type' => 'image',       
        'posts_per_page' => 4,
		'tax_query' => array(
		array(
			'taxonomy' => 'image-type',
			'field' => 'slug',
			'terms' => 'symbiostock-featured-images'
		)
	)
        
        );
        
        
        $featuredWidget = new WP_Query($args);
        
		?>
<div class="row-fluid front-page-featured">
    <?php 
		
        while ( $featuredWidget->have_posts() ) : 
            $featuredWidget->the_post(); 
								
			?>
    <div class="widget-featured search-result">
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
            
            'Symbiostock - Latest Images',
            
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
        
        if ( !empty( $title ) ) echo $before_title . $title . $after_title;
                
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
    <div class="widget-featured search-result">
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
            
            'Symbiostock - Mobile Navigation',
            
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





//similar images widget STILL UNDER CONSTRUCTION
/*
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
    
    echo $before_widget;
    
    if ( !empty( $title ) )
        echo $before_title . $title . $after_title;
    
    //this related images code was derived from here: http://www.wprecipes.com/how-to-show-related-posts-without-a-plugin
    
    if ( $tags ) {
        $tag_ids = array( );
        foreach ( $tags as $individual_tag )
            $tag_ids[ ] = $individual_tag->term_id;
        
        $query_args = array(
			  'post_type' => 'image',
			  'tax_query' => array(
							array(
								'taxonomy' => 'image-tags',
								'field' => 'id',
								'terms' => $tag_ids,
								'operator'=> 'IN' //Or 'AND' or 'NOT IN'
							 )),
			  'showposts' => 6,	
			  'orderby' => 'ASC',
			  'post__not_in'=>array($post->ID)
        );
        
        $similar_images_query = new wp_query( $query_args );
        
        if ( $similar_images_query->have_posts() ) {
            
            while ( $similar_images_query->have_posts() ) {
                $similar_images_query->the_post();
			?>
			
			<div class="widget-featured search-result">
				<div class="inner-featured">
					<div class="thumb"> 
					<a title="<?php the_title(); ?>" href="<?php the_permalink(); ?>">
						<?php
							if ( has_post_thumbnail() ) {
								the_post_thumbnail();
							}				
						?>
					</a> </div>
				</div>
			</div>
			<?php
            }
            
        }
    }
    
    echo '<div class="clearfix"></div>';
    echo $after_widget;
    
}


    
}
register_widget( 'symbiostock_similar_images' );
*/
?>
