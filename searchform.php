<?php
/**
 * The template for displaying search forms in symbiostock
 *
 * @package symbiostock
 * @since symbiostock 1.0
 */
?>

<div class="navbar navbar-default">
    <form class="navbar-form navbar-left" method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
       
        <div class="form-group row">
            <label for="s" class="assistive-text"><?php _e( 'Search', 'symbiostock' ); ?></label>
            
            <div class="col-md-8">  
                <input class="form-control" type="text" class="field" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" id="s" placeholder="<?php esc_attr_e( 'Search &hellip;', 'symbiostock' ); ?>" />
            </div>
              
            <div class="col-md-4">  
                <input id="s" type="submit" value="<?php _e('Search ', 'symbiostock') ?>" class="btn btn-default form-control" />
            </div>
            
            <input type="hidden" name="post_type" value="post" />                       
        </div>
        
    </form>
</div>    