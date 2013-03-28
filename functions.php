<?php
/**
 * symbiostock functions and definitions
 *
 * @package symbiostock
 * @since symbiostock 1.0
 */
/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since symbiostock 1.0
 */
 
//define some paths for easy working
//remove code editing ability
//define('DISALLOW_FILE_EDIT', TRUE);
//http constants
$symbiostock_template_directory = get_bloginfo('template_directory');
define('symbiostock_CLASSDIR', $symbiostock_template_directory . '/inc/classes' );
define('symbiostock_IMGDIR', $symbiostock_template_directory . '/img' );
define('symbiostock_HEADERDIR', $symbiostock_template_directory . '/img/header' );
define('symbiostock_JSDIR', $symbiostock_template_directory . '/js' );
define('symbiostock_CSSDIR', $symbiostock_template_directory . '/css' );
//branding - shows up a lot in the theme, so we make some constants for that
define('symbiostock_LOGO', symbiostock_IMGDIR . '/symbiostock_logo.png');
define('symbiostock_LOGOSMALL', symbiostock_IMGDIR . '/symbiostock_logo_small.png');
define('symbiostock_32_DEFAULT', symbiostock_IMGDIR . '/32_default.jpg');
define('symbiostock_128_DEFAULT', symbiostock_IMGDIR . '/128_default.jpg');
//filepath constants 
$symbiostock_theme_root = get_theme_root() . '/symbiostock';
define('symbiostock_STOCKDIR', $_SERVER['DOCUMENT_ROOT'] . '/symbiostock_rf/' );
define('symbiostock_CLASSROOT', $symbiostock_theme_root . '/inc/classes/' );
define('symbiostock_INCLUDESROOT', $symbiostock_theme_root . '/inc/' );
define('symbiostock_NETWORK_MANAGER', $symbiostock_theme_root . '/inc/classes/network-manager/' );
define('symbiostock_CSSROOT', $symbiostock_theme_root . '/css/' );
//setup databases after activation - 
add_action('after_switch_theme', 'symbiostock_installer');
function symbiostock_installer(){
	
	/**
	* Install Theme Databases..
	*/
	require( get_template_directory() . '/inc/installer/installer.php' );
	
	}
 
if ( ! isset( $content_width ) )
	$content_width = 640; /* pixels */
if ( ! function_exists( 'symbiostock_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * @since symbiostock 1.0
 */
function symbiostock_setup() {
	
	/**
	 * Custom template tags for this theme.
	 */
	require( get_template_directory() . '/inc/template-tags.php' );
	/**
	 * Custom functions that act independently of the theme templates
	 */
	require( get_template_directory() . '/inc/extras.php' );
	/**
	 * Custom Theme Options
	 */
	//require( get_template_directory() . '/inc/theme-options/theme-options.php' );
	/**
	 * WordPress.com-specific functions and definitions
	 */
	//require( get_template_directory() . '/inc/wpcom.php' );
	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on symbiostock, use a find and replace
	 * to change 'symbiostock' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'symbiostock', get_template_directory() . '/languages' );
	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );
	/**
	 * Enable support for Post Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	/**
	 * Get Our Image Sizes
	 */
if ( function_exists( 'add_image_size' ) ) { 	
	add_image_size( 'homepage-thumb-cropped', 220, 135, true ); // 220 pixels wide by 135 pixels tall, hard crop mode
	
	add_image_size( 'homepage-thumb-proportional', 220, 220, true ); // soft crop mode
	
	add_image_size( 'mini-thumb', 60, 60, true ); // 60 pixels wide by 60 pixels tall, hard crop mode
}
	/**
	 * Add support for the Aside Post Formats
	 */
	add_theme_support( 'post-formats', array( 'aside', ) );
}
endif; // symbiostock_setup
add_action( 'after_setup_theme', 'symbiostock_setup' );
/**
 * Register widgetized area and update sidebar with default widgets
 *
 * @since symbiostock 1.0
 */
function symbiostock_widgets_init() {
	
	register_sidebar( array(
		'name' => __( 'Sidebar', 'symbiostock' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );
	
		
		register_sidebar( array(
		'name' => __( 'Featured Posts (Below Content)', 'symbiostock' ),
		'id' => 'featured-1',
		'before_widget' => '<div class="row-fluid"><aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside></div>',
		'before_title' => '<div class="row-fluid"><h3 class="featured-posts span12">',
		'after_title' => '</h3></div>',
	) );
	
	//footer sidebars
	
		register_sidebar(array(
		'name'=>'Footer Left',
		
		'before_widget' => '<div class="footer_sub_section">',
			
		'after_widget' => "</div>\n",
		
		'before_title' => '',
		
		'after_title' => "",
		));	
		
		
		register_sidebar(array(
		'name'=>'Footer Middle',
		
		'before_widget' => '<div class="footer_sub_section">',
			
		'after_widget' => "</div>\n",
		
		'before_title' => '',
		
		'after_title' => "",
		));	
		
		
		register_sidebar(array(
		'name'=>'Footer Right',
		
		'before_widget' => '<div class="footer_sub_section">',
		
		'after_widget' => "</div>\n",
		
		'before_title' => '',
		
		'after_title' => "",
		));	
		
}
add_action( 'widgets_init', 'symbiostock_widgets_init' );
/**
 * Enqueue scripts and styles
 */
function symbiostock_scripts() {
    if (!is_admin()) {	
	
		wp_enqueue_style( 'style', get_stylesheet_uri() );
		
		
		//Get Jquery
		
		wp_deregister_script('jquery');
        wp_register_script('jquery', ("http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"), false, '1.3.2', true);
        wp_enqueue_script('jquery');
        //bootstrap
        wp_register_script('symbiostock_bootstrap_js', symbiostock_JSDIR . '/bootstrap.min.js', array('jquery'), '1.0', false );
        wp_enqueue_script('symbiostock_bootstrap_js');
        //modernizr
        wp_register_script('modernizr', symbiostock_JSDIR . '/modernizr.js', array('jquery'), '2.6.2');
        wp_enqueue_script('modernizr'); // Enqueue it!
        //scripts
        wp_register_script('symbiostock_scripts', symbiostock_JSDIR . '/scripts.js', array('jquery'), '2.6.2');
        wp_enqueue_script('symbiostock_scripts'); // Enqueue it!
	
		//other scripts
		
		wp_enqueue_script( 'small-menu', get_template_directory_uri() . '/js/small-menu.js', array( 'jquery' ), '20120206', true );
	
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	
		if ( is_singular() && wp_attachment_is_image() ) {
			wp_enqueue_script( 'keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
		}
		
		//custom scripts for image search page -
		if(is_tax( 'image-tags' ) || is_tax( 'image-type' ) || (is_search() && get_query_var( 'post_type' ) == 'image')){
				
			wp_register_script('search-results', symbiostock_JSDIR . '/search-results.js', array('jquery'), '2.6.2');
	
			wp_enqueue_script('search-results'); // Enqueue it!
		
		}
		function symbiostock_header_js(){
			?>
			<script type="text/javascript">
			var symbiostock_large_loader = "<?php echo symbiostock_IMGDIR . '/loading-large.gif' ?>";
			</script>
			<?php
		}
		// Add hook for admin <head></head>
		add_action( 'wp_head', 'symbiostock_header_js' );
		//if buddypress is installed
		
	
	}
	
}
//separate content from addon-stuff that other plugins do
function symbiostock_sep_content($content) {
	if(!is_feed() && !is_home()) {
		$content = '<div class="content-wrap"><hr />' . $content . '<hr /></div>';
	}
	return $content;
}
add_filter('the_content', 'symbiostock_sep_content', 1);
//custom login functions
function symbiostock_is_login_page() {
    return !strncmp($_SERVER['REQUEST_URI'], '/wp-login.php', strlen('/wp-login.php'));
}
if(symbiostock_is_login_page()){
	
	
	function symbiostock_login_stylesheet() { ?>
        <link rel="stylesheet" id="custom_wp_admin_css"  href="<?php echo symbiostock_CSSDIR . '/style-login.css'; ?>" type="text/css" media="all" />
        <?php 
	}
	
	add_action( 'login_enqueue_scripts', 'symbiostock_login_stylesheet' );
	
	function symbiostock_login_logo() {
		
		$default = symbiostock_IMGDIR . '/site-login-logo.png';
		
		$symbiostock_login_logo = get_option('symbiostock_login_logo_link', $default );;
		
		if(empty($symbiostock_login_logo)){
			$symbiostock_login_logo = $default;
			}
		
		?>
	<style type="text/css">
		body.login div#login h1 a {
			background-image: url(<?php echo $symbiostock_login_logo ?>);
			padding-bottom: 30px;
		}
	</style>
	<?php }
	add_action( 'login_enqueue_scripts', 'symbiostock_login_logo' );
	
		function sybiostock_login_logo_url() {
		return get_bloginfo( 'url' );
	
	}
	add_filter( 'login_headerurl', 'sybiostock_login_logo_url' );
	
	function symbiostock_login_logo_url_title() {
		return get_option('symbiostock_my_network_description', get_bloginfo( 'description' ));
	}
	add_filter( 'login_headertitle', 'symbiostock_login_logo_url_title' );
	
}
/**
 * Sets up filters for basic functionality and viewing
 *
 * @since symbiostock 1.0
 */
add_filter('excerpt_length', 'symbiostock_excerpt_length');
function symbiostock_excerpt_length($length) {
	return 20; 
}
add_filter('excerpt_more', 'symbiostock_excerpt_more'); 
function symbiostock_excerpt_more($more) {
	
    global $post;
	
    return ' ... <a class="read-more" href="'. get_permalink($post->ID) . '"> See More &raquo;</a>';
	
}
function symbiostock_admin_bar_remove() {
        global $wp_admin_bar;
        /* Remove WP Logo */
        $wp_admin_bar->remove_menu('wp-logo');
}
add_action('wp_before_admin_bar_render', 'symbiostock_admin_bar_remove', 0);
add_action( 'wp_enqueue_scripts', 'symbiostock_scripts' );
//symbiostock_product_attachments, symbiostock_delete_cleanup, symbiostock_product_delete
//are all used in a complete deleletion of files when an image post is deleted
//this gets all attachment ids for post
function symbiostock_product_attachments($post_id){
		
		$attachments_to_delete = array();
			
			$args = array(
			'post_type' => 'attachment',
			'numberposts' => null,
			'post_status' => null,
			'post_parent' => $post_id,
		); 
		
		$attachments = get_posts($args);
			
		if ($attachments) {
			foreach ($attachments as $attachment) {
	
			   array_push($attachments_to_delete, $attachment->ID);
			}
		}	
		
		return $attachments_to_delete;
	}
//during image deletion, we tidy up by removing attachments
add_action( 'wp_trash_post', 'symbiostock_delete_cleanup' );
function symbiostock_delete_cleanup($post_id){
	
	$type = get_post_type($post_id);
	
	if($type == 'image'){
		
		$attach_ids = symbiostock_product_attachments($post_id);
		
		foreach ($attach_ids as $delete){
			
			//we only want to delete previews, not other attachments
			$preview_check = get_post_meta($delete, 'symbiostock_preview');
							
			if( $preview_check[0] == 'minipic' || $preview_check[0] ==  'preview' || $preview_check[0] ==  'transparency' ){
			
				wp_delete_attachment( $delete, false ); 
			
			}
			}
		
	
		//now delete parent post
		
		wp_delete_post( $post_id, true );
		}
	
	}
//if an image post is deleted, stock image files also get deleted
add_action('delete_post', 'symbiostock_product_delete');
function symbiostock_product_delete($post_id){
		
	$type = get_post_type($post_id);
	
	if($type == 'image'){
		
    $extensions = array('jpg', 'png', 'zip', 'eps');
			
		foreach($extensions as $type){
			
			$file = symbiostock_STOCKDIR . $post_id . '.' . $type;
			
			if(file_exists($file)){
				
				unlink($file);
									
				}
			
			}
		}
	
	}
function symbiostock_filter_post_tag_term_links( $term_links ) {
    $wrapped_term_links = array();
    foreach ( $term_links as $term_link ) {
		
        $wrapped_term_links[] = '<i class="icon-tag"></i> ' . $term_link;
    }	
	
    return $wrapped_term_links;
}
add_filter( 'term_links-post_tag', 'symbiostock_filter_post_tag_term_links' );
//image info specific functions
//WARNING too database intensive. Needs to use get_post_custom() but that function does not seem to play well with serialized data
function symbiostock_post_meta($postid){
	
	$meta_values = array(
		'extensions',
		'collections', 
		'collection_img', 
		'related_images', 
		'author', 
		'live', 
		'size_info',
		'price_bloggee', 
		'price_small',
		'price_medium', 
		'price_large', 
		'price_vector', 
		'price_zip', 
		'locked', 
		'discount_percent', 
		'exclusive', 
		'symbiostock_minipic', 
		'symbiostock_preview',
		'symbiostock_transparency',	
		'size_eps', 
		'size_zip',
		'symbiostock_bloggee_available',			
		'symbiostock_small_available',			
		'symbiostock_medium_available',			
		'symbiostock_large_available',			
		'symbiostock_vector_available',			
		'symbiostock_zip_available',
		
		'symbiostock_referral_link_1',
		'symbiostock_referral_link_2',
		'symbiostock_referral_link_3',
		'symbiostock_referral_link_4',
		'symbiostock_referral_link_5',
		
		'symbiostock_referral_label_1',
		'symbiostock_referral_label_2',
		'symbiostock_referral_label_3',
		'symbiostock_referral_label_4',
		'symbiostock_referral_label_5',
	);
	
	$image_meta = array();
	
	foreach ($meta_values as $meta_value){
				
		$image_meta[$meta_value] = get_post_meta($postid, $meta_value);
		
		}
	
	$image_meta['id'] = $postid;	
	
	return $image_meta;
		
	}
function symbiostock_get_user_files($user_id=''){
		
		if(empty($user_id)){
			global $current_user;
     		get_currentuserinfo();
			
			$user_id = $current_user->ID;
		} 
		$user_products = get_user_meta($user_id , 'symbiostock_purchased_products'); 
		
		$user_products = unserialize($user_products[0]);		
		
		return $user_products;
	}	
function symbiostock_network_info(){
	
	$network_values = array(	
		'symbiostock_my_network_name',
		'symbiostock_my_network_description',
		'symbiostock_my_network_avatar',
		'symbiostock_my_network_logo',
		'symbiostock_my_network_about_page',
		'symbiostock_my_network_announcement',
		'symbiostock_use_network',		
	);
	
	$network_info = array();
	
	foreach ($network_values  as $network_value){
				
		$network_info[$network_value] = get_option($network_value);
		
		}	
	
	return $network_info;
		
	}
//customer page is generated on setup. We retrieve this at various parts of site
function symbiostock_customer_area_link(){
	
	$customer_page_id = get_option('symbiostock_customer_page'); 
	
	$permalink =  get_permalink( $customer_page_id );
	
	return $permalink;
	
}	
function symbiostock_customer_area($text){
	
	$customer_page_id = get_option('symbiostock_customer_page'); 
	
	$permalink =  get_permalink( $customer_page_id );
	
	$customer_page_link = '<a title="' . $text. '" href="' . $permalink . '"><i class="icon-shopping-cart"> </i> ' . $text . '</a>';
	return $customer_page_link;
	
}	
//customer login page is generated on setup. We retrieve this at various parts of site
function symbiostock_customer_login($text){
	
	$eula_page_id = get_option('symbiostock_eula_page'); 
	
	$permalink =  get_permalink( $eula_page_id );
	
	$eula_page_id_link = '<a title="' . $text. '" href="' . $permalink . '"><i class="icon-file"> </i> ' . $text . '</a>';
	return $eula_page_id_link;
	
}	
function symbiostock_eula($text){
	
	$symbiostock_eula_page = get_option('symbiostock_eula_page'); 
	
	$permalink =  get_permalink( $symbiostock_eula_page );
		
	$customer_login_page_link = '<a title="' . $text. '" href="' . $permalink . '"><i class="icon-lock"> </i> ' . $text . '</a>';
	return $customer_login_page_link;
	
}	
function symbiostock_customer_nav_links(){
	if(is_user_logged_in()){	
	   global $current_user;		
 	   get_currentuserinfo();	
	
	
	$name = $current_user->display_name;
	}
	if(!is_user_logged_in()){
		
		$nav_links = '<li data-toggle="modal" data-target="#symbiostock_member_modal" class="login_register">' . symbiostock_customer_login('Login / Register') . '</li>';
				
		} else {
		$nav_links = '<li class="logout"><a href="' . wp_logout_url( get_permalink( ) ) . '" title="Logout">(<i class="icon-key"> </i> Logout)</a></li>';
		$nav_links .= '<li class="license_area">' . symbiostock_customer_area($name) . '</li>';	
		
		
				
		}
		
	return $nav_links;
	
	}
//add a menu item for customer on above header nav
add_filter('wp_nav_menu_items','add_customer_nav', 10, 2);
function add_customer_nav( $nav, $args ) {
	
    if( $args->theme_location == 'above-header-menu' )
	
        return $nav. symbiostock_customer_nav_links();
    return $nav;
}
//set up our symbiostock feed
function symbiostock_feed_display($feed_url, $qty){
		
	$rss = fetch_feed($feed_url);
		if (!is_wp_error( $rss ) ) :
				$maxitems = $rss->get_item_quantity($qty);
			$rss_items = $rss->get_items(0, $maxitems);
			if ($rss_items):
				echo "<ul>\n";
				foreach ( $rss_items as $item ) : 
					//instead of a bunch of string concatenation or echoes, I prefer the terseness of printf 
					//(http://php.net/manual/en/function.printf.php)
					printf('<li><a href="%s">%s</a><p>%s</p></li>',$item->get_permalink(),$item->get_title(),$item->get_description() );
				endforeach;
				echo "</ul>\n";
			endif;
		endif;
	}
//for validating URLs
function symbiostock_validate_url($url){
	if (filter_var($url, FILTER_VALIDATE_URL)) {
	  return true;
	}
	else {
	  return false;
	}
}
//set up some unique variables for wp_query so that our network search gets parameters properly
function symbiostock_wp_query_vars( $qvars ){
	global $wp_query;
	$qvars[] = 'symbiostock_network_search'; //is this a network query?
	$qvars[] = 'symbiostock_network_info'; //do we want network info?
	$qvars[] = 'symbiostock_number_results'; //how many search results do we want?
	return $qvars;
	}
add_filter('query_vars', 'symbiostock_wp_query_vars');
//the next few functions compensate for what appears to be a pagination bug.
//image searches are sent to taxonomy where results are shown. If no results, 404 is thrown, but 
// we disguise the 404 to be a typical "no results found in search" page.
function symbiostock_modify_query( $query ) {
	if ( ( is_search() && get_query_var( 'post_type' ) == 'image' ) ) {
		
		$encoded_search_term = urlencode(get_query_var('s'));
		
		$home = home_url();
		$params = array( 'image-tags' => $encoded_search_term);
		$redirect = add_query_arg( $params, $home );
		wp_redirect($redirect);
		exit();
	
	}
}
add_action( 'parse_query', 'symbiostock_modify_query' );
function symbiostock_filter_404_title( $title )
{
    if ( is_404() && is_tax( 'image-tags' )) {
        $title = 'No results found. ';
    }
    // You can do other filtering here, or
    // just return $title
    return $title;
}
// Hook into wp_title filter hook
add_filter( 'wp_title', 'symbiostock_filter_404_title', 1 );
function symbiostock_image_results_per_page( $query ) {
		$network_search = get_query_var('symbiostock_network_search');
		
		if($network_search != true && !is_admin()){
			
			$query->set('posts_per_page', 20);
			return;
			}
}
add_action( 'pre_get_posts', 'symbiostock_image_results_per_page' );
function symbiostock_network_results_per_page( $query ) {
		$network_search = get_query_var('symbiostock_network_search');
		
		if($network_search == true){
			
			$query->set('posts_per_page', 5);
			return;
			}
}
add_action( 'pre_get_posts', 'symbiostock_network_results_per_page' );
//Symbiostock Decode Entities function
function ssde($text) {
    $text= html_entity_decode($text,ENT_QUOTES,"ISO-8859-1"); #NOTE: UTF-8 does not work!
    //$text= preg_replace('/&#(\d+);/me',"chr(\\1)",$text); #decimal notation
    //$text= preg_replace('/&#x([a-f0-9]+);/mei',"chr(0x\\1)",$text);  #hex notation
	
    return $text;
}
function symbiostock_credit_links( $position )
{
    
    $symbiostock_credit_links = get_option( 'symbiostock_credit_links' );
    
    $links = array(
         'ClipArtIllustration.com - Illustrations by Leo Blanchette, and home of the Orange Man' => 'http://www.clipartillustration.com',
        'Microstockgroup.com - A meeting place for microstock professionals.' => 'http://www.microstockgroup.com',
        'ClipArtof.com - High Resolution Stock Illustrations &amp; Clip Art' => 'http://www.clipartof.com/',
        'Symbiostock.com - Sell your images and network with fellow microstock professionals.' => 'http://www.symbiostock.com/' 
    );
    
    if ( $position == $symbiostock_credit_links ) {
        
		
        echo '<ul class="nav nav-tabs nav-stacked nav-list">';
       	
        foreach ( $links as $title => $link ) {
            
            $link_parts = explode( '-', $title );
            
            echo '<li><a class="" title="' . trim( $link_parts[ 1 ] ) . '" href="' . $link . '"><i class="icon-double-angle-right"> </i>' . trim( $link_parts[ 0 ] ) . '</a></li>';
            
        }
        
        echo '</ul>';
    }
    
}
/**
 * Implement the Custom Header feature
 */
//require( get_template_directory() . '/inc/custom-header.php' );
/**
 * Get symbiostock Menus
 */
require_once('inc/menus.php');
/**
 * Get symbiostock Widgets
 */
 
require_once('inc/classes/widgets.php');
/**
 * Get symbiostock Carousel
 */
 
require_once('inc/classes/symbiostock_carousel.php');
/**
 * Get symbiostock Admin Area
 */
 
require_once('inc/classes/admin.php');
/**
 * Get Image Custom Post Functions
 */
 
require_once('inc/rf-custom-post-functions.php');
/**
 * Get the cart
 */
 
require_once('inc/classes/cart/cart.php');
/**
 * Get symbiostock frontend ajax
 */
require_once('inc/classes/symbiostock_ajax_frontend.php');
//get our interpreter class, for displaying network data and search results
require_once(symbiostock_NETWORK_MANAGER . '/network-manager.php');