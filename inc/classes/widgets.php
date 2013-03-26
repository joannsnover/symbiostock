<?php
class symbiostock_featured_posts_homepage extends WP_Widget{
    
    public function __construct() {
        //widget actual process
        
        parent::__construct(
        
            'symbiostock_featured_posts_homepage',
            
            'Featured Posts (Below Content)',
            
            array( 'description' => __( 'symbiostock featured posts below content area.' ) )
        
        );
        
    }
        
    public function form( $instance ) {
        
        //outputs the options form on Admin screen
        
        $title = (isset( $instance[ 'title' ])) ? $instance[ 'title' ] : 'Featured Posts';
        
        ?>
        <p>
        
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title: ' ); ?></label>
        
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
        
        if ( !empty( $title ) ) echo $before_title . $title . $after_title;
                
        $args = array(
        
        'post_type' => 'post',
		
		'tag' => 'featured',
        
        'posts_per_page' => 4
        
        );
        
        
        $featuredWidget = new WP_Query($args);
        
		?> <div class="row-fluid"> <?php 
		
        while ( $featuredWidget->have_posts() ) : 
            $featuredWidget->the_post(); 
								
			?>
            
            <div class="widget-featured span3">
            
                <div class="thumb">
                <a title="<?php the_title(); ?>" href="<?php the_permalink(); ?>"><?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'homepage-thumb-cropped' ); } ?></a>
                </div>
                
                <a title="<?php the_title(); ?>" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                
                <p class="entry-date"><i class="icon-calendar"> </i> <?php the_time('F j, Y'); ?></p>
                
                <?php  the_excerpt( ); ?>                
            </div>    
            
        
        <?php
        endwhile;   
        
		?> </div> <?php 
		
        wp_reset_postdata();
        
        echo $after_widget; 
        
    }
    
}
register_widget( 'symbiostock_featured_posts_homepage' );
class symbiostock_featured_posts_sidebar extends WP_Widget{
    
    public function __construct() {
        
        //widget actual process
        
        parent::__construct(
        
            'symbiostock_featured_posts_sidebar',
            
            'Featured Posts (SideBar)',
            
            array( 'description' => __( 'symbiostock featured posts placed on sidebar.' ) )
        
        );
        
    }
        
    public function form( $instance ) {
        
        //outputs the options form on Admin screen
        
        $title = (isset( $instance[ 'title' ])) ? $instance[ 'title' ] : 'Featured Posts';
        
        ?>
        <p>
        
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title: ' ); ?></label>
        
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
        
        if ( !empty( $title ) ) echo $before_title . $title . $after_title;
                
        $args = array(
        
        'post_type' => 'post',
		
		'tag' => 'featured',
        
        'posts_per_page' => 4
        
        );
        
        
        $featuredWidget = new WP_Query($args);
        
		?> <ul> <?php 
		
        while ( $featuredWidget->have_posts() ) : 
            $featuredWidget->the_post(); ?>
            
            <li class="widget-featured">
            
               
                <a title="<?php the_title(); ?>" href="<?php the_permalink(); ?>"><?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'mini-thumb' ); } ?></a>
               
                <a title="<?php the_title(); ?>" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                <p class="entry-date"><i class="icon-calendar"> </i>  <?php the_time('F j, Y'); ?></p>
                                         
            </li>    
            
        
        <?php
        endwhile;   
        
		?> </ul> <?php 
		
        wp_reset_postdata();
        
        echo $after_widget; 
        
    }
    
}
register_widget( 'symbiostock_featured_posts_sidebar' );
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
        
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title: ' ); ?></label>
        
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
?>