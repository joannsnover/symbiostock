<?php
//fire up the carousel...
class symbiostock_carousel{
    
    function __construct( $atts ) {
        
        $this->carousel( $span );
        
            
    }
    
    function carousel( $span = 'span6' ){
        
        $use_carousel = get_option('symbiostock_slide_image_0');
        
        $count = 0;
        
        if($use_carousel != false){
            
            ?>
            <div id="lauch_pad_carousel" class="<?php echo $span; ?> carousel slide">
            <!-- Carousel items -->
                <div class="carousel-inner">
                
                <?php
                    
                    while($count <= 9){
                        
                        if($count == 0){$active = 'active';} else {$active = '';}    
                            
                            $slide = get_option('symbiostock_slide_image_' . $count);
                            
                                if($slide != false){
                                
                                ?>
                                
                                <div class="<?php echo $active; ?> item">
                                    
                                    <img alt="" src="<?php echo get_option('symbiostock_slide_image_' . $count); ?>" />
                                                
                                    <div class="carousel-caption">
                                                                            
                                    <?php echo get_option('symbiostock_slide_description_' . $count); ?>
                                    
                                    </div>
                                    
                                </div>
                                
                                <?php
                                
                                }
                            
                        $count++;
                        
                        }
                        
                    
                    ?>
                
                    </div>
                <!-- Carousel nav -->
                <a class="carousel-control left" href="#lauch_pad_carousel" data-slide="prev">&lsaquo;</a>
                <a class="carousel-control right" href="#lauch_pad_carousel" data-slide="next">&rsaquo;</a>
                </div>
                
                <?php
            
        }
        
    }
    
}
// [bartag foo="foo-value"]
function symbiostock_carousel_init( $atts ) {
    new symbiostock_carousel( $atts );
    
}
add_shortcode( 'carousel', 'symbiostock_carousel_init' );
?>