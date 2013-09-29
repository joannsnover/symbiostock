<?php
/**
 * The template for displaying the main search form of symbiostock
 *
 * @package symbiostock
 * @since symbiostock 1.0
 */
?>
    <form class="navbar-form navbar-right" name="symbiostock_search" method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
        
            <div class="form-group">
            <label for="s" class="assistive-text"><?php _e( 'Search', 'symbiostock' ); ?></label>
                    
            <input id="ss_primary_search" class="form-control field" type="text" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" id="s" placeholder="<?php esc_attr_e( 'Search &hellip;', 'symbiostock' ); ?>" />
            </div>
            <div class="form-group">
            <input class="form-control submit" type="submit" name="submit" id="searchsubmit" value="<?php esc_attr_e( 'Search', 'symbiostock' ); ?>" />
            
            <?php 
            ss_image_blog_form_option();
            ?>
            </div>
            <div class="form-group">
                <button class="btn btn-primary form-control" type="submit">search</button>
            </div>
            
    </form>
