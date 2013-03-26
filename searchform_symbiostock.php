<?php
/**
 * The template for displaying the main search form of symbiostock
 *
 * @package symbiostock
 * @since symbiostock 1.0
 */
?>
<div class="input-prepend input-append" id="symbiostock_main_search">
	<form name="symbiostock_search" method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
		<label for="s" class="assistive-text"><?php _e( 'Search', 'symbiostock' ); ?></label>
		<input type="text" class="field" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" id="s" placeholder="<?php esc_attr_e( 'Search &hellip;', 'symbiostock' ); ?>" />
		<input type="submit" class="submit" name="submit" id="searchsubmit" value="<?php esc_attr_e( 'Search', 'symbiostock' ); ?>" />
        <select id="select_type" name="post_type" >
        <option value="image">Images</option>
        <option value="post">Blog</option>
        </select>
        <button class="btn" type="submit">search</button>
	</form>
</div>
