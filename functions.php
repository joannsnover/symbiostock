<?php
/**
 * Main functions file of Symbiostock
 *
 * Coordinates all other Symbiostock includes, functions, and classes.
 * 
 * @package    symbiostock
 * @author     Leo Blanchette <Leo@Symbiostock.com>
 * @copyright  2013 Symbiostock LLC
 * @license    http://www.gnu.org/licenses/gpl-2.0.html
 * @link       http://www.symbiostock.org
 */

/*
 Copyright 2012  Leo Blanchette (email : leo@symbiostock.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 3, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

//trash the admin bar if not admin...
if ( ! current_user_can( 'manage_options' ) ) {
    show_admin_bar( false );
}

$symbiostock_template_directory = get_bloginfo( 'template_directory' );

/**
 * HTML Path to the "classes" directory.
 * @package symbiostock
 * @subpackage site-constants
 */
define( 'symbiostock_CLASSDIR', $symbiostock_template_directory . '/inc/classes' );

/**
 * HTML Path to the "img" directory (main theme images).
 * @package symbiostock
 * @subpackage site-constants 
 */
define( 'symbiostock_IMGDIR', $symbiostock_template_directory . '/img' );

/**
 * HTML Path to the "header" directory.
 * @package symbiostock
 * @subpackage site-constants
 */
define( 'symbiostock_HEADERDIR', $symbiostock_template_directory . '/img/header' );

/**
 * HTML Path to the "js" (javascript) directory.
 * @package symbiostock
 * @subpackage site-constants
 */
define( 'symbiostock_JSDIR' , $symbiostock_template_directory . '/js' );

/**
 * HTML Path to the "css" directory (main css files).
 * @package symbiostock
 * @subpackage site-constants
 */
define( 'symbiostock_CSSDIR' , $symbiostock_template_directory . '/css' );

/**
 * HTML Path to the "tmp" directory (often used for file manipulation).
 * @package symbiostock
 * @subpackage site-constants
 */
define( 'symbiostock_TMPDIR' , $symbiostock_template_directory . '/tmp' );

/**
 * Convenient HTML Link to the large Symbiostock logo.
 * @package symbiostock
 * @subpackage site-constants
 */
define( 'symbiostock_LOGO' , symbiostock_IMGDIR . '/symbiostock_logo.png' );

/**
 * Convenient HTML Link to the large Symbiostock small logo.
 * @package symbiostock
 * @subpackage site-constants
 */
define( 'symbiostock_LOGOSMALL', symbiostock_IMGDIR . '/symbiostock_logo_small.png' );

/**
 * Convenient HTML Link to the 32px SS avatar.
 * @package symbiostock
 * @subpackage site-constants
 */
define( 'symbiostock_32_DEFAULT' , symbiostock_IMGDIR . '/32_default.jpg' );

/**
 * Convenient HTML Link to the 12px SS avatar.
 * @package symbiostock
 * @subpackage site-constants
 */
define( 'symbiostock_128_DEFAULT' , symbiostock_IMGDIR . '/128_default.jpg' );

/**
 * Simple function for returning a sizename key array.
 *
 * @return Array List of sizes to size-names conversion key. -- $var['bloggee'] gives chosen name for "bloggee" such as "Blog" or "Extra Small"
 */

function ss_get_sizenames(){

    $sizes = array(
            'bloggee' => __('Bloggee', 'Symbiostock'),
            'small'   => __('Small',  'Symbiostock'),
            'medium'  => __('Medium', 'Symbiostock'),
            'large'   => __('Large',  'Symbiostock'),
            'vector'  => __('Vector', 'Symbiostock'),
            'zip'     => __('Zip',    'Symbiostock'),

    );

    return get_option('symbiostock_size_names', $sizes);

}

//SET UP SIZE NAMES AS GLOBALLY ACCESSIBLE
$ss_sizenames = ss_get_sizenames();

//filepath constants 
$symbiostock_theme_root = get_theme_root( ) . '/' . get_template();


/**
 * FILE PATH to the "symbiostock_rf" directory. "rf" is meaningless, but
 * all of symbiostock's protected stock images are kept here.
 * 
 * @package symbiostock
 * @subpackage site-constants
 */
define( 'symbiostock_STOCKDIR' , ABSPATH . 'symbiostock_rf/' );

/**
 * FILE PATH to Symbiostock's network director, where Symbiocards are kept and updated.
 *
 * @package symbiostock
 * @subpackage site-constants
 */
define( 'symbiostock_NETDIR' , ABSPATH . 'symbiostock_network/' );

/**
 * FILE PATH to marketing functions
 * @package symbiostock
 * @subpackage site-constants
 */
define( 'symbiostock_MARKETROOT', $symbiostock_theme_root . '/inc/classes/marketing/' );

/**
 * FILE PATH to main "classes" folder
 * @package symbiostock
 * @subpackage site-constants
 */
define( 'symbiostock_CLASSROOT', $symbiostock_theme_root . '/inc/classes/' );

/**
 * FILE PATH to "inc" folder, where most Symbiostock functionality is kept.
 * @package symbiostock
 * @subpackage site-constants
 */
define( 'symbiostock_INCLUDESROOT', $symbiostock_theme_root . '/inc/' );

/**
 * FILE PATH to the "Network Manager" system, which is responsible for most network functionality
 * @package symbiostock
 * @subpackage site-constants
 */
define( 'symbiostock_NETWORK_MANAGER', $symbiostock_theme_root . '/inc/classes/network-manager/' );

/**
 * FILE PATH to CSS -- was used when css files were generated dynamically on install.
 * @package symbiostock
 * @subpackage site-constants
 */
define( 'symbiostock_CSSROOT' , $symbiostock_theme_root . '/css/' );

/**
 * FILE PATH to the temporary folder which is often used in image/file manipulation
 * @package symbiostock
 * @subpackage site-constants
 */
define( 'symbiostock_TMPROOT' , $symbiostock_theme_root . '/tmp/' );

//setup databases after activation - 

if ( !function_exists( 'ss_url_key' ) )
{
    /**
     * Generate URL key which is used to uniquely identify sites in Symbiostock
     * 
     * @param string $site The URL of the site.
     * @return string
     */

    function ss_url_key( $site )
    {
        $urlParts = parse_url( $site );
        $domain = preg_replace( '/^www\./' , '' , $urlParts[ 'host' ] );
        return $domain . $urlParts[ 'path' ];
    }
}

/**
 * Simplified domain name of our website, which is often passed in referral.
 * 
 * Javascript inserts your website's domain name on click of certain links,
 * allowing the tracking of human traffic. It does not get used with search engines,
 * thus keeping links "clean" and not dynamic.
 * 
 * @package symbiostock
 * @subpackage site-constants
 */
define( 'SSREF' , '?r=' . str_replace('http://', '', home_url( ) ) );

add_action( 'after_switch_theme' , 'symbiostock_installer' );

/**
 * Upon theme activation, runs a set of actions that set up Symbiostock.
 * 
 * Very important function that sets up Symbiostock for proper functionality.
 * 
 * It does the following: 
 * <ul>
 * <li> Create our content directory for wordpress, which holds previews on product pages.</li>
 * <li> Create our directory for downloadable products.</li>
 * <li> Create our directory for paypal IPN.</li>
 * <li> Create and move "downloads" script to proper directory (which customer retrieves purchases with).</li>
 * <li> Creates the seeds directory, for collecting network data.</li>
 * <li> Creates our htaccess, to protect downloadable products.</li>
 * <li> Sets up essential pages, based on templates, which are referenced by essential Symbiostock navigation.</li>
 * <li> Creates initial categories for image organization (which can be deleted).</li>
 * <li> Creates upload directory.</li>
 * <li> Upon completion, sends upgrade email.</li>
 * </ul>
 * 
 * @package symbiostock
 * @subpackage installation-activation
 * 
 */
function symbiostock_installer()
{
    require( get_template_directory( ) . '/inc/installer/installer.php' );        
}

/**
 * Include the TGM_Plugin_Activation class.
 */

require_once ( get_template_directory( ) . '/inc/classes/class-tgm-plugin-activation.php' );

/**
 * Activate Plugins.
 */
require_once ( get_template_directory( ) . '/inc/plugin_install_list.php' );


if ( !isset( $content_width ) )
    $content_width = 640;
/* pixels */


if ( !function_exists( 'symbiostock_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 */
    function symbiostock_setup()
    {

        /**
         * Custom template tags for this theme.
         */
        require( get_template_directory( ) . '/inc/template-tags.php' );
        /**
         * Custom functions that act independently of the theme templates
         */
        require( get_template_directory( ) . '/inc/extras.php' );
        /**
         * Customize Theme Options
         */  
        $use_customizer = get_option('ss_use_customizer', 1);
        
        if($use_customizer == 1){               
            require_once('customizer.php');
        }
        /*
         * Front Page Specific Setup 
         */
        require_once( get_template_directory( ) . '/inc/front_page_generator.php' );
        
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
        load_theme_textdomain( 'symbiostock' ,
                get_template_directory( ) . '/languages' );
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
        if ( function_exists( 'add_image_size' ) )
        {
            add_image_size( 'homepage-thumb-cropped' , 220 , 135 , true ); // 220 pixels wide by 135 pixels tall, hard crop mode

            add_image_size( 'homepage-thumb-proportional' , 220 , 220 , true ); // soft crop mode

            add_image_size( 'mini-thumb' , 60 , 60 , true ); // 60 pixels wide by 60 pixels tall, hard crop mode
        }
        /**
         * Add support for the Aside Post Formats
         */
        add_theme_support( 'post-formats' , array( 'aside', ) );
        
        $defaults = array(
                'default-image'          => '',
                'random-default'         => false,
                'width'                  => 0,
                'height'                 => 0,
                'flex-height'            => false,
                'flex-width'             => false,
                'default-text-color'     => '',
                'header-text'            => false,
                'uploads'                => true,
                'wp-head-callback'       => '',
                'admin-head-callback'    => '',
                'admin-preview-callback' => '',
        );
        add_theme_support( 'custom-header', $defaults );     


        
    }
endif; // symbiostock_setup
add_action( 'after_setup_theme' , 'symbiostock_setup' );


/**
 * Register widgetized areas and update sidebar with default widgets
 *
 * @since symbiostock 1.0
 *  
 */
function symbiostock_widgets_init()
{   
    register_sidebar( 
            array( 
                    'name'          => __( 'Sidebar' , 'symbiostock' ),
                    'id'            => 'sidebar-1',
                    'class'         => 'panel-body',
                    'before_widget' => '<div class="panel-body"><aside id="%1$s" class="widget %2$s">',
                    'after_widget'  => '</aside></div>',
                    'before_title'  => '<div class="panel-heading"><h3 class="panel-title widget-title">',
                    'after_title'   => '</h3></div>', ) );

    //Home page, above content area (typically for a slide show)
    register_sidebar( 
            array( 
                    'name' => __( 'Home Page (Above Content)' , 'symbiostock' ),
                    'id' => 'home-page-above-content',   
                    'class'         => 'panel-body',
                    'before_widget' => '<div class="panel-body home-above-content"><aside id="%1$s" class="widget %2$s">',
                    'after_widget' => '</aside></div>',
                    'before_title' => '<div class=" panel-heading"><h3 class="panel-title featured-posts ">',
                    'after_title' => '</h3></div>', ) );
    
    //home page beside content area, such as for a sidebar type content, or CTA
    register_sidebar( 
            array( 
                    'name' => __( 'Home Page (Beside Content)' , 'symbiostock' ),
                    'id' => 'home-page-beside-content',
                    'class'         => 'panel-body',
                    'before_widget' => '<div class="panel-body home-beside-content widget %2$s"><aside id="%1$s" class="widget %2$s">',
                    'after_widget' => '</aside></div>',
                    'before_title' => '<div class=" panel-heading"><h3 class="panel-title featured-posts">',
                    'after_title' => '</h3></div>', ) );

    //Home page below content (for featured images)        
    register_sidebar( 
            array( 
                    'name' => __( 'Home Page (Below Content)' , 'symbiostock' ),
                    'id' => 'home-page-below-content',
                    'class'         => 'panel-body',
                    'before_widget' => '<div class=" panel-body home-below-content"><aside id="%1$s" class="widget %2$s">',
                    'after_widget' => '</aside></div>',
                    'before_title' => '<div class=" panel-heading"><h3 class="panel-title featured-posts ">',
                    'after_title' => '</h3></div>', ) );

    //Call To Action Widgets
    register_sidebar( 
            array( 
                    'name' => __( 'Home Page Bottom  1/3' , 'symbiostock' ),
                    'id' => 'cta-1',
                    'class'         => 'panel-body',
                    'before_widget' => '<div class="symbiostock-cta panel-body"><aside id="%1$s" class="widget %2$s">',
                    'after_widget' => '</aside></div>',
                    'before_title' => '<div class="panel-heading"><h3 class="panel-title">',
                    'after_title' => '</h3></div>', ) );
    register_sidebar( 
            array( 
                    'name' => __( 'Home Page Bottom  2/3' , 'symbiostock' ),
                    'id' => 'cta-2',
                    'class'         => 'panel-body',
                    'before_widget' => '<div class="symbiostock-cta panel-body"><aside id="%1$s" class="widget %2$s">',
                    'after_widget' => '</aside></div>',
                    'before_title' => '<div class="panel-heading"><h3 class="panel-title">',
                    'after_title' => '</h3></div>', ) );
    register_sidebar( 
            array( 
                    'name' => __( 'Home Page Bottom  3/3' , 'symbiostock' ),
                    'id' => 'cta-3',
                    'class'         => 'panel-body',
                    'before_widget' => '<div class="symbiostock-cta panel-body"><aside id="%1$s" class="widget %2$s">',
                    'after_widget' => '</aside></div>',
                    'before_title' => '<div class="panel-heading"><h3 class="panel-title">',
                    'after_title' => '</h3></div>', ) );

    //author page widgets 

    //Author Page page below content (for featured images)        
    register_sidebar( 
            array( 
                    'name' => __( 'Author Page (Below Content)' , 'symbiostock' ),
                    'id' => 'author-page-below-content',
                    'class'         => 'panel-body',
                    'before_widget' => '<div class="panel-body author-below-content"><aside id="%1$s" class="widget %2$s">',
                    'after_widget' => '</aside></div>',
                    'before_title' => '<div class="panel-heading"><h3 class="panel-title featured-posts ">',
                    'after_title' => '</h3></div>', ) );
    register_sidebar( 
            array( 
                    'name' => __( 'Author Page (Sidebar)' , 'symbiostock' ),
                    'id' => 'author-page-sidebar',
                    'class'         => 'panel-body',
                    'before_widget' => '<div  class="panel-body author-sidebar"><aside id="%1$s" class="widget %2$s">',
                    'after_widget' => '</aside></div>',
                    'before_title' => '<div class="panel-heading"><h3 class="panel-title featured-posts ">',
                    'after_title' => '</h3></div>', ) );

    //image page widget areas    

    register_sidebar( 
            array( 
                    'name' => __( 'Image Page Side' , 'symbiostock' ),
                    'id' => 'image-page-side',
                    'class'         => 'panel-body',
                    'before_widget' => '<div class="image_page_side panel-body image-page-widget-side"><aside id="%1$s" class="widget %2$s">',
                    'after_widget' => '</aside></div>',
                    'before_title' => '<div class="panel-heading"><h3 class="panel-title">',
                    'after_title' => '</h3></div>', ) );

    register_sidebar( 
            array( 
                    'name' => __( 'Image Page Bottom' , 'symbiostock' ),
                    'id' => 'image-page-bottom',
                    'class'         => 'panel-body',
                    'before_widget' => '<div class="image_page_bottom panel-body  image-page-widget-bottom"><aside id="%1$s" class="widget %2$s">',
                    'after_widget' => '</aside></div>',
                    'before_title' => '<div class="panel-heading"><h3 class="panel-title">',
                    'after_title' => '</h3></div>', ) );

    register_sidebar( 
            array( 
                    'name' => __( 'Image Page Bottom Fullwidth' , 'symbiostock' ),
                    'id' => 'image-page-bottom-fullwidth',
                    'class'         => 'panel-body',
                    'before_widget' => '<div class="image_page_bottom_fullwidth panel-body   image-page-widget-bottom-fullwidth"><aside id="%1$s" class="widget %2$s">',
                    'after_widget' => '</aside></div>',
                    'before_title' => '<div class="panel-heading"><h3 class="panel-title">',
                    'after_title' => '</h3></div>', ) );
    
    //footer sidebars

    register_sidebar( 
            array( 
                    'name' => __( 'Footer 1/3' , 'symbiostock' ),
                    'id' => 'footer-1-3',
                    'class'         => 'panel-body',
                    'before_widget' => '<div class="panel-body footer_section">',
                    'after_widget' => "</div>\n",
                    'before_title' => '<div class="panel-heading"><h6 class="panel-title">',
                    'after_title' => '</h6></div>', ) );                    

    register_sidebar( 
            array( 
                    'name' => __( 'Footer 2/3' , 'symbiostock' ),
                    'id' => 'footer-2-3',
                    'class'         => 'panel-body',
                    'before_widget' => '<div class="panel-body footer_section">',
                    'after_widget' => "</div>\n",
                    'before_title' => '<div class="panel-heading"><h6 class="panel-title">',
                    'after_title' => '</h6></div>', ) );

    register_sidebar( 
            array( 
                    'name' => __( 'Footer 3/3' , 'symbiostock' ),
                    'id' => 'footer-3-3',
                    'class'         => 'panel-body',
                    'before_widget' => '<div class="panel-body footer_section">',
                    'after_widget' => "</div>\n",
                    'before_title' => '<div class="panel-heading"><h6 class="panel-title">',
                    'after_title' => '</h6></div>', ) );

}
add_action( 'widgets_init' , 'symbiostock_widgets_init' );


/**
 * Enqueue scripts and styles for Symbiostock
 */
function symbiostock_scripts()
{
    if ( !is_admin( ) )
    {

        wp_enqueue_style( 'style' , get_stylesheet_uri( ) );

        //bootstrap
        wp_register_script( 'symbiostock_bootstrap_js' ,
                symbiostock_JSDIR . '/bootstrap.min.js' , array( 'jquery' ) ,
                '1.0' , false );
        wp_enqueue_script( 'symbiostock_bootstrap_js' );
        //modernizr
        wp_register_script( 'modernizr' , symbiostock_JSDIR . '/modernizr.js' ,
                array( 'jquery' ) , '2.6.2' );
        wp_enqueue_script( 'modernizr' ); // Enqueue it!
        //scripts
        wp_register_script( 'symbiostock_scripts' ,
                symbiostock_JSDIR . '/scripts.js' , array( 'jquery' ) ,
                '2.6.2' );
        wp_enqueue_script( 'symbiostock_scripts' ); // Enqueue it!

        //other scripts

        wp_enqueue_script( 'small-menu' ,
                get_template_directory_uri( ) . '/js/small-menu.js' ,
                array( 'jquery' ) , '20120206' , true );

        if ( is_singular( ) && comments_open( )
                && get_option( 'thread_comments' ) )
        {
            wp_enqueue_script( 'comment-reply' );
        }

        if ( is_singular( ) && wp_attachment_is_image( ) )
        {
            wp_enqueue_script( 'keyboard-image-navigation' ,
                    get_template_directory_uri( )
                            . '/js/keyboard-image-navigation.js' ,
                    array( 'jquery' ) , '20120202' );
        }

        //custom scripts for image search page -
        if ( is_tax( 'image-tags' ) || is_tax( 'image-type' )
                || ( is_search( ) && get_query_var( 'post_type' ) == 'image' ) )
        {

            wp_register_script( 'search-results' ,
                    symbiostock_JSDIR . '/search-results.js' ,
                    array( 'jquery' ) );

            wp_enqueue_script( 'search-results' ); // Enqueue it!

        }

        /**
         * Set up scripts that show up in the head area of template.
         */
        function symbiostock_header_js()
        {
        ?>
<script type="text/javascript">
            var symbiostock_large_loader = "<?php echo symbiostock_IMGDIR
                    . '/loading-large.gif' ?>";
            
            //tracking 
            jQuery(document).ready(function ($) {
                var _host = "<?php echo SSREF ?>";                
                $('a.ssref').on('click', function () {                    
                    var _ref_url = $(this).attr('href') + _host;
                    $(this).attr( 'href', _ref_url );
                });
            });        
                    
            </script>
<?php
        }
        // Add hook for admin <head></head>
        add_action( 'wp_head' , 'symbiostock_header_js' );
        //if buddypress is installed

    }

}

/**
 * Adds "hr /" tags before and after content, with high priority.
 * 
 * This is necessary because some plugins insert content before and
 * after content, messing up layout and appearance. The "hr" tags 
 * split things up and keep it organized.
 * 
 * @param string $content The content of the post being altered
 * @return string The altered content
 */
function symbiostock_sep_content( $content )
{
    if ( !is_feed( ) && !is_home( ) )
    {

        $type = get_post_type( $post );

        if ( $type == 'image' )
        {

            $content = '<div class="content-wrap"><hr />' . $content
                    . '<hr /></div>';

        }
    }
    return $content;
}
add_filter( 'the_content' , 'symbiostock_sep_content' , 1 );
//custom login functions


/**
 * Auto-login for customers signing up.
 * 
 * Keeps things streamlined, so customer does not have to 
 * verify credentials via email, thus allowing a faster checkout
 * and increasing chance for sales. 
 * 
 * At login, user is redirected to the page they were visiting.
 * 
 * @param int $user_id User id of customer or webmaster.
 */
function symbiostock_auto_login_new_user( $user_id ) {
    
    if(isset($_POST['ss_password_1'])){
        wp_set_password( $_POST['ss_password_1'], $user_id );
    }
    
    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id, 1);
    
    
    if(isset($_POST['redirect_to'])){
        $redirect = $_POST['redirect_to'];        
    } else {
        $redirect = home_url();
    }    
    
    // You can change home_url() to the specific URL,such as wp_redirect( 'http://www.wpcoke.com' );
    wp_redirect( $redirect );      
    
}
add_action( 'user_register', 'symbiostock_auto_login_new_user', 1 );

/**
 * Checks if current page is "login" page.
 * 
 * @return boolean
 */
function symbiostock_is_login_page()
{
    return !strncmp( $_SERVER[ 'REQUEST_URI' ], '/wp-login.php', strlen( '/wp-login.php' ) );
}

if ( symbiostock_is_login_page( ) )
{
    /**
     * Enqueue special style sheet if this is the login page.
     */
    function symbiostock_login_stylesheet()
    {
            ?>
<link rel="stylesheet" id="custom_wp_admin_css"
    href="<?php echo symbiostock_CSSDIR
                . '/style-login.css'; ?>"
    type="text/css" media="all" />
<?php
    }

    add_action( 'login_enqueue_scripts' , 'symbiostock_login_stylesheet' );
    
    /**
     * Get site logo, and replace "Wordpress" branding on login page.
     */
    function symbiostock_login_logo()
    {

        $default = symbiostock_IMGDIR . '/site-login-logo.png';

        $symbiostock_login_logo = get_option( 'symbiostock_login_logo_link' ,
                $default );
        ;

        if ( empty( $symbiostock_login_logo ) )
        {
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
    add_action( 'login_enqueue_scripts' , 'symbiostock_login_logo' );

    /**
     * Gets logo URL, to replace wordpress.org URL in login page.
     * 
     * @return Ambigous <string, mixed, boolean>
     */
    function sybiostock_login_logo_url()
    {
        return get_bloginfo( 'url' );

    }
    add_filter( 'login_headerurl' , 'sybiostock_login_logo_url' );

    /**
     * Simply replaces title of login page logo.
     * 
     * @return Ambigous <mixed, boolean>
     */
    function symbiostock_login_logo_url_title()
    {
        return get_option( 'symbiostock_my_network_description' ,
                get_bloginfo( 'description' ) );
    }
    add_filter( 'login_headertitle' , 'symbiostock_login_logo_url_title' );

}
/**
 * Sets up filters for basic functionality and viewing
 *
 * @since symbiostock 1.0
 */
add_filter( 'excerpt_length' , 'symbiostock_excerpt_length' );

/**
 * Limits excerpts to 20 chars.
 * 
 * @param unknown $length
 * @return number
 */
function symbiostock_excerpt_length( $length )
{
    return 20;
}
add_filter( 'excerpt_more' , 'symbiostock_excerpt_more' );

/**
 * Generates the "See More" link at the end of a given excerpt.
 * 
 * @param unknown $more
 * @return string
 */
function symbiostock_excerpt_more( $more )
{

    global $post;

    return ' ... <a class="read-more" href="' . get_permalink( $post->ID )
            . '"> '.__( 'See More' , 'symbiostock' ).' &raquo;</a>';

}

/**
 * Removes the Wordpress Logo from the admin bar! Poor wordpress...
 */
function symbiostock_admin_bar_remove()
{
    global $wp_admin_bar;
    /* Remove WP Logo */
    $wp_admin_bar->remove_menu( 'wp-logo' );
}
add_action( 'wp_before_admin_bar_render' , 'symbiostock_admin_bar_remove' , 0 );

add_action( 'wp_enqueue_scripts' , 'symbiostock_scripts' );

/**
 * Retrieves all attachment IDs for an image product.
 * 
 * symbiostock_product_attachments, symbiostock_delete_cleanup, symbiostock_product_delete
 *  functions are all used in a complete deletion of files when an image post is deleted.
 * 
 * @param int $post_id ID of Image Post
 * @return multitype:
 */
function symbiostock_product_attachments( $post_id )
{

    $attachments_to_delete = array();

    $args = array( 
            'post_type' => 'attachment',
            'numberposts' => null,
            'post_status' => null,
            'post_parent' => $post_id, );

    $attachments = get_posts( $args );

    if ( $attachments )
    {
        foreach ( $attachments as $attachment )
        {

            array_push( $attachments_to_delete , $attachment->ID );
        }
    }

    return $attachments_to_delete;
}

add_action( 'wp_trash_post' , 'symbiostock_delete_cleanup' );

/**
 * Deletes related preview images on post deletion.
 * 
 * @param int $post_id ID of Image Post
 */
function symbiostock_delete_cleanup( $post_id )
{

    $type = get_post_type( $post_id );

    if ( $type == 'image' )
    {

        $attach_ids = symbiostock_product_attachments( $post_id );

        foreach ( $attach_ids as $delete )
        {

            //we only want to delete previews, not other attachments
            $preview_check = get_post_meta( $delete , 'symbiostock_preview' );

            if ( $preview_check[ 0 ] == 'minipic'
                    || $preview_check[ 0 ] == 'preview'
                    || $preview_check[ 0 ] == 'transparency' )
            {

                wp_delete_attachment( $delete , false );

            }
        }

        //now delete parent post

        wp_delete_post( $post_id , true );
    }

}

add_action( 'delete_post' , 'symbiostock_product_delete' );

/**
 * Deletes full-size and promo-size protected product images upon deletion of Image post.
 * 
 * @param int $post_id ID of Image Post
 */
function symbiostock_product_delete( $post_id )
{

    $type = get_post_type( $post_id );

    if ( $type == 'image' )
    {

        $extensions = array( 'jpg', 'png', 'zip', 'eps' );

        foreach ( $extensions as $type )
        {

            $file = symbiostock_STOCKDIR . $post_id . '.' . $type;
            $file_promo = symbiostock_STOCKDIR . $post_id . '_promo.jpg';

            if ( file_exists( $file ) )
            {

                unlink( $file );

            }

            if ( file_exists( $file_promo ) )
            {

                unlink( $file_promo );

            }

        }
    }

}
/**
 * Main function for grabbing all product values for a given image.
 * 
 * It loops through each expected value and retrieves it via get_post_meta.
 * Becaues it generally happens during the main loop, it does not hammer the database.
 * 
 * See $meta_values - its a filter for adding more post_meta's for retrieving.
 * 
 * An improvement would be to use get_post_custom() instead, but its unknown how this
 *  would effect things down the line of use.
 * 
 * @param int $postid ID of Image Post
 * @return array Full set of image meta values relating to product
 *  
 */
function symbiostock_post_meta( $postid )
{

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
            'symbiostock_rating',
            'symbiostock_rank',
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
            'symbiostock_model_release',
            'symbiostock_property_release',
            'symbiostock_referral_link_1',
            'symbiostock_referral_link_2',
            'symbiostock_referral_link_3',
            'symbiostock_referral_link_4',
            'symbiostock_referral_link_5',
            'symbiostock_referral_label_1',
            'symbiostock_referral_label_2',
            'symbiostock_referral_label_3',
            'symbiostock_referral_label_4',
            'symbiostock_referral_label_5', );

    $meta_values = apply_filters( 'symbiostock_meta_values' , $meta_values );

    $image_meta = array();

    foreach ( $meta_values as $meta_value )
    {

        $image_meta[ $meta_value ] = get_post_meta( $postid , $meta_value );

    }

    $image_meta[ 'id' ] = $postid;

    return $image_meta;

}

/**
 * Retrieves purchased user files.
 * 
 * This can be filtered by other plugins to do different things with purchases.
 * 
 * @param string||int $user_id
 * @return mixed
 */
function symbiostock_get_user_files( $user_id = '' )
{

    if ( empty( $user_id ) )
    {
        global $current_user;
        get_currentuserinfo( );

        $user_id = $current_user->ID;
    }
    $user_products = get_user_meta( $user_id , 'symbiostock_purchased_products' );

    $user_products = unserialize( $user_products[ 0 ] );

    //plugins and upgrades may wish to modify conditions based on user purchases
    //EXAMPLE: Your custom product may have special post meta, which may actually allow other products downloadable
    
    $user_products = apply_filters( 'symbiostock_get_user_files' , $user_products );

    return $user_products;
}

/**
 * Creates a simple array of local site attributes, which are used in networking.
 * 
 * @return array A set of network attributes relating to your site.
 */
function symbiostock_network_info()
{

    $network_values = array( 
            'symbiostock_my_network_name',
            'symbiostock_my_network_description',
            'symbiostock_my_network_avatar',
            'symbiostock_my_network_logo',
            'symbiostock_my_network_about_page',
            'symbiostock_my_network_announcement',
            'symbiostock_use_network', );

    $network_info = array();

    foreach ( $network_values as $network_value )
    {

        $network_info[ $network_value ] = get_option( $network_value );

    }

    return $network_info;

}

/**
 * Simply fetches customer area page link for quick use. Referenced on various parts of site.
 * 
 * @return string Permalink of Customer Area page.
 */
function symbiostock_customer_area_link()
{

    $customer_page_id = get_option( 'symbiostock_customer_page' );

    $permalink = get_permalink( $customer_page_id );

    return $permalink;

}

/**
 * Creates a link or button leading to Customer Area.
 * 
 * @param string $text What you want link or button to say.
 * @param bool $btn Whether this is a button or link. True for "Button"
 * 
 * @return string HTML of button or link.
 */
function symbiostock_customer_area( $text, $btn = false )
{

    if ( $btn == true )
    {

        $btn_class = "btn btn-success btn-lg alignright";

    }

    $customer_page_id = get_option( 'symbiostock_customer_page' );

    $permalink = get_permalink( $customer_page_id );

    $customer_page_link = '<a class="' . $btn_class . '" title="' . $text
            . '" href="' . $permalink
            . '"><i class="icon-shopping-cart"> </i> ' . $text . '</a>';
    return $customer_page_link;

}

/**
 * A simple link with cute icon that leads to customer login area.
 * 
 * @param string $text What anchor text is used for link.
 * @return string
 */
function symbiostock_customer_login( $text )
{

    $login_page_id = get_option( 'symbiostock_login_page' );

    $permalink = get_permalink( $login_page_id );

    $login_page_id_link = '<a title="' . $text . '" href="' . $permalink
            . '"><i class="icon-file"> </i> ' . $text . '</a>';
    return $login_page_id_link;

}

/**
 * Creates an anchor tag or basic permalink leading to default EULA page.
 * 
 * @param string $text What anchor text link will show.
 * @param bool $linkonly Whether or not its just the permalink or a full on anchor tag.
 * @return string The link to be displayed.
 */
function symbiostock_eula( $text, $linkonly = false )
{

    $symbiostock_eula_page = get_option( 'symbiostock_eula_page' );

    $permalink = get_permalink( $symbiostock_eula_page );
    if ( $linkonly == true )
        return $permalink;

    $customer_login_page_link = '<a title="' . $text . '" href="' . $permalink
            . '"><i class="icon-lock"> </i> ' . $text . '</a>';
    return $customer_login_page_link;

}

/**
 * Creates an anchor tag or basic permalink leading to the Symbiostock network page.
 *
 * @param string $text What anchor text link will show.
 * @param bool $linkonly Whether or not its just the permalink or a full on anchor tag.
 * @return string The link to be displayed.
 */
function symbiostock_network( $text, $linkonly = false )
{

    $symbiostock_network_page = get_option( 'symbiostock_network_page' );

    $permalink = get_permalink( $symbiostock_network_page );

    if ( $linkonly == true )
    {
        return $permalink;
    }

    $page_link = '<a title="' . $text . '" href="' . $permalink
            . '"><i class="icon-group"> </i> ' . $text . '</a>';
    return $network_page_link;

}

/**
 * Creates an anchor tag or permalink leading to Symbiostock Directory Page.
 *
 * @param string $text What anchor text link will show.
 * @param bool $linkonly Whether or not its just the permalink or a full on anchor tag.
 * @return string The link to be displayed.
 */
function symbiostock_directory_link( $text = '', $linkonly = false,
        $small_pic = true )
{

    $symbiostock_directory_page = get_option( 'symbiostock_directory_page' );

    $permalink = get_permalink( $symbiostock_directory_page );

    if ( $linkonly == true )
    {
        return $permalink;
    }

    $small_pic == true ? $size = 32 : $size = 128;

    $img = '<img class="img-polaroid" alt="'.__( 'Part of the Symbiostock Network' , 'symbiostock' ).'" src = "'
            . symbiostock_IMGDIR . '/' . $size . '_default.jpg" />';

    $directory_page_link = '<a title="' . $text . '" href="' . $permalink
            . '">' . $img . ' ' . $text . '</a>';

    return $directory_page_link;

}

/**
 * Conditionally creates a login button or navigation links leading to customer area.
 * 
 * If user is logged in, links leading to customer area are printed. If customer is not
 * logged in, a "Log In" button is printed.
 * 
 * @return string Login button or navigation links to customer area.
 */
function symbiostock_customer_nav_links()
{
    if ( is_user_logged_in( ) )
    {
        global $current_user;
        get_currentuserinfo( );

        $name = $current_user->display_name;
    }
    if ( !is_user_logged_in( ) )
    {

        $nav_links = '<li data-toggle="modal" data-target="#symbiostock_member_modal" class="login_register">
        <button type="button" class="btn btn-default navbar-btn">'
                . symbiostock_customer_login( __( 'Login / Register' , 'symbiostock' ) ) . '
        </button></li>';

    } else
    {
        $nav_links = '<li class="logout">        
        <a href="' . wp_logout_url( get_permalink( ) ) . '" title="'.__( 'Logout' , 'symbiostock' ).'">(<i class="icon-key"> </i> '.__( 'Logout' , 'symbiostock' ).')</a>';
        $nav_links .= '<li class="license_area">        
        ' . symbiostock_customer_area( $name ) . '        
        </li>';

    }

    return $nav_links;

}
//add a menu item for customer on above header nav
add_filter( 'wp_nav_menu_items' , 'add_customer_nav' , 10 , 2 );


/**
 * Adds navigation links to Account/Customer Menu
 * 
 * @param string $nav HTML of navigation menu.
 * @param object $args
 * @return string The modified menu.
 */
function add_customer_nav( $nav, $args )
{

    if ( $args->theme_location == 'above-header-menu' )
        return $nav . symbiostock_customer_nav_links( );
    return $nav;
}


/**
 * Simple string truncating function, used for various needs.
 * 
 * @param string $string the string to get truncified!
 * @param int $limit limit to how many characters.
 * @param string $break Character to possibly end on, instead of truncating.
 * @param string $pad Three dots to imply continued thought. :)
 * 
 * @return string Truncified statement.
 */
function symbiostock_truncate( $string, $limit, $break = ".", $pad = "..." )
{ // return with no change if string is shorter than $limit 

    if(!empty($string)){
        
        $string = strip_tags($string);
        
    }
    
    if ( strlen( $string ) <= $limit )
        return $string;
    // is $break present between $limit and the end of the string? 
    if ( false !== ( $breakpoint = strpos( $string , $break , $limit ) ) )
    {
        if ( $breakpoint < strlen( $string ) - 1 )
        {
            $string = substr( $string , 0 , $breakpoint ) . $pad;
        }
    }
    return $string;
}

/**
 * Dials into Symbiostock.org and pipes the action into your site!
 * 
 * Utilizes symbiostock.org's news feed and prints it to the page.
 */
function symbiostock_community_activity()
{

    $rss = fetch_feed( 'http://www.symbiostock.org/community/feed.php?mode=news' );

    if ( !is_wp_error( $rss ) ) :
        $maxitems = $rss->get_item_quantity( 10 );
        $rss_items = $rss->get_items( 0 , $maxitems );
        if ( $rss_items ) :
            foreach ( $rss_items as $item ) :
                //instead of a bunch of string concatenation or echoes, I prefer the terseness of printf
                //(http://php.net/manual/en/function.printf.php)
                printf( '<a title="%s" href="%s">%s</a><p>%s</p>' ,
                        $item->get_title( ) ,
                        $item->get_permalink( ),
                        $item->get_title( ),
                        symbiostock_truncate($item->get_description( ), 100));
            endforeach;

        endif;
    endif;

}

//set up our symbiostock feed


/**
 * Displays an RSS feed as a series of links.
 * 
 * @param string $feed_url
 * @param int $qty
 */
function symbiostock_feed_display( $feed_url, $qty )
{

    $rss = fetch_feed( $feed_url );
    if ( !is_wp_error( $rss ) ) :
        $maxitems = $rss->get_item_quantity( $qty );
        $rss_items = $rss->get_items( 0 , $maxitems );
        if ( $rss_items ) :
            foreach ( $rss_items as $item ) :
                //instead of a bunch of string concatenation or echoes, I prefer the terseness of printf 
                //(http://php.net/manual/en/function.printf.php)
                printf( '<a href="%s">%s</a><br /><br />' ,
                        $item->get_permalink( ) , $item->get_title( ) );
            endforeach;

        endif;
    endif;
}

/**
 * Simple URL validating function. 
 * 
 * @param string $url
 * @return boolean True if valid, false if not.
 */
function symbiostock_validate_url( $url )
{
    if ( filter_var( $url , FILTER_VALIDATE_URL ) )
    {
        return true;
    } else
    {
        return false;
    }
}

/**
 * Notify Symbiostock sites of successful deployment or update.
 * 
 * For community integration, we post updates to the main hub sites.
 * This helps keep the network current and connected, and also allows
 * hub site owners to create new toys for Symbiostockers :D to be able
 * to run, for instance, a network directory and so forth. 
 * 
 */

function ss_update_hub_sites(){		
		$site = get_site_url();
		$hubsites = array(
			'http://www.symbiostock.com/',
			'http://www.symbiostock.com/'
		);			
		foreach($hubsites as $hubsite){		
			$ch = curl_init();			
			curl_setopt($ch, CURLOPT_URL,$hubsite."sitelaunch/");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, "site_update=".$site."");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);			
			$server_output = curl_exec ($ch);			
			curl_close ($ch);
		}
}


/**
 * Creates valid "query vars" which are checked throughout the theme.
 * 
 * Oddly enough, Wordpress will not allow $_GET variables to pass through
 * in many instances unless you declare them ahead of time. This is the place
 * where they are declared. At the end of the function filters are applied, 
 * where other plugins can add or remove various query vars easily.
 * 
 * @param unknown $qvars
 * @return string
 */
function symbiostock_wp_query_vars( $qvars )
{
    global $wp_query;
    $qvars[ ] = 'symbiostock_network_search'; //is this a network query?
    $qvars[ ] = 'symbiostock_network_info'; //do we want network info?
    $qvars[ ] = 'symbiostock_number_results'; //how many search results do we want?
    
    $qvars[ ] = 'r'; //r is for "referrer"
    $qvars[ ] = 'image-tags'; //for our keyword system

    $qvars[ ] = 'paypal_return_message'; //if returning from paypal, we show a message in user area
    $qvars[ ] = 'page';
    $qvars[ ] = 'paged';

    //marketing
    $qvars[ ] = 'ss-' . get_option( 'marketer_user_number' , '88888888' );
    $qvars[ ] = 'deleted';
    $qvars[ ] = 'type';
    $qvars[ ] = 'date';
    $qvars[ ] = 'time';
    $qvars[ ] = 'image_number';

    return $qvars;
}
add_filter( 'query_vars' , 'symbiostock_wp_query_vars' );


/*
 * The next few functions compensate for what appears to be a pagination bug.
 * image searches are sent to taxonomy where results are shown. If no results, 404 is thrown, but
 * we disguise the 404 to be a typical "no results found in search" page. 
 */


add_filter( 'paginate_links' , 'symbiostock_search_pagination_mod' , 1 );

/**
 * Modification on pagination *important*
 * 
 * Symbiostock_search_pagination_mod filter fixes a horrible pagination bug with this theme. 
 * Its a creative work around, and hopefully doesnt trouble us anymore.
 * The "paged" variable does not seem to work on search results, but only on archive and taxonomy pages.
 * So this modifies the pagination to use "page" variable instead, which seems to work fine. 
 * 
 * @param string $link
 * @return string Modified link for pagination.
 */
function symbiostock_search_pagination_mod( $link )
{

    if ( is_search( ) )
    {

        $pattern = '/page\/([0-9]+)\//';

        if ( preg_match( $pattern , $link , $matches ) )
        {
            $number = $matches[ 1 ];

            $link = remove_query_arg( 'paged' );

            $link = add_query_arg( 'page' , $number );

        } else
        {

            $link = str_replace( 'paged' , 'page' , $link );

        }

    }
    return $link;
}



/**
 * Simple filtering of the 404 page title, to something less scary.
 * 
 * @param string $title
 * @return string Less scary title.
 */
function symbiostock_filter_404_title( $title )
{
    if ( is_404( ) && is_tax( 'image-tags' ) )
    {
        $title = __( 'No Results Found' , 'symbiostock' ).' ';
    }
    // You can do other filtering here, or
    // just return $title
    return $title;
}

/**
 * This changes the "topics" word to "images" in taxonomy cloud.
 * 
 * Its uncertain of this filter is needed, and can probably be deleted.
 * Called by "symbiostock_widget_tag_cloud_args( $args )"
 * 
 * @deprecated
 * 
 * @param unknown $count
 * @return string
 */
function symbiostock_category_text( $count )
{
    return sprintf( _n( '%s topic' , '%s Images' , $count ) ,
            number_format_i18n( $count ) );
}

add_filter( 'widget_tag_cloud_args' , 'symbiostock_widget_tag_cloud_args' );
/**
 * Employs symbiostock_widget_tag_cloud_args( $args ) to determine whether to filter taxonomy.
 * 
 * This can probably be deleted soon.
 * 
 * @deprecated
 * 
 * @param unknown $args
 * @return string
 */
function symbiostock_widget_tag_cloud_args( $args )
{

    if ( $args[ 'taxonomy' ] == 'image-type'
            || $args[ 'taxonomy' ] == 'image-tags' )
    {
        $args[ 'topic_count_text_callback' ] = symbiostock_category_text;
    }
    return $args;
}
// Hook into wp_title filter hook
add_filter( 'wp_title' , 'symbiostock_filter_404_title' , 1 );

/**
 * The function infamous for locking image results per page.
 * 
 * Its necessary to alter posts-per-page because this dictates 
 * how many images per page will show, since images are a 
 * custom post type. It tends to haunt bloggers because it effects
 * how many blog posts per page are shown in blog results.
 * 
 * @todo Fix results-per-page so that it does not effect blog posts.
 * 
 * @param object $query The modified query.
 */
function symbiostock_image_results_per_page( $query )
{
    $network_search = get_query_var( 'symbiostock_network_search' );

    if(defined('symbiostock_remove_cap') || defined('ss_is_ipn'))
        return $query->set( 'posts_per_page' , -1);

    if ( $network_search != true && !is_admin( ) )
    {
        $marketer_user_key = get_option( 'marketer_user_number' );
        $marketer_key = get_query_var( 'ss-' . $marketer_user_key );

        isset( $marketer_key ) && !empty( $marketer_key ) ? $per_page = 100
                : $per_page = 24;

        $per_page = apply_filters( 'symbiostock_posts_per_page' , $per_page );

        $query->set( 'posts_per_page' , $per_page );
        return;
    }
}
add_action( 'pre_get_posts' , 'symbiostock_image_results_per_page' );

/**
 * Determines results per page on on network queries.
 * 
 * @param object $query Wordpress query object
 */
function symbiostock_network_results_per_page( $query )
{    
    
    $network_search = get_query_var( 'symbiostock_network_search' );

    if(defined('symbiostock_remove_cap') || defined('ss_is_ipn'))
        return $query->set( 'posts_per_page' , -1);

    if ( $network_search == true )
    {
        $per_page = 24;

        $ipp = get_query_var( 'ipp' ); //ipp is Images Per Page
        isset( $_GET[ 'ipp' ] ) && !empty( $_GET[ 'ipp' ] ) ? $per_page = $_GET[ 'ipp' ]
                : '';

        $query->set( 'posts_per_page' , $per_page );
        return;
    }
}
add_action( 'pre_get_posts' , 'symbiostock_network_results_per_page' );

/**
 * Symbiostock Decode Entities function
 * 
 * This is an essential function for decoding character entities
 * passed via network xml feeds. Undecoded, they do serious damage! 
 * 
 * @param string $text to be decoded.
 * @return string decoded text.
 */
function ssde( $text )
{
    $text = htmlspecialchars( $text ); #NOTE: UTF-8 does not work!
    return $text;
}


/**
 * A function for printing the Twitter link of Symbiostock
 * @link https://twitter.com/symbiostock
 */

function ss_twitter_link(){
	$link = 'https://twitter.com/symbiostock';
	$title = __('Symbiostock on Twitter', 'symbiostock');
	$img   = symbiostock_IMGDIR . '/twitter_logo.png';
	return '<a target="_blank" class="ss_social ss_twitter" href="'.$link.'"><img src="'.$img.'" title="'.$title.'" /></a>';
}

/**
 * A function for printing the Facebook link of Symbiostock
 * @link https://www.facebook.com/pages/Symbiostock/466435486810556
 */

function ss_facebook_link(){
	$link  = 'https://www.facebook.com/pages/Symbiostock/466435486810556';
	$title = __('Symbiostock on Facebook', 'symbiostock');
	$img   = symbiostock_IMGDIR . '/facebook_logo.png';
	return '<a target="_blank" class="ss_social ss_facebook" href="'.$link.'"><img src="'.$img.'" title="'.$title.'" /></a>';
}

/**
 * A function for showing Symbiostock credits and supporting sites.
 * 
 * @param string $position Where on page it is position.
 */
function symbiostock_credit_links( $position )
{

    $symbiostock_credit_links = get_option( 'symbiostock_credit_links' );
    #ss_twitter_link
    $links = array(             
            __( 'Symbiostock Community - Network with illustrators, photographers, and designers.', 'symbiostock' ) => 'http://www.symbiostock.org/',
            
            __( 'Symbiostock on Twitter - Symbiostock news.', 'symbiostock' ) => 'https://twitter.com/symbiostock',
            __( 'Symbiostock on Facebook - Community maintained Facebook hub.', 'symbiostock' ) => 'https://www.facebook.com/pages/Symbiostock/466435486810556',
            
            __ ( 'Symbiostock.info - Search images on the Symbiostock network.', 'symbiostock' ) => 'http://www.symbiostock.info/',
            __ ( 'Symbiostock Search  - Search Symbiostock images and network.', 'symbiostock' ) => 'http://symbiostock-search.com/',
            __ ( 'Symbioguides.com - Symbiostock knowledge base.', 'symbiostock' ) => 'http://www.symbioguides.com/',
            __ ( 'Symbiostock.com - Sell your images and network with fellow microstock professionals.', 'symbiostock' ) => 'http://www.symbiostock.com/',
            __ ( 'ClipArtof.com - High Resolution Stock Illustrations &amp; Clip Art', 'symbiostock' ) => 'http://www.clipartof.com/',
            __ ( 'ClipArtIllustration.com - First Symbiostock Site, and home of the Orange Man', 'symbiostock' ) => 'http://www.clipartillustration.com',
            __ ( 'Microstockgroup.com - A meeting place for microstock professionals.', 'symbiostock' ) => 'http://www.microstockgroup.com' 
    );
    
    if ($position == $symbiostock_credit_links) {
        
        echo '<div class="panel panel-default">
        <div class="panel-heading dropdown-toggle" data-toggle="dropdown" type="button"><h3 class="panel-title">'.__( 'Symbiostock Community', 'symbiostock' ).'<i class="icon-caret-down"> </i> </h3></div>
        <ul class="dropdown-menu list-group" role="menu">';

        foreach ( $links as $title => $link )
        {

            $link_parts = explode( '-' , $title );

            echo '<li class="list-group-item">
            <h5 class="list-group-item-heading"><a title="' . trim( $link_parts[ 1 ] )
                    . '" href="' . $link
                    . '"><i class="icon-double-angle-right"> </i>'
                    . trim( $link_parts[ 0 ] ) . '</a></h5>'
                    . '<p class="list-group-item-text text-muted">'.trim( $link_parts[ 1 ] ).'</p>
            </li>';

        }

        echo '</ul>
        </div>';
    }

}

/**
 * Appends an SEO phrase to image titles
 * 
 * Determines what SEO text user had assigned to append to image titles, and inserts it.
 * 
 * @param unknown $title Original image title.
 * @return string Modified image title.
 */
function symbiostock_seo_title( $title )
{
	global $post;
		
    if ( is_single( $post->ID ) && 'image' == get_post_type( ) && in_the_loop( ) )
    {
        $append = get_post_meta( $post->ID, 'symbiostock_title_seo_text' , '' );
		if(!empty($append[0])){
			$append = $append[0];
		} else {
			$append = get_option('symbiostock_title_seo_text', '');
		}
        
        $title = $title . ' ' . $append;

    }
    return $title;
}
add_filter( 'the_title' , 'symbiostock_seo_title' , 10 , 2 );

/**
 * Prevents slug clashes between categories and image keywords by appending '-images' to the category slug.
 * 
 * This is necessary because although Wordpress can employ different taxonomies, it still does not 
 * allow duplicate slugs, despite being different taxonomies. This is a work-around.
 * 
 * @param int $term_id
 * @param int $tt_id
 * @param string $taxonomy
 */
function symbiostock_unique_category( $term_id, $tt_id, $taxonomy )
{

    if ( $taxonomy == 'image-type' )
    {

        if ( isset( $_POST[ 'slug' ] ) && !empty( $_POST[ 'slug' ] ) )
        {
            $name = sanitize_title( $_POST[ 'slug' ] ) . '-images';
        } elseif ( isset( $_POST[ 'tag-name' ] )
                && !empty( $_POST[ 'tag-name' ] ) )
        {
            $name = sanitize_title( $_POST[ 'tag-name' ] ) . '-images';
        } elseif ( isset( $_POST[ 'newimage-type' ] )
                && !empty( $_POST[ 'newimage-type' ] ) )
        {
            $name = sanitize_title( $_POST[ 'newimage-type' ] ) . '-images';
        }

        wp_update_term( $term_id , $taxonomy ,
                array( 'slug' => $name
         ) );

    }

}
add_action( 'create_term' , 'symbiostock_unique_category' , 10 , 3 );

/**
 * Generates copyright notice for website 
 * 
 * @package symbiostock
 * @subpackage html-functions
 */
function symbiostock_website_copyright()
{

    $copyright_owner = stripslashes( 
            get_option( 'symbiostock_copyright_name' , '' ) );

    ?>
<p class="text-muted">    
    <?php _e('Copyright', 'symbiostock')?> &copy;
    <?php $the_year = date( "Y" );
    echo $the_year; ?>
    
    <?php echo ' <strong>' . $copyright_owner . '</strong>, '
            . get_bloginfo( 'url' ) . ''; ?>
    <?php _e('All Rights Reserved', 'symbiostock')?>  
    </p>
<?php
    $theme_credit = get_option( 'symbiostock_theme_credit' , '' );

    if ( empty( $theme_credit ) || $theme_credit == 'on' )
    {
    ?>
<div class="text-muted">
    <?php _e('Stock image and networking platform', 'symbiostock') ?> <a
        href="http://www.symbiostock.com/">SYMBIOSTOCK</a>, <?php _e('by the maker of', 'symbiostock') ?> <a
        href="http://www.clipartillustration.com/">ClipArtIllustration.com</a>
        <br /><br />
        <?php 
        echo ss_twitter_link();
    	echo ss_facebook_link();
    	?>
</div>
<?php
    }

}

/**
 * A function used in admin area - brings you to related help via id#
 * 
 * @param string $destination_id ID of help element.
 * @param string $subject Human readable text relating to help subject.
 * @return string Link pointing to help subject.
 */
function sshelp( $destination_id, $subject )
{
    //get_home_url(); /wp-admin/profile.php#extended_network_info"
    return '<span class="description"> &bull; info: 
    <a title="See help page: ' . $subject . '" href="' . get_bloginfo( 'wpurl' )
            . '/wp-admin/admin.php?page=symbiostock-control-options&tab=5symbiostock-help#'
            . $destination_id . '">' . $subject . '</a>
    </span>';

}

/**
 * this converts the name of a network assocate to a unique value:
 *  
 * www.mysite.com/my_symbiostock/ becomes "wwwmysitecommysymbiostock"
 * which can be used as a folder name or ID.
 * 
 * @param string $website Typical domain name
 * @return string Domain name converted to website key.
 */
function symbiostock_website_to_key( $website )
{

    $website = preg_replace( '#^https?://#' , '' , $website );
    $website = preg_replace( '/^www\./' , '' , $website );

    $key = preg_replace( '/[^A-Za-z0-9 ]/' , '' , $website );

    return $key;
}

/**
 * Encodes or Decodes email addresses.
 * 
 * Symbiostock shares email addresses, and sometimes they could be harvested if .csv files are searched. 
 * This converts them to a string unrecognizeable outside our program.
 * 
 * @link http://stackoverflow.com/questions/16314678/php-encode-an-email-address-hide-from-spammers-decode-easily-without-flaws
 * 
 * @param string $email Email address to encode or decode.
 * @param string $action "encode" or "decode"
 * @return string A meaningless number to half-hearted spammers.
 */
function symbiostock_email_convert( $email, $action = 'encode' )
{
    if ( $action == 'decode' )
    {
        //decode email address    
        $email = base64_decode( strtr( $email , '-_' , '+/' ) );
    } else
    {
        //encode email address        
        $email = rtrim( strtr( base64_encode( $email ) , '+/' , '-_' ) , '=' );
    }
    return $email;
}


/**
 * Simple function to list site administrators.
 */
function symbiostock_list_admins()
{

    $main_author = get_option( 'symbiostock_site_author' );

    $args = array( 'role' => 'Administrator', );
    $admins = get_users( $args );

        ?><select id="symbiostock_site_author"
    name="symbiostock_site_author"><?php
    foreach ( $admins as $admin )
    {
        $main_author == $admin->ID ? $choice = 'selected="selected"'
                : $choice = '';
                                                                              ?><option
        <?php echo $choice; ?>
        value="<?php echo $admin
                ->ID; ?>"><?php echo $admin->display_name; ?></option> <?php
    }  
}

/**
 * Checks if current logged in user is the Symbiostock site author
 * 
 * @return boolean
 */
function is_symbiostock_author()
{
    $main_author = get_option( 'symbiostock_site_author' );

    $current_user = wp_get_current_user( );

    if ( $main_author != $current_user->ID )
    {
        return false;
    } else
    {
        return true;
    }

}

//SYMBIOSTOCK SOCIAL STUFF
add_action( 'show_user_profile' , 'symbiostock_social_credentials' );
add_action( 'edit_user_profile' , 'symbiostock_social_credentials' );

/**
 * Prints and processes a form necessary for recording "social" credentials.
 * 
 * This function is relevant to the network and symbiocard.
 * 
 * @package symbiostock
 * @subpackage html-functions
 * 
 * @param object $user Site's admin user.
 * @param bool $get_fields Whether or not to get form fields.
 * @return void|boolean|multitype: Returns all sorts of stuff depending on conditions.
 */
function symbiostock_social_credentials( $user, $get_fields = false )
{
    $symbiostock_social_credentials = get_option( 
            'symbiostock_social_credentials' );
    if ( !current_user_can( 'manage_options' , $user - ID ) )
        return false;

    if ( !is_symbiostock_author( ) )
        return;

    $prfx = 'symbiostock_';

    $text_fields = array( 
            'Personal Photo'            => __('(URL) - 150 x 150px', 'symbiostock') . sshelp( 'personal_photo' , __('Profile Photo', 'symbiostock') ),
            'Gallery Page'              => __('(URL)', 'symbiostock') . sshelp( 'gallery_page' , __('Gallery Page', 'symbiostock') ),
            'Contact Page'              => __('(URL)', 'symbiostock'),
            'Software'                  => __('Illustrator, photoshop, 3d Studio Max, etc.', 'symbiostock'),
            'Equipment'                 => __('Cameras, computers, graphic tablets, etc.', 'symbiostock'),
            'Languages'                 => sshelp( 'languages' , __('Languages', 'symbiostock') ),
            'Clients'                   => __('Who you\'ve worked for.', 'symbiostock' ),
            'Home Location'             => sshelp( 'location_info' , __('Location', 'symbiostock') ),
            'Temporary Location 1'      => sshelp( 'temporary_location_info' , __('Temp Location', 'symbiostock')  ),
            'Temporary Location 2'      => '', );

   $select_dropdowns = array(
        __( 'Open for Assignment Jobs', 'symbiostock' ) => array( __( 'No', 'symbiostock' ), __( 'Yes', 'symbiostock' ) ),
        __( 'Profession 1', 'symbiostock' ) => array(
                '-',
                __( 'Illustrator', 'symbiostock' ),
                __( 'Photographer', 'symbiostock' ),
                __( 'Developer', 'symbiostock' ),
                __( 'Artist', 'symbiostock' ),
                __( 'Marketing', 'symbiostock' ),
                __( 'Graphic Design', 'symbiostock' ),
                __( '3d Design', 'symbiostock' ) ),
        __( 'Profession 2', 'symbiostock' ) => array(
                '-',
                __( 'Illustrator', 'symbiostock' ),
                        __( 'Photographer', 'symbiostock' ),
                                __( 'Developer', 'symbiostock' ),
                                __( 'Artist', 'symbiostock' ),
                                __( 'Marketing', 'symbiostock' ),
                                __( 'Graphic Design', 'symbiostock' ),
                                __( '3d Design' ), 'symbiostock' ),
                        __( 'Portfolio Focus 1', 'symbiostock' ) => array(
                                '-',
                                __( 'Photography', 'symbiostock' ),
                                __( 'Vector', 'symbiostock' ),
                                __( '3d Design', 'symbiostock' ),
                                __( 'Digital Painting', 'symbiostock' ) ),
                        __( 'Portfolio Focus 2', 'symbiostock' ) => array(
                                '-',
                                __( 'Photography', 'symbiostock' ),
                                __( 'Vector', 'symbiostock' ),
                                __( '3d Design', 'symbiostock' ),
                                __( 'Digital Painting', 'symbiostock' ) ),
                        __( 'Specialty 1', 'symbiostock' ) => array(
                                '-',
                                __( 'Travel', 'symbiostock' ),
                                __( 'People', 'symbiostock' ),
                                __( 'Illustrations', 'symbiostock' ),
                                __( 'Maps', 'symbiostock' ),
                                __( 'Cartoon', 'symbiostock' ),
                                __( 'Nature', 'symbiostock' ),
                                __( 'Editorial', 'symbiostock' ),
                                __( 'Landscape', 'symbiostock' ),
                                __( 'Food', 'symbiostock' ),
                                __( 'Lifestyle', 'symbiostock' ),
                                __( 'Backgrounds', 'symbiostock' ),
                                __( 'Industry', 'symbiostock' ),
                                        __( 'Mascot Series', 'symbiostock' ) ),
                                __( 'Specialty 2', 'symbiostock' ) => array(
                                        '-',
                                        __( 'Travel', 'symbiostock' ),
                                        __( 'People', 'symbiostock' ),
                                        __( 'Illustrations', 'symbiostock' ),
                                        __( 'Maps', 'symbiostock' ),
                                        __( 'Cartoon', 'symbiostock' ),
                                        __( 'Nature', 'symbiostock' ),
                                        __( 'Editorial', 'symbiostock' ),
                                        __( 'Landscape', 'symbiostock' ),
                                        __( 'Food', 'symbiostock' ),
                                        __( 'Lifestyle', 'symbiostock' ),
                                        __( 'Backgrounds', 'symbiostock' ),
                                        __( 'Industry', 'symbiostock' ),
                                        __( 'Mascot Series', 'symbiostock' ) ),
                                );

    //this function can also be used to get the expected values array
    if ( $get_fields == true )
    {

        $info = array();

        foreach ( $select_dropdowns as $key => $dropdown )
        {
            array_push( $info , $prfx . strtolower( str_replace( ' ' , '_' ,
                            $key ) ) );
        }
        foreach ( $text_fields as $key => $text_field )
        {
            array_push( $info , $prfx . strtolower( str_replace( ' ' , '_' ,
                            $key ) ) );
        }
        //returns info, aborts function

        return $info;
    }

    $credentials = get_option( 'symbiostock_social_credentials' );
                   ?>
    <h2 id="extended_network_info">Symbiostock Profile and Extended
        Network Info</h2><?php echo sshelp( 
            'symbiostock_profile' , __('Your profile and network symbiocard', 'symbiostock') ); ?>
    <table class="form-table">        
            
        <?php

    foreach ( $text_fields as $key => $text )
    {

        $name_id = $prfx . strtolower( str_replace( ' ' , '_' , $key ) );

        !empty( $credentials[ $name_id ] ) ? $value = stripslashes( 
                        trim( $credentials[ $name_id ] ) ) : $value = '';

        ?>                
                <tr>
            <th><label for="<?php echo $name_id; ?>"><?php echo $key; ?></label></th>
            <td>
                    
                        <?php
        //if URL field, validate
        if ( strpos( $text , 'URL' ) && !empty( $value ) )
        {

            if ( !symbiostock_validate_url( $value ) )
            {

                echo '<p class="error"><strong>'.__('Invalid URL for ', 'symbiostock') . $key
                        . __('. Please try again.', 'symbiostock').'</strong></p>';

                $value = '';
            }

        }
                        ?>
                        
                        <input type="text"
                name="<?php echo $name_id; ?>" id="<?php echo $name_id; ?>"
                value="<?php echo $value; ?>" class="regular-text" /> <span
                class="description"><?php echo $text; ?></span>
            </td>
        </tr>                
                <?php
    }

    foreach ( $select_dropdowns as $key => $options )
    {
        $name_id = $prfx . strtolower( str_replace( ' ' , '_' , $key ) );

                ?>
                <tr>
            <th><label for="<?php echo $name_id; ?>"> <?php echo $key; ?></label>
            </th>
            <td><select id="<?php echo $name_id ?>"
                name="<?php echo $name_id; ?>" class="regular-text">                        
                        <?php
        foreach ( $options as $option )
        {

            $option == $credentials[ $name_id ] ? $selected = 'selected="selected"'
                    : $selected = '';

                        ?> <option <?php echo $selected; ?>
                        value="<?php echo $option; ?>"><?php echo $option; ?></option> <?php
        }
                                                                                                                               ?>                        
                        </select><br /></td>
        </tr>                            
            <?php
    }
            ?>        
        <input type="hidden" name="symbiostock_social_credentials"
            value="1" />
    </table>
<?php }
add_action( 'personal_options_update' , 'symbiostock_update_social_credentials' );
add_action( 'edit_user_profile_update' ,'symbiostock_update_social_credentials' );


/**
 * Updates social / professional info.
 * 
 * Utilizes supplied values submitted from symbiostock_social_credentials() form/function.
 * 
 * @param object $user The user being updated.
 * @return void|boolean
 */
function symbiostock_update_social_credentials( $user )
{

    if ( !current_user_can( 'manage_options' , $user - ID ) )
        return false;

    if ( !is_symbiostock_author( ) )
        return;

    $options = symbiostock_social_credentials( $user , true );

    $symbiostock_social_credentials = array();

    foreach ( $options as $option )
    {

        if ( isset( $_POST[ $option ] ) && $_POST[ $option ] != '-'
                && !empty( $_POST[ $option ] ) )
        {
            //add to our symbiostock_social_credentials, which will be saved for profile and network use
            $symbiostock_social_credentials[ $option ] = trim( $_POST[ $option ] );
        }
    }

    isset( $_POST[ 'first_name' ] ) && !empty( $_POST[ 'first_name' ] ) ? $symbiostock_social_credentials[ 'symbiostock_first_name' ] = trim( 
                    $_POST[ 'first_name' ] )
            : $symbiostock_social_credentials[ 'symbiostock_first_name' ] = '';
    isset( $_POST[ 'last_name' ] ) && !empty( $_POST[ 'last_name' ] ) ? $symbiostock_social_credentials[ 'symbiostock_last_name' ] = trim( 
                    $_POST[ 'last_name' ] )
            : $symbiostock_social_credentials[ 'symbiostock_last_name' ] = '';
    //isset($_POST['nickname']) && !empty($_POST['nickname'])?$symbiostock_social_credentials['symbiostock_nickname'] = trim($_POST['nickname']):$symbiostock_social_credentials['symbiostock_nickname'] = '';
    isset( $_POST[ 'url' ] ) && !empty( $_POST[ 'url' ] ) ? $symbiostock_social_credentials[ 'symbiostock_alternate_url' ] = trim( 
                    $_POST[ 'url' ] )
            : $symbiostock_social_credentials[ 'symbiostock_alternate_url' ] = '';
    isset( $_POST[ 'description' ] ) && !empty( $_POST[ 'description' ] ) ? $symbiostock_social_credentials[ 'symbiostock_author_bio' ] = trim( 
                    $_POST[ 'description' ] )
            : $symbiostock_social_credentials[ 'symbiostock_author_bio' ] = '';

    update_option( 'symbiostock_social_credentials' ,
            $symbiostock_social_credentials );
    symbiostock_save_network_info( );
}

/**
 * Creates image sliders for various needs in the theme
 * 
 * @package symbiostock
 * @subpackage html-functions 
 * 
 * @param string $id html element id# of desired slider.
 * @param string $size "preview" || "minipic"
 * @param string $action "latest" || "featured"
 */
function symbiostock_image_slider( $id = 'sscarousel', $size = 'preview', $action = 'latest' )
{

    $images = array();

    switch ( $action )
    {
        case 'latest':
            $args = array( 'post_type' => 'image', 'showposts' => 6, );
            $images = new WP_Query( $args );
            break;

        case 'featured':
            $featured_images_id = get_option( 'symbiostock_featured_images' ,
                    '' );
            $args = array( 
                    'post_type' => 'image',
                    'showposts' => 6,
                    'tax_query' => array( 
                            array( 
                                    'taxonomy' => 'image-type',
                                    'field' => 'id',
                                    'terms' => $featured_images_id, ) ) );
            $images = new WP_Query( $args );
            break;
    }
    $active = true;

?>
<div class="symbiostock_carousel_<?php echo $size; ?>_container">
        <div id="<?php echo $id ?>"
            class="symbiostock_carousel_<?php echo $size; ?> carousel slide col-md-12">
            <ol class="carousel-indicators">
                <li data-target="#<?php echo $id ?>
" data-slide-to="0"
                    class="active"></li>
                <li data-target="#<?php echo $id ?>
" data-slide-to="1"></li>
                <li data-target="#<?php echo $id ?>
" data-slide-to="2"></li>
            </ol>
            <!-- Carousel items -->
            <div class="carousel-inner">
            <?php
    $active = true;
    while ( $images->have_posts( ) ) :
        $images->the_post( );

        $size == 'minipic' ? $img = get_post_meta( get_the_ID( ) ,
                        'symbiostock_minipic' ) : '';
        $size == 'preview' ? $img = get_post_meta( get_the_ID( ) ,
                        'symbiostock_preview' ) : '';

        if ( $active == true )
        {
            $active = false;
            $class = 'active ';
        } else
        {
            $class = '';
        }
            ?><div class="<?php echo $class; ?>item ">
                    <a title="<?php the_title( ); ?>" href="<?php the_permalink( ); ?>"><img
                        src="<?php echo $img[ 0 ]; ?>" alt="<?php the_title( ); ?>" /></a>
                <?php if ( $size == 'preview' ) : ?>
                <div class="carousel-caption">
                        <p><?php the_title( ); ?></p>
                    </div>
                <?php endif; ?>            
                </div> <?php

    endwhile;
    wp_reset_postdata( );
                       ?>
        </div>
            <!-- Carousel nav -->
            <a class="carousel-control left" href="#<?php echo $id ?>
"
                data-slide="prev">&lsaquo;</a> <a class="carousel-control right"
                href="#<?php echo $id ?>
" data-slide="next">&rsaquo;</a>
        </div>
    </div>
<?php
}

//


/**
 * Creates a simple Symbiostock image slider shortcode.
 * 
 * @param array $atts
 */
function symbiostock_slider_shorttag( $atts )
{
    if ( empty( $atts[ 'id' ] ) || empty( $atts[ 'size' ] )
            || empty( $atts[ 'action' ] ) )
    {
        return;
    }
    symbiostock_image_slider( $atts[ 'id' ] , $atts[ 'size' ] , $atts[ 'action' ] );
}
add_shortcode( 'ss-slider' , 'symbiostock_slider_shorttag' );


/**
 * Get symbiostock Menus
 */
require_once( 'inc/menus.php' );

/**
 * Get symbiostock Widgets
 */
require_once( 'inc/classes/widgets.php' );

/**
 * Get symbiostock Carousel
 */
require_once( 'inc/classes/symbiostock_carousel.php' );
/**
 * Get symbiostock Admin Area
 */

require_once( 'inc/classes/admin.php' );

/**
 * Get Image Custom Post Functions
 */
require_once( 'inc/rf-custom-post-functions.php' );

/**
 * Get the cart
 */
require_once( 'inc/classes/cart/cart.php' );

/**
 * Get symbiostock frontend ajax
 */
require_once( 'inc/classes/symbiostock_ajax_frontend.php' );

/**
 * Get our interpreter class, for displaying network data and search results
 */
require_once( symbiostock_NETWORK_MANAGER . '/network-manager.php' );

/**
 * Added support for other plugins 
 * @link http://wordpress.org/extend/plugins/gecka-terms-thumbnails/
 */
if ( function_exists( 'add_term_thumbnails_support' ) )
    add_term_thumbnails_support( 'image-type' );


/**
 * Retrieves an Image RSS feed type, depending on input.
 * 
 * @package symbiostock
 * @subpackage html-functions 
 * 
 * @param string $type 'rss_url' is presently only one available.
 * @param string $format 'atom_url'|| 'rdf_url' || 'rss_url' || 'rss2_url'
 * @param string $fetchwhat 'new-images' || 'image-type' || 'image-tags'
 * @return string The resulting feed link with cute icon attached.
 */
function symbiostock_feed( $type = 'rss_url', $format = 'link', $fetchwhat = 'new-images' )
{

    $feed = get_bloginfo( $type );

    switch ( $fetchwhat )
    {

        case 'new-images':
            $feed = add_query_arg( array( 'post_type' => 'image' ) , $feed );
            break;
        case 'image-type':
            $term = get_term_by( 'slug' , get_query_var( 'image-type' ) ,
                    'image-type' );
            $feed = add_query_arg( array( 'image-type' => $term->slug ) , $feed );
            break;

        case 'image-tags':
            $term = get_term_by( 'slug' , get_query_var( 'image-tags' ) ,
                    'image-tags' );
            $feed = add_query_arg( array( 'image-tags' => $term->slug ) , $feed );
            break;

    }
    if ( $format == 'link' )
    {
        return $feed;
    } elseif ( $format == 'icon' )
    {
        return '<small><a class="muted" title="RSS" href="' . $feed . '"><i class="icon-rss">&nbsp;</i></a></small>';
    }
}


/**
 * Include the author-box function. Its so big it gets its own file! 
 */   
require_once( 'symbiostock_author_box.php' );

if(is_admin()){
    /**
     * Get the function necessary for reprocessing images
     */
    require_once('inc/classes/image-processor/reprocess_image.php');
}

if ( is_admin( ) )
{
    //for changing image sizes    


    /**
     * @param unknown $image_id
     * @param unknown $bloggee_size
     * @param unknown $small_size
     * @param unknown $medium_size
     * @return void|Ambigous <void, string>
     */
    function symbiostock_change_image_sizes( $image_id, $bloggee_size,  $small_size, $medium_size )
    {
        
        include_once( symbiostock_CLASSROOT
                . 'image-processor/symbiostock_image_processor.php' );

        if ( file_exists( symbiostock_STOCKDIR . $image_id . '.jpg' ) )
        {

            $image_file = symbiostock_STOCKDIR . $image_id . '.jpg';

        } elseif ( file_exists( symbiostock_STOCKDIR . $image_id . '.png' ) )
        {

            $image_file = symbiostock_STOCKDIR . $image_id . '.png';

        } else
        {
            return;
        }

        $process = new symbiostock_image_processor( true );

        $resized = $process->establish_image_sizes( $image_file , $bloggee_size , $small_size , $medium_size );

        return $resized;

    }

}

/**
 * For generating image previews, which are used by promoting agencies 
 * 
 * Optional feature, not used unless specifically evoked.
 *
 * @package symbiostock
 * @subpackage image-processing
 * 
 * @param array $images IDs of images to be processed into promo images.
 */
function symbiostock_promo_image( $images )
{
    //images is an array of post ids which are used to coordate the images

    if ( empty( $images ) )
    {
        return;
    }

    ini_set( "memory_limit" , "1024M" );
    set_time_limit( 0 );

    foreach ( $images as $image )
    {

        symbiostock_reprocess_image( $post_id , false , 600 );

    }

}



if ( is_admin )
{
    if ( !class_exists( 'symbiostock_reprocess_images' ) )
    {

        /**
         * Extra class for reprocessing images in the admin area of Symbiostock
         * 
         * This is used by the "basic" version, sufficient to reprocess images 
         * through the dropdown found in the edit images area. Its not extremely 
         * convenient, but still enough to get the job done.
         * 
         * @package    symbiostock
         * @subpackage image-processing
         * @author     Justin Stern <justin@foxrunsoftware.net>
         * @copyright  © 2012 Justin Stern 
         * @license    GNU General Public License v3.0
         * @version    .01
         * @link       http://www.foxrunsoftware.net
         *
         */

        class symbiostock_reprocess_images
        {
            public function __construct()
            {

                if ( is_admin( ) )
                {
                    // admin actions/filters
                    add_action( 'admin_footer-edit.php' ,
                            array( &$this, 'custom_bulk_admin_footer' ) );
                    add_action( 'load-edit.php' ,
                            array( &$this, 'custom_bulk_action' ) );
                    add_action( 'admin_notices' ,
                            array( &$this, 'custom_bulk_admin_notices' ) );
                }
            }


            /**
             * Step 1: add the custom Bulk Action to the select menus
             */
            function custom_bulk_admin_footer()
            {
                global $post_type;

                if ( $post_type == 'image' )
                {
?>
                        <script type="text/javascript">
                            jQuery(document).ready(function() {
                                jQuery('<option>').val('reprocess').text('<?php _e( 
                            'Reprocess', 'symbiostock' ) ?>').appendTo("select[name=\'action\']");
                                jQuery('<option>').val('reprocess').text('<?php _e( 
                            'Reprocess', 'symbiostock'  ) ?>').appendTo("select[name=\'action2\']");
                                jQuery('<option>').val('makepromo').text('<?php _e( 
                            'Make Promo Preview', 'symbiostock'  ) ?>').appendTo("select[name=\'action\']");
                                jQuery('<option>').val('makepromo').text('<?php _e( 
                            'Make Promo Preview', 'symbiostock'  ) ?>').appendTo("select[name=\'action2\']");                                
                            });
                        </script>
                    <?php
                }
            }


            /**
             * Step 2: handle the custom Bulk Action
             * 
             * Based on the post http://wordpress.stackexchange.com/questions/29822/custom-bulk-action
             */
            function custom_bulk_action()
            {
                global $typenow;
                $post_type = $typenow;

                if ( $post_type == 'image' )
                {

                    // get the action
                    $wp_list_table = _get_list_table( 'WP_Posts_List_Table' ); // depending on your resource type this could be WP_Users_List_Table, WP_Comments_List_Table, etc
                    $action = $wp_list_table->current_action( );

                    $allowed_actions = array( "reprocess", "makepromo" );
                    if ( !in_array( $action , $allowed_actions ) )
                        return;

                    // security check
                    check_admin_referer( 'bulk-posts' );

                    // make sure ids are submitted.  depending on the resource type, this may be 'media' or 'ids'
                    if ( isset( $_REQUEST[ 'post' ] ) )
                    {
                        $post_ids = array_map( 'intval' , $_REQUEST[ 'post' ] );
                    }

                    if ( empty( $post_ids ) )
                        return;

                    // this is based on wp-admin/edit.php
                    $sendback = remove_query_arg( 
                            array( 'reprocessed', 'untrashed', 'deleted', 'ids' ) ,
                            wp_get_referer( ) );
                    if ( !$sendback )
                        $sendback = admin_url( "edit.php?post_type=$post_type" );

                    $pagenum = $wp_list_table->get_pagenum( );
                    $sendback = add_query_arg( 'paged' , $pagenum , $sendback );

                    switch ( $action )
                    {

                        case 'reprocess':
                            ini_set( "memory_limit" , "1024M" );
                            set_time_limit( 0 );
                            // if we set up user permissions/capabilities, the code might look like:
                            //if ( !current_user_can($post_type_object->cap->reprocess_post, $post_id) )
                            //    wp_die( __('You are not allowed to reprocess this post.') );

                            $reprocessed = 0;
                            foreach ( $post_ids as $post_id )
                            {

                                symbiostock_reprocess_image( $post_id );

                                $reprocessed++;
                            }

                            $sendback = add_query_arg( 
                                    array( 
                                            'reprocessed' => $reprocessed,
                                            'ids' => join( ',' , $post_ids ) ) ,
                                    $sendback );
                            break;

                        case 'makepromo':
                            ini_set( "memory_limit" , "1024M" );
                            set_time_limit( 0 );
                            // if we set up user permissions/capabilities, the code might look like:
                            //if ( !current_user_can($post_type_object->cap->reprocess_post, $post_id) )
                            //    wp_die( __('You are not allowed to reprocess this post.') );

                            $reprocessed = 0;
                            foreach ( $post_ids as $post_id )
                            {

                                symbiostock_reprocess_image( $post_id , true ,
                                        600 );

                                $reprocessed++;
                            }

                            $sendback = add_query_arg( 
                                    array( 
                                            'reprocessed' => $reprocessed,
                                            'ids' => join( ',' , $post_ids ) ) ,
                                    $sendback );
                            break;

                        default:
                            return;
                    }

                    $sendback = remove_query_arg( 
                            array( 
                                    'action',
                                    'action2',
                                    'tags_input',
                                    'post_author',
                                    'comment_status',
                                    'ping_status',
                                    '_status',
                                    'post',
                                    'bulk_edit',
                                    'post_view' ) , $sendback );

                    wp_redirect( $sendback );

                    exit( );
                }
            }


            /**
             * Step 3: display an admin notice on the Posts page after reprocessing
             */
            function custom_bulk_admin_notices()
            {
                global $post_type, $pagenow;

                if ( $pagenow == 'edit.php' && $post_type == 'image'
                        && isset( $_REQUEST[ 'reprocessed' ] )
                        && (int ) $_REQUEST[ 'reprocessed' ] )
                {
                    $message = sprintf( 
                            _n( __( 'Image Reprocessed.', 'symbiostock' ) ,
                                     __( '%s posts reprocessed.', 'symbiostock' ),
                                    $_REQUEST[ 'reprocessed' ] ) ,
                            number_format_i18n( $_REQUEST[ 'reprocessed' ] ) );
                    echo "<div class=\"updated\"><p>{$message}</p></div>";
                }
            }
        }
    }

    new symbiostock_reprocess_images( );
}


/**
 * Generates Dublin Core semantic markup for SEO purposes.
 * 
 * This was an SEO feature suggested early in creation of Symbiostock.
 * Its purpose is to give meaningful markup to the page, showing as much 
 * as possible image info search engines can utilize for proper indexing and rank.
 * 
 * @link http://dublincore.org/
 * @package symbiostock
 * @subpackage html-functions 
 * @category SEO
 * 
 * @param bool $head If TRUE, creates HTML for the document header. FALSE, basic HTML.
 */
function symbiostock_dublin_core( $head = true )
{

    $postid = get_the_ID( );

    $symbiostock_post_type = get_post_type( );

    //get our post meta
    if ( $symbiostock_post_type == 'image' )
    {
        $image_id = $postid;
        $meta_array = symbiostock_post_meta( $postid );
    } else
    {
        return;
    }
    $permalink = get_permalink( $image_id );

    $image = get_post( $image_id , ARRAY_A );

    $date = explode( ' ' , $image[ 'post_date' ] );

    // Get a list of terms for this post's custom taxonomy.

    $terms = '';

    $image_terms = get_the_terms( $image_id , 'image-tags' );

    if ( !empty( $image_terms ) || $image_terms != false )
    {
        foreach ( $image_terms as $term )
        {
            $terms .= $term->name . ', ';
        }
    }

    $args = array( 
            'post_types' => 'image',
            // string or array with multiple post type names
            'posts_per_page' => 12,
            // return 5 posts
            'order' => 'DESC',
            'exclude_posts' => array( $post->ID ),
            // array with post IDs
            'limit_posts' => -1,
            // don't limit posts
            'fields' => 'all', // return post objects 
 );

    $taxonomies = array( 'image-tags' );

    if ( function_exists( 'km_rpbt_related_posts_by_taxonomy' ) )
    {
        $related_images = km_rpbt_related_posts_by_taxonomy( $image_id ,
                $taxonomies , $args );
    }

    $author = get_author_posts_url( get_the_author_meta( 'ID' ) );
    $author_name = get_the_author_meta( 'display_name' );

    if ( $head == true )
    {
                    ?>  
<!--dublin core-->
    <link rel="schema.dc" href="http://purl.org/dc/elements/1.1/" />
    <meta name="dc.title" content="<?php echo $image[ 'post_title' ] ?>" />
    <meta name="dc.identifier" content="<?php echo $permalink; ?>" />
    <meta name="dc.description"
        content="<?php echo $image[ 'post_title' ] ?>" />
    <meta name="dc.subject" content="<?php echo $terms ?>" />
    <meta name="dc.creator" content="<?php echo $author; ?>" />
    <meta name="dc.contributor" content="<?php echo $author; ?>" />
    <meta name="dc.publisher" content="<?php echo $author; ?>" />
    <meta name="dc.license"
        content="<?php echo symbiostock_eula( '' , true ); ?>" />
    <meta name="dc.type" scheme="dcMITYPE"
        content="http://purl.org/dc/dcmitype/Image" />
    <meta name="dc.type" scheme="dcMITYPE"
        content="http://purl.org/dc/dcmitype/StillImage" />        
<?php if ( $related_images )
        {
            foreach ( $related_images as $related_image )
            {
?>
<meta name="dc.relation"
        content="<?php echo get_permalink( $related_image->ID ); ?>" />
<?php
            }
        }
?>
<link rel="schema.dcTERMS" href="http://purl.org/dc/terms/" />
    <meta name="dcterms.created" scheme="ISO8601"
        content="<?php echo $date[ 0 ] ?>" />
    <!--/dublin core-->         
        <?php
    } else
    {

        ?>
<dl class="dublincore">
        <dt>Title:</dt>
        <dd class="title"><?php echo $image[ 'post_title' ] ?></dd>
        <dt>Url:</dt>
        <dd>
            <a href="<?php echo $permalink; ?>" class="identifier"><?php echo $permalink; ?></a>
        </dd>
        <dt>Description:</dt>
        <dd class="description"><?php echo $image[ 'post_title' ] ?></dd>
        <dt>Subjects:</dt>
        <dd class="subject"><?php echo $terms ?></dd>
        <dt>Author:</dt>
        <dd>
            <a href="<?php echo $author; ?>" class="creator"><?php echo $author_name; ?></a>
        </dd>
        <dt>License:</dt>
        <dd>
            <a href="<?php echo symbiostock_eula( '' , true ); ?>"
                class="license"><?php echo symbiostock_eula( 
                '' , true ); ?></a>
        </dd>
        <dt>Created:</dt>
        <dd class="created"><?php echo $date[ 0 ] ?></dd>
        <dt>Related:</dt>    
<?php if ( $related_images )
        {
            foreach ( $related_images as $related_image )
            {
?>
<dd>
            <a href="<?php echo get_permalink( $related_image->ID ); ?>"
                class="relation"><?php echo $related_image
                        ->post_title; ?></a>
        </dd>
<?php
            }
        }
?>   
</dl>
<?php

    }

}


if ( is_admin( ) )
{
    //in case user updates ALL posts, we up the time limit so it doesn't crash
    set_time_limit( 0 );

    /**
     * Updates all images on site with current values if needed
     * 
     * This is responsible for applying huge batch edits.
     * Its a sensitive function with lots of conditionals,
     * so be carefull editing it!
     * 
     * @category batch-editing
     * @package symbiostock
     * @subpackage image-processing
     * 
     */
    function symbiostock_update_all_images()
    {
        ini_set( "memory_limit" , "1024M" );

        $meta_values = array( 
                'symbiostock_exclusive' => 'exclusive',
                'symbiostock_live' => 'live',
                'price_bloggee' => 'price_bloggee',
                'price_small' => 'price_small',
                'price_medium' => 'price_medium',
                'price_large' => 'price_large',
                'price_vector' => 'price_vector',
                'price_zip' => 'price_zip',
				'symbiostock_title_seo_text' => 'symbiostock_title_seo_text',
                'symbiostock_discount' => 'discount_percent',
                'symbiostock_rank' => 'symbiostock_rank',
                'symbiostock_rating' => 'symbiostock_rating',
                'symbiostock_model_released' => 'symbiostock_model_released',
                'symbiostock_property_released' => 'symbiostock_property_released',
                'symbiostock_bloggee_available' => 'symbiostock_bloggee_available',
                'symbiostock_small_available' => 'symbiostock_small_available',
                'symbiostock_medium_available' => 'symbiostock_medium_available',
                'symbiostock_large_available' => 'symbiostock_large_available',
                'symbiostock_vector_available' => 'symbiostock_vector_available',
                'symbiostock_zip_available' => 'symbiostock_zip_available',
                'symbiostock_referral_label_1' => 'symbiostock_referral_label_1',
                'symbiostock_referral_label_2' => 'symbiostock_referral_label_2',
                'symbiostock_referral_label_3' => 'symbiostock_referral_label_3',
                'symbiostock_referral_label_4' => 'symbiostock_referral_label_4',
                'symbiostock_referral_label_5' => 'symbiostock_referral_label_5',
                'symbiostock_referral_link_1' => 'symbiostock_referral_link_1',
                'symbiostock_referral_link_2' => 'symbiostock_referral_link_2',
                'symbiostock_referral_link_3' => 'symbiostock_referral_link_3',
                'symbiostock_referral_link_4' => 'symbiostock_referral_link_4',
                'symbiostock_referral_link_5' => 'symbiostock_referral_link_5' );
		
		$meta_values = apply_filters('ss_batch_meta_values', $meta_values);
		
	
        $args = array( 
                'post_type' => 'image',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'caller_get_posts' => 1,
                'fields' => 'ids' );

        //we announce this loop is running, in case other functions wish to know
        $ss_mass_edit = true;
        global $ss_mass_edit;

        $args = apply_filters( 'ss_bulk_edit_args' , $args );

        $all_images = null;
        $all_images = new WP_Query( $args );

        $count = 0;
        $total_count = 0;

        if ( $all_images->have_posts( ) )
        {
            while ( $all_images->have_posts( ) ) :
                $all_images->the_post( );

                $id = get_the_ID( );

                do_action( 'ss_bulk_edit_in_loop' , $id );

                if ( isset( $_POST[ 'symbiostock_update_images' ] ) )
                {

                    $image_post = array();
                    $image_post[ 'ID' ] = $id;
                    $image_post[ 'comment_status' ] = $_POST[ 'symbiostock_comments' ];

                    wp_update_post( $image_post );
					
                    $edit = get_post_meta( $id , 'locked' , 'not_locked' );

                    if ( $edit == 'not_locked' )
                    {

                        //The following is a correction on the batch editor, properly adjusting image sizes if they are blank
                        $meta = get_post_meta($id, 'size_info');
                        $meta_array = maybe_unserialize($meta[0]);
                        
                        $meta_array['bloggee']['width'] >= $meta_array['bloggee']['height'] ? $current_size_bloggee = $meta_array['bloggee']['width'] : $current_size_bloggee = $meta_array['bloggee']['height'];
                        $meta_array['small']['width']   >= $meta_array['small']['height'] ? $current_size_small = $meta_array['small']['width'] : $current_size_small =  $meta_array['small']['height'];
                        $meta_array['medium']['width']  >= $meta_array['medium']['height'] ? $current_size_medium = $meta_array['medium']['width'] : $current_size_medium = $meta_array['medium']['height'];

                        if(!isset($_POST[ 'symbiostock_bloggee_size' ])){
                            $size_bloggee = $current_size_bloggee;
                        } else {
                            $size_bloggee = trim($_POST[ 'symbiostock_bloggee_size' ]);
                        }

                        if(!isset($_POST[ 'symbiostock_small_size' ])){
                            $size_small = $current_size_small;
                        } else {
                            $size_small = trim($_POST[ 'symbiostock_small_size' ]);
                        }

                        if(!isset($_POST[ 'symbiostock_medium_size' ])){
                            $size_medium = $current_size_medium;
                        } else {
                            $size_medium = trim($_POST[ 'symbiostock_medium_size' ]);
                        }
                        
                        $size_info = symbiostock_change_image_sizes( 
                                $id ,
                                $size_bloggee ,
                                $size_small ,
                                $size_medium );

                        update_post_meta( $id , 'size_info' , $size_info );

                        foreach ( $meta_values as $key => $meta_value )
                        {

                            $option = $_POST[ $key ];

                            //echo $meta_value . ': ' . $option . '<br />';
							
                                                        
                            if ( !empty( $option ) )
                            {	 
                                $success = update_post_meta( $id , $meta_value , $option );
                            }
                        }

                    }

                }
                $count++;
                $total_count++;
                if ( $count == 100 )
                {

                    $subject = __('Image Process Update: ', 'symbiostock')  . $total_count
                            . __( ' Completed.', 'symbiostock' );
                    
                    $message = __( 'Image process update: ', 'symbiostock') . $total_count
                            . __( ' image pages updated on ', 'symbiostock') . home_url( );

                    wp_mail( get_option( 'admin_email' ) , $subject , $message );

                    $count = 0;

                }
                //update post
                wp_cache_flush( );
            endwhile;

            $complete = __( 'Operation complete!', 'symbiostock') . $total_count
                    . __( 'images updated.', 'symbiostock');

            wp_mail( get_option( 'admin_email' ) , $complete , $complete );
        }
    }


    /**
     * 
     */
    function symbiostock_settings_and_pricing()
    {

        //exclusivity
        if ( isset( $_POST[ 'symbiostock_exclusive' ] )
                && isset( $_POST[ 'symbiostock_save_defaults' ] ) )
            update_option( 'symbiostock_exclusive' ,
                    $_POST[ 'symbiostock_exclusive' ] );

        $exclusive = get_option( 'symbiostock_exclusive' );
        $exclusive == 'not_exclusive' || !isset( $exclusive ) ? $not_exclusive = 'selected="selected"'
                : $not_exclusive = '';
        $exclusive == 'exclusive' ? $exclusive = 'selected="selected"'
                : $exclusive = '';

        $_POST[ 'symbiostock_save_defaults' ] == 1 ? $update = true : $update = false;
		
        
        do_action('ss_settings_and_pricing');
       
        
        //live or not live
        if ( isset( $_POST[ 'symbiostock_live' ] ) && $update == true )
            update_option( 'symbiostock_live' , $_POST[ 'symbiostock_live' ] );

        $live = get_option( 'symbiostock_live' );
        $live == 'not_live' ? $not_live = 'selected="selected"' : $not_live = '';
        $live == 'live' || !isset( $live ) ? $live = 'selected="selected"'
                : $live = '';

        if ( isset( $_POST[ 'price_bloggee' ] ) && $update == true )
            update_option( 'price_bloggee' , $_POST[ 'price_bloggee' ] );

        if ( isset( $_POST[ 'price_small' ] ) && $update == true )
            update_option( 'price_small' , $_POST[ 'price_small' ] );

        if ( isset( $_POST[ 'price_medium' ] ) && $update == true )
            update_option( 'price_medium' , $_POST[ 'price_medium' ] );

        if ( isset( $_POST[ 'price_large' ] ) && $update == true )
            update_option( 'price_large' , $_POST[ 'price_large' ] );

        if ( isset( $_POST[ 'price_vector' ] ) && $update == true )
            update_option( 'price_vector' , $_POST[ 'price_vector' ] );

        if ( isset( $_POST[ 'price_zip' ] ) && $update == true )
            update_option( 'price_zip' , $_POST[ 'price_zip' ] );

        if ( isset( $_POST[ 'symbiostock_bloggee_available' ] )
                && $update == true )
            update_option( 'symbiostock_bloggee_available' ,
                    $_POST[ 'symbiostock_bloggee_available' ] );

        if ( isset( $_POST[ 'symbiostock_small_available' ] )
                && $update == true )
            update_option( 'symbiostock_small_available' ,
                    $_POST[ 'symbiostock_small_available' ] );

        if ( isset( $_POST[ 'symbiostock_medium_available' ] )
                && $update == true )
            update_option( 'symbiostock_medium_available' ,
                    $_POST[ 'symbiostock_medium_available' ] );

        if ( isset( $_POST[ 'symbiostock_large_available' ] )
                && $update == true )
            update_option( 'symbiostock_large_available' ,
                    $_POST[ 'symbiostock_large_available' ] );

        if ( isset( $_POST[ 'symbiostock_vector_available' ] )
                && $update == true )
            update_option( 'symbiostock_vector_available' ,
                    $_POST[ 'symbiostock_vector_available' ] );

        if ( isset( $_POST[ 'symbiostock_zip_available' ] ) && $update == true )
            update_option( 'symbiostock_zip_available' ,
                    $_POST[ 'symbiostock_zip_available' ] );

        if ( isset( $_POST[ 'symbiostock_discount' ] ) && $update == true )
            update_option( 'symbiostock_discount' ,
                    $_POST[ 'symbiostock_discount' ] );

        
        //SET SIZES
        if ( isset( $_POST[ 'symbiostock_bloggee_size' ] ) && $update == true )
            update_option( 'symbiostock_bloggee_size' , $_POST[ 'symbiostock_bloggee_size' ] );

        if ( isset( $_POST[ 'symbiostock_small_size' ] ) && $update == true )
            update_option( 'symbiostock_small_size' , $_POST[ 'symbiostock_small_size' ] );

        if ( isset( $_POST[ 'symbiostock_medium_size' ] ) && $update == true )
            update_option( 'symbiostock_medium_size' , $_POST[ 'symbiostock_medium_size' ] );

        //SEO title 
        if ( isset( $_POST[ 'symbiostock_title_seo_text' ] ) && $update == true )
            update_option( 'symbiostock_title_seo_text' , $_POST[ 'symbiostock_title_seo_text' ] );

        //Rank
        if ( isset( $_POST[ 'symbiostock_rank' ] ) && $update == true )
            update_option( 'symbiostock_rank' , $_POST[ 'symbiostock_rank' ] );

        $rank = get_option( 'symbiostock_rank' , '2' );
        $rank == '1' ? $rank_1 = 'selected="selected"' : $rank_1 = '';
        $rank == '2' ? $rank_2 = 'selected="selected"' : $rank_2 = '';
        $rank == '3' ? $rank_3 = 'selected="selected"' : $rank_3 = '';

        //Rating
        if ( isset( $_POST[ 'symbiostock_rating' ] ) && $update == true )
            update_option( 'symbiostock_rating' ,
                    $_POST[ 'symbiostock_rating' ] );

        $rating = get_option( 'symbiostock_rating' , '0' );
        $rating == '0' ? $rating_0 = 'selected="selected"' : $rating_0 = '';
        $rating == '1' ? $rating_1 = 'selected="selected"' : $rating_1 = '';
        $rating == '2' ? $rating_2 = 'selected="selected"' : $rating_2 = '';
        $rating == '3' ? $rating_3 = 'selected="selected"' : $rating_3 = '';

        //comments on or off on images
        if ( isset( $_POST[ 'symbiostock_comments' ] ) && $update == true )
            update_option( 'symbiostock_comments' ,
                    $_POST[ 'symbiostock_comments' ] );

        $symbiostock_comment_status = get_option( 'symbiostock_comments' );
        $symbiostock_comment_status == __( 'open', 'symbiostock')
                || !isset( $symbiostock_comment_selected ) ? $symbiostock_comment_selected = 'selected="selected"'
                : $symbiostock_comment_selected = '';
        $symbiostock_comment_status == __( 'closed', 'symbiostock') ? $symbiostock_comment_not_selected = 'selected="selected"'
                : $symbiostock_comment_not_selected = '';
        //reflections on or off on minipics
        if ( isset( $_POST[ 'symbiostock_reflections' ] ) && $update == true )
            update_option( 'symbiostock_reflections' ,
                    $_POST[ 'symbiostock_reflections' ] );

        $symbiostock_reflections = get_option( 'symbiostock_reflections' );
        $symbiostock_reflections == __( 'on', 'symbiostock') || !isset( $symbiostock_reflections ) ? $symbiostock_reflections_on = 'selected="selected"'
                : $symbiostock_reflections_on = '';
        $symbiostock_reflections == __( 'off', 'symbiostock') ? $symbiostock_reflections_off = 'selected="selected"'
                : $symbiostock_reflections_off = '';
        //model release Yes / No / N/A
        if ( isset( $_POST[ 'symbiostock_model_released' ] ) && $update == true )
            update_option( 'symbiostock_model_released' ,
                    $_POST[ 'symbiostock_model_released' ] );

        $symbiostock_model_release = get_option( 
                'symbiostock_model_released' , __( 'N/A', 'symbiostock'));
        $symbiostock_model_release == __( 'Yes', 'symbiostock')
                || !isset( $symbiostock_model_release ) ? $symbiostock_model_released_yes = 'selected="selected"'
                : $symbiostock_model_released_yes = '';
        $symbiostock_model_release == __( 'No', 'symbiostock') ? $symbiostock_model_released_no = 'selected="selected"'
                : $symbiostock_model_released_no = '';
        $symbiostock_model_release == __( 'N/A', 'symbiostock') ? $symbiostock_model_released_na = 'selected="selected"'
                : $symbiostock_model_released_na = '';
        //property release Yes / No / N/A
        if ( isset( $_POST[ 'symbiostock_property_released' ] )
                && $update == true )
            update_option( 'symbiostock_property_released' ,
                    $_POST[ 'symbiostock_property_released' ] );

        $symbiostock_property_release = get_option( 
                'symbiostock_property_released' , __( 'N/A', 'symbiostock') );
        $symbiostock_property_release == __( 'Yes', 'symbiostock')
                || !isset( $symbiostock_property_released_yes ) ? $symbiostock_property_released_yes = 'selected="selected"'
                : $symbiostock_property_released_yes = '';
        $symbiostock_property_release == __( 'No', 'symbiostock') ? $symbiostock_property_released_no = 'selected="selected"'
                : $symbiostock_property_released_no = '';
        $symbiostock_property_release == __( 'N/A', 'symbiostock') ? $symbiostock_property_released_na = 'selected="selected"'
                : $symbiostock_property_released_na = '';

        $symbiostock_bloggee_available = get_option( 
                'symbiostock_bloggee_available' , __( 'yes', 'symbiostock') );
        $symbiostock_small_available = get_option( 
                'symbiostock_small_available' , __( 'yes', 'symbiostock')  );
        $symbiostock_medium_available = get_option( 
                'symbiostock_medium_available' , __( 'yes', 'symbiostock')  );
        $symbiostock_large_available = get_option( 
                'symbiostock_large_available' , __( 'yes', 'symbiostock')  );
        $symbiostock_vector_available = get_option( 
                'symbiostock_vector_available' , __( 'yes', 'symbiostock')  );
        $symbiostock_zip_available = get_option( 'symbiostock_zip_available' ,
                'yes' );
        $symbiostock_medium_size = get_option( 'symbiostock_medium_size' , 1000 );
        $symbiostock_small_size = get_option( 'symbiostock_small_size' , 500 );
        $symbiostock_bloggee_size = get_option( 'symbiostock_bloggee_size' ,
                250 );

        $referral_count = 1;
        while ( $referral_count <= 5 )
        {

            if ( isset( $_POST[ 'symbiostock_referral_link_' . $referral_count ] )
                    && $update == true )
            {
                update_option( 
                        'symbiostock_referral_link_' . $referral_count ,
                        $_POST[ 'symbiostock_referral_link_' . $referral_count ] );
                update_option( 
                        'symbiostock_referral_label_' . $referral_count ,
                        $_POST[ 'symbiostock_referral_label_' . $referral_count ] );
            }
            $referral_count++;
        }
?>
    <table class="symbiostock-author-settings widefat wp-list-table">
        <thead>
            <tr>
                <th colspan-=2><strong>&raquo; <?php _e('Image Status', 'symbiostock') ?></strong></th>
            </tr>
        </thead>
        <tr>
            <?php do_action( 'ss_bulk_edit_before' ); ?>
            <th scope="row"><?php _e('Exclusive', 'symbiostock') ?></th>
            <td><select id="symbiostock_exclusive" name="symbiostock_exclusive">
                    <option <?php echo $not_exclusive; ?> value="not_exclusive">Not
                        Exclusive</option>
                    <option <?php echo $exclusive; ?> value="exclusive"><?php _e('Exclusive', 'symbiostock') ?></option>
            </select></td>
        </tr>
        <tr>
            <th scope="row">Live<br /><?php echo sshelp( 'live' , __('Live', 'symbiostock')  ); ?></th>
            <td><select id="symbiostock_live" name="symbiostock_live">
                    <option <?php echo $live; ?> value="live"><?php _e('Live', symbiostock) ?></option>
                    <option <?php echo $not_live; ?> value="not_live"><?php _e('not_Live', symbiostock) ?></option>
            </select></td>
        </tr>
        <!--rank rating-->
        <tr>
            <th scope="row"><?php _e('Rank', 'symbiostock') ?><br /><?php echo sshelp( 'rank' , __('Rank', 'symbiostock') ); ?></th>
            <td><select id="symbiostock_rank" name="symbiostock_rank">
                    <option <?php echo $rank_1; ?> value="1"><?php _e('1st', 'symbiostock') ?></option>
                    <option <?php echo $rank_2; ?> value="2"><?php _e('2nd', 'symbiostock') ?></option>
                    <option <?php echo $rank_3; ?> value="3"><?php _e('3rd', 'symbiostock') ?></option>
            </select> <br />
                <p class="description"><?php _e('Relative ranking system, putting premium at
                    front of search results, second in the middle, third last.', 'symbiostock') ?></p></td>
        </tr>
        <tr>
            <th scope="row"><?php _e('Rating', 'symbiostock') ?><br /><?php echo sshelp( 'rating' , __('Rating', 'symbiostock') ); ?></th>
            <td><select id="symbiostock_rating" name="symbiostock_rating">
                    <option <?php echo $rating_0; ?> value="0">-</option>
                    <option <?php echo $rating_1; ?> value="1"><?php _e('GREEN', 'symbiostock') ?></option>
                    <option <?php echo $rating_2; ?> value="2"><?php _e('YELLOW', 'symbiostock') ?></option>
                    <option <?php echo $rating_3; ?> value="3"><?php _e('RED', 'symbiostock') ?></option>
            </select>
                <p class="description"><?php _e('Nudity filter. See info link for definitions.', 'symbiostock') ?></p>
            </td>
        </tr>
        <!--pricing and options-->
        <thead>
            <tr>
                <th colspan-=2><strong>&raquo; <?php _e('Pricing and Options.', 'symbiostock') ?><?php echo sshelp( 
                'default_pricing' , __('Default Pricing', 'symbiostock') ); ?></strong><br />
                    <?php _e( ' *See "Settings" to change type.', 'symbiostock') ?></th>
            </tr>
        </thead>
        <tr>
            <th scope="row"><strong><?php _e( 'Vector', 'symbiostock') ?></strong></th>
            <td><input type="text" name="price_vector" id="price_vector"
                value="<?php echo get_option( 
                'price_vector' , '20.00' ); ?>" />
                <?php symbiostock_size_available( 'vector' ,
                $symbiostock_vector_available ) ?>
            </td>
        </tr>
        <tr>
            <th scope="row"><strong><?php _e( 'Zip', 'symbiostock') ?></strong> (<?php _e( 'Packaged alternate files.', 'symbiostock') ?>)</th>
            <td><input type="text" name="price_zip" id="price_zip"
                value="<?php echo get_option( 
                'price_zip' , '30.00' ); ?>" />
                <?php symbiostock_size_available( 'zip' ,
                $symbiostock_zip_available ) ?>
            </td>
        </tr>
        <tr>
            <th scope="row"><strong><?php _e( 'Large', 'symbiostock') ?></strong></th>
            <td><input type="text" name="price_large" id="price_large"
                value="<?php echo get_option( 
                'price_large' , '20.00' ); ?>" />
                <?php symbiostock_size_available( 'large' ,
                $symbiostock_large_available ) ?>
            </td>
        </tr>
        <tr>
            <th scope="row"><strong><?php _e( 'Medium', 'symbiostock') ?></strong></th>
            <td><input type="text" name="price_medium" id="price_medium"
                value="<?php echo get_option( 
                'price_medium' , '10.00' ); ?>" />
                <?php symbiostock_size_available( 'medium' ,
                $symbiostock_medium_available ) ?>
            </td>
        </tr>
        <tr>
            <th scope="row"><strong><?php _e( 'Small', 'symbiostock') ?></strong></th>
            <td><input type="text" name="price_small" id="price_small"
                value="<?php echo get_option( 
                'price_small' , '5.00' ); ?>" />
                <?php symbiostock_size_available( 'small' ,
                $symbiostock_small_available ) ?>
            </td>
        </tr>
        <tr>
            <th scope="row"><strong><?php _e( 'Bloggee', 'symbiostock') ?></strong></th>
            <td><input type="text" name="price_bloggee" id="price_bloggee"
                value="<?php echo get_option( 
                'price_bloggee' , '2.50' ); ?>" />
                <?php symbiostock_size_available( 'bloggee' ,
                $symbiostock_bloggee_available ) ?>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e( 'Discount', 'symbiostock') ?> %</th>
            <td><input type="text" name="symbiostock_discount"
                id="symbiostock_discount"
                value="<?php echo get_option( 
                'symbiostock_discount' , '0' ); ?>" /> <?php _e( 'Enter', 'symbiostock') ?> "<strong>00</strong>"
                <?php _e( ' to reset to ', 'symbiostock') ?> 0.</td>
        </tr>
        <thead>
            <tr>
                <th colspan-=2><strong>&raquo; <?php _e( 'Default Size Settings', 'symbiostock') ?></strong> <br /><?php echo sshelp( 'default_size_settings' ,
                __('Default Size Settings', 'symbiostock') ); ?>
                </th>
            </tr>
        </thead>
        <tr>
            <th scope="row"><strong><?php _e( 'Medium', 'symbiostock') ?></strong></th>
            <td><input type="text" name="symbiostock_medium_size"
                id="symbiostock_medium_size"
                value="<?php echo $symbiostock_medium_size ?>" /></td>
        </tr>
        <tr>
            <th scope="row"><strong><?php _e( 'Small', 'symbiostock') ?></strong></th>
            <td><input type="text" name="symbiostock_small_size"
                id="symbiostock_small_size"
                value="<?php echo $symbiostock_small_size ?>" /></td>
        </tr>
        <tr>
            <th scope="row"><strong><?php _e( 'Bloggee', 'symbiostock') ?></strong></th>
            <td><input type="text" name="symbiostock_bloggee_size"
                id="symbiostock_bloggee_size"
                value="<?php echo $symbiostock_bloggee_size ?>" /></td>
        </tr>
        <thead>
            <tr>
                <th colspan-=2><strong>&raquo; <?php __('Image SEO -- *Entering "-Royalty Free Image" will append that phrase to all image titles.', 'symbiostock') ?></th>
            </tr>
        </thead>
        <tr>
            <th scope="row"><strong><?php _e( 'Append Text to Title', 'symbiostock') ?>:</strong></th>
            <td><input type="text" name="symbiostock_title_seo_text"
                id="symbiostock_title_seo_text"
                value="<?php echo get_option( 
                'symbiostock_title_seo_text' , '' ); ?>" /><br />
            <br /></td>
        </tr>
        <tr>
            <th scope="row"><strong><?php _e( 'Image Comments', 'symbiostock') ?></strong> <br /> <?php _e( 'Applied during processing. Must manually change afterward.', 'symbiostock') ?></th>
            <td><select id="symbiostock_comments" name="symbiostock_comments">
                    <option <?php echo $symbiostock_comment_selected; ?> value="open"><?php _e( 'Allowed', 'symbiostock') ?></option>
                    <option <?php echo $symbiostock_comment_not_selected; ?>
                        value="closed"><?php _e( 'Disabled', 'symbiostock') ?></option>
            </select></td>
        </tr>
        <thead>
            <tr>
                <th colspan-=2><strong>&raquo; <?php _e( 'Legal', 'symbiostock') ?></strong></th>
            </tr>
        </thead>
        <tr>
            <th scope="row"><strong><?php _e( 'Model Released?', 'symbiostock') ?></strong> <br /></th>
            <td><select id="symbiostock_model_released"
                name="symbiostock_model_released">
                    <option <?php echo $symbiostock_model_released_yes; ?> value="<?php _e( 'Yes', 'symbiostock') ?>"><?php _e( 'Yes', 'symbiostock') ?></option>
                    <option <?php echo $symbiostock_model_released_no; ?> value="<?php _e( 'No', 'symbiostock') ?>"><?php _e( 'No', 'symbiostock') ?></option>
                    <option <?php echo $symbiostock_model_released_na; ?> value="<?php _e( 'N/A', 'symbiostock') ?>"><?php _e( 'N/A', 'symbiostock') ?></option>
            </select></td>
        </tr>
        <tr>
            <th scope="row"><strong><?php _e( 'Property Released?', 'symbiostock') ?></strong> <br /></th>
            <td><select id="symbiostock_property_released"
                name="symbiostock_property_released">
                    <option <?php echo $symbiostock_property_released_yes; ?>
                        value="<?php _e( 'Yes', 'symbiostock') ?>"><?php _e( 'Yes', 'symbiostock') ?></option>
                    <option <?php echo $symbiostock_property_released_no; ?> value="<?php _e( 'No', 'symbiostock') ?>"><?php _e( 'No', 'symbiostock') ?></option>
                    <option <?php echo $symbiostock_property_released_na; ?>
                        value="<?php _e( 'N/A', 'symbiostock') ?>"><?php _e( 'N/A', 'symbiostock') ?></option>
            </select></td>
        </tr>
        <thead>
            <tr>
                <th colspan="2"><strong>&raquo; <?php _e( 'Referral Links', 'symbiostock') ?></strong></th>
            </tr>
        </thead>
        <tr>
            <td><label for="symbiostock_referral_link_1" scope="row"><strong><?php _e( 'Referral Link', 'symbiostock') ?> #1:</strong></label> <input class="longfield" type="text"
                name="symbiostock_referral_link_1" id="symbiostock_referral_link_1"
                value="<?php echo get_option( 
                'symbiostock_referral_link_1' , '' ); ?>" /></td>
            <td><label for="symbiostock_referral_label_1" scope="row"><?php _e( 'Label', 'symbiostock') ?>:</label>
                <input class="longfield" type="text"
                name="symbiostock_referral_label_1"
                id="symbiostock_referral_label_1"
                value="<?php echo get_option( 
                'symbiostock_referral_label_1' , '' ); ?>" /></td>
        </tr>
        <tr>
            <td><label scope="row"><strong><?php _e( 'Referral Link', 'symbiostock') ?> #2</strong></label> <input
                class="longfield" type="text" name="symbiostock_referral_link_2"
                id="symbiostock_referral_link_2"
                value="<?php echo get_option( 
                'symbiostock_referral_link_2' , '' ); ?>" /></td>
            <td><label scope="row"><?php _e( 'Label', 'symbiostock') ?>:</label> <input class="longfield"
                type="text" name="symbiostock_referral_label_2"
                id="symbiostock_referral_label_2"
                value="<?php echo get_option( 
                'symbiostock_referral_label_2' , '' ); ?>" /></td>
        </tr>
        <tr>
            <td><label scope="row"><strong><?php _e( 'Referral Link', 'symbiostock') ?> #3</strong></label> <input
                class="longfield" type="text" name="symbiostock_referral_link_3"
                id="symbiostock_referral_link_3"
                value="<?php echo get_option( 
                'symbiostock_referral_link_3' , '' ); ?>" /></td>
            <td><label scope="row"><?php _e( 'Label', 'symbiostock') ?>:</label> <input class="longfield"
                type="text" name="symbiostock_referral_label_3"
                id="symbiostock_referral_label_3"
                value="<?php echo get_option( 
                'symbiostock_referral_label_3' , '' ); ?>" /></td>
        </tr>
        <tr>
            <td><label scope="row"><strong><?php _e( 'Referral Link', 'symbiostock') ?> #4</strong></label> <input
                class="longfield" type="text" name="symbiostock_referral_link_4"
                id="symbiostock_referral_link_4"
                value="<?php echo get_option( 
                'symbiostock_referral_link_4' , '' ); ?>" /></td>
            <td><label scope="row"><?php _e( 'Label', 'symbiostock') ?>:</label> <input class="longfield"
                type="text" name="symbiostock_referral_label_4"
                id="symbiostock_referral_label_4"
                value="<?php echo get_option( 
                'symbiostock_referral_label_4' , '' ); ?>" /></td>
        </tr>
        <tr>
            <td><label scope="row"><strong><?php _e( 'Referral Link', 'symbiostock') ?> #5</strong></label> <input
                class="longfield" type="text" name="symbiostock_referral_link_5"
                id="symbiostock_referral_link_5"
                value="<?php echo get_option( 
                'symbiostock_referral_link_5' , '' ); ?>" /></td>
            <td><label scope="row"><?php _e( 'Label', 'symbiostock') ?>:</label> <input class="longfield"
                type="text" name="symbiostock_referral_label_5"
                id="symbiostock_referral_label_5"
                value="<?php echo get_option( 
                'symbiostock_referral_label_5' , '' ); ?>" /></td>
        </tr>
        <?php do_action( 'ss_bulk_edit_after' ); ?>
    </table>
    <br />
<br />
    <?php

    }
}

/**
 * Set up the theme auto-updater
 */
require_once( 'wp-updates-theme.php' );
new WPUpdatesThemeUpdater_409( 'http://wp-updates.com/api/2/theme', basename(get_template_directory()));

/**
 * Get marketing functions
 */
require_once( symbiostock_MARKETROOT . 'marketer_functions.php' );

/**
 * To show Blog Search option or not.
 * 
 * Works with the them Customizer. Determines whether or not
 * to show blog search. Most webmasters would leave it out.
 * 
 * @package symbiostock.
 * @subpackage html-functions
 * 
 */
function ss_image_blog_form_option(){
    $menu_option = get_theme_mod( 'show_blog_search' );
    
    if($menu_option == 1){
        ?>
            <select class="form-control" id="select_type"
    name="post_type">
        <option value="image"><?php _e( 'Images', 'symbiostock') ?></option>
        <option value="post"><?php _e( 'Blog', 'symbiostock') ?></option>
</select>
        <?php 
    } else {
        ?>
            <input type="hidden" value="image" name="post_type" />
        <?php 
    }    
}

/**
 * Determines whether or not to use customizer for site's look.
 * 
 * Some webmasters would wish to leave Customizer options off, 
 * giving them more freedom in Style/CSS. This is controlled in the 
 * BEE->SETTINGS tab in the admin area. 
 * 
 */
function ss_use_customizer(){

    if(isset($_POST['ss_use_customizer'])){

        update_option('ss_use_customizer', $_POST['ss_use_customizer']);

    }

    $update_settings = get_option('ss_use_customizer', 1);

    if($update_settings == 1){
        $ss_use_customizer_yes = 'checked';
        $ss_use_customizer_no = '';
    } else {
        $ss_use_customizer_yes = '';
        $ss_use_customizer_no = 'checked';
    }

    ?>
    <tr>
        <td colspan="2"><strong><?php _e( 'Symbiostock Customizer', 'symbiostock') ?></strong> <span
            class="description"><?php _e( 'Keep OFF if you have specific child theme setups    that you don\'t want overridden by the customizer.', 'symbiostock') ?></span><br /> <label
            for="ss_use_customizer_1"> <input type="radio"
                id="ss_use_customizer_1" name="ss_use_customizer"
                <?php echo $ss_use_customizer_yes; ?> value="1" /> <?php _e( 'On', 'symbiostock') ?>
        </label> <label for="ss_use_customizer_2"> <input type="radio"
                id="ss_use_customizer_2" name="ss_use_customizer"
                <?php echo $ss_use_customizer_no ; ?> value="0" /> <?php _e( 'Off', 'symbiostock') ?>
        </label></td>
    </tr>
    <?php 

}
add_action( 'ss_settings_table_top' , 'ss_use_customizer', 7 );

/*
 * Allows daily chron jobs to run.
 *
 */

/**
 * Determines if daily chron jobs should run.
 * 
 * There are functions that update your site's csv and xml
 * files daily. They tend to be memory intensive, and some webmasters
 * would prefer to keep this turned off. This is controlled in the 
 * BEE->SETTINGS tab in the admin area. 
 * 
 */
function ss_daily_update_settings(){

    if(isset($_POST['symbiostock_update_settings'])){

        update_option('symbiostock_update_settings', $_POST['symbiostock_update_settings']);

    }

    $update_settings = get_option('symbiostock_update_settings', 1);

    if($update_settings == 1){
        $symbiostock_update_settings_yes = 'checked';
        $symbiostock_update_settings_no = '';
    } else {
        $symbiostock_update_settings_yes = '';
        $symbiostock_update_settings_no = 'checked';
    }

    ?>
    <tr>
        <td colspan="2"><?php _e( 'Run daily image update? (for network    and connected search engines)', 'symbiostock') ?> <br /> <label
            for="symbiostock_update_settings_1"> <input type="radio"
                id="symbiostock_update_settings_1"
                name="symbiostock_update_settings"
                <?php echo $symbiostock_update_settings_yes; ?> value="1" /> <?php _e( 'Yes', 'symbiostock') ?>
        </label> <label for="symbiostock_update_settings_2"> <input
                type="radio" id="symbiostock_update_settings_2"
                name="symbiostock_update_settings"
                <?php echo $symbiostock_update_settings_no ; ?> value="0" /> <?php _e( 'No', 'symbiostock') ?>
        </label></td>
    </tr>
    <?php 

}
add_action( 'ss_settings_table_top' , 'ss_daily_update_settings', 6 );

/*
 * Sets name of download button.
 * 
 * Some webmasters want control over the "cart" or "download"
 * button found under the product table. This is controlled in the 
 * BEE->SETTINGS tab in the admin area. 
 */

function ss_name_download_button(){

    if(isset($_POST['symbiostock_download_button_name'])){

        update_option('symbiostock_download_button_name', trim($_POST['symbiostock_download_button_name']));

    }
    $name = get_option('symbiostock_download_button_name', __('DOWNLOAD', 'symbiostock'));
    ?>
    <tr>
        <td colspan="2"><label for="symbiostock_download_button_name"> <strong><?php _e( 'Product table "Cart" Button Name', 'symbiostock') ?></strong><br /> <input type="text"
                id="symbiostock_download_button_name"
                name="symbiostock_download_button_name" value="<?php echo $name ?>" />
        </label></td>
    </tr>
    <?php 
}
add_action( 'ss_settings_table_top' , 'ss_name_download_button', 8 );

/**
 * Allows the turning off and on of public network analytics. 
 * 
 * This is a very important feature. See symbiostock help file. This is controlled in the 
 * BEE->SETTINGS tab in the admin area. 
 */
function ss_public_analytics(){

	if(isset($_POST['symbiostock_public_analytics'])){

		update_option('symbiostock_public_analytics', $_POST['symbiostock_public_analytics']);

	}

	$public_analytics = get_option('symbiostock_public_analytics', 1);

	if($public_analytics == 1){
		$symbiostock_public_analytics_yes = 'checked';
		$symbiostock_public_analytics_no = '';
	} else {
		$symbiostock_public_analytics_yes = '';
		$symbiostock_public_analytics_no = 'checked';
	}

	?>
    <tr>
        <td colspan="2"><?php _e( 'Allow Public Analytics?', 'symbiostock') ?> <?php echo sshelp('public_analytics', __( 'About Network Analytics (IMPORTANT)', 'symbiostock')); ?> <br /> <label
            for="symbiostock_public_analytics_1"> <input type="radio"
                id="symbiostock_public_analytics_1"
                name="symbiostock_public_analytics"
                <?php echo $symbiostock_public_analytics_yes; ?> value="1" /> <?php _e( 'Yes', 'symbiostock') ?>
        </label> <label for="symbiostock_public_analytics_2"> <input
                type="radio" id="symbiostock_public_analytics_2"
                name="symbiostock_public_analytics"
                <?php echo $symbiostock_public_analytics_no ; ?> value="0" /> <?php _e( 'No', 'symbiostock') ?>
        </label></td>
    </tr>
    <?php 

}
add_action( 'ss_settings_table_bottom' , 'ss_public_analytics', 6 );



/**
 * Sets the names of sizes to user-defined ones: Bloggee, Small, Medium, etc.
 * 
 * Some webmasters want control over the Size Names
 * This is controlled in the * BEE->SETTINGS tab in the admin area. 
 */

function ss_name_size_buttons(){

    $sizes = array(
            'bloggee' => __('Bloggee', 'Symbiostock'),
            'small'   => __('Small',  'Symbiostock'),
            'medium'  => __('Medium', 'Symbiostock'),
            'large'   => __('Large',  'Symbiostock'),
            'vector'  => __('Vector', 'Symbiostock'),
            'zip'     => __('Zip',    'Symbiostock'),

    );

    if(isset($_POST['save_form_info']) && $_POST['save_form_info'] == 1){

        $vals = array();
    
        foreach($sizes as $size => $name){
            
            if(isset($_POST['ss_name_size_'.$size])){
                $vals[$size] = trim($_POST['ss_name_size_'.$size]);
                
            }
    
        }    

        update_option('symbiostock_size_names', $vals);
    }
    

    ?><tr><td colspan="2"><?php
    
    $size_names = get_option('symbiostock_size_names', $sizes);    
    if(empty($size_names)){
        $size_names = $sizes;
    }
    ?><strong>Customize Size Names</strong><br /><?php 
    
    foreach($size_names  as $size => $name){
                
        ?>
        <label for="ss_name_size_<?php echo $size ?>">
            <input type="text" value="<?php echo $name ?>" id="ss_name_size_<?php echo $size ?>" name="ss_name_size_<?php echo $size ?>"  /> <?php echo $name ?> <br />            
        </label>
        <?php
        
    }
    
    ?></td></tr><?php    
}
add_action( 'ss_settings_table_top' , 'ss_name_size_buttons', 9 );


/**
 * Gets an array of connection symbio-sites.
 * 
 * @package symbiostock
 * @subpackage networking
 * 
 * @return array list of connected sites.:
 */
function symbiostock_info_sitelist(){    
    $sitelist = explode("\n", file_get_contents('http://symbiostock.info/sitelist.php'));
    if(is_array($sitelist))
        return $sitelist;    
}

/**
 * Keyword Analytics
 */ 

require_once ( get_template_directory( ) . '/inc/classes/analytics.php' );

/*
 * Get category template
 */
require_once(get_template_directory( ) . '/inc/list_categories.php');