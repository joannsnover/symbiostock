<?php
/**
 * Generates all symbiostock admin pages simply.
 *
 * This class was created to keep the forms/ajax simple and able to be
 * expanded easily. To add a new tab along with form functionality, simply
 * place a new file into the admin/ directory, and give it:
 * 
 * A: the name you'd like it to have. Two-word names separated by hyphen.
 * B: The tab order signified by the number leading the name. 
 * 
 * Example: 2some-settings.php ... will be called 'Some Settings' and be second on tab list.
 * second on tab list.
 *
 * PHP version 5
 *
 * @package    symbiostock
 * @author     Leo Blanchette <leo@symbiostock.com>
 * @copyright  Leo Blanchette
 * @license    http://www.gnu.org/licenses/gpl-2.0.html
 * @link       http://www.symbiostock.com
 */
/**
 * Below class is responsible for generating the admin pages and fetching
 * the admin files for each tab. There should not be any need to modify 
 * anything below the class since it is standard wordpress setup code
 * for ajax functionality.
 */
class symbiostock_render_admin_panel
{
    public $active_tab = '1network';
    
    public $options = array();
    
    public function __construct()
    {
        $this->get_pages();
        
?>
<div id="symbiostock_admin" class="wrap">
    <?php
        $this->symbiostock_admin_tabs();
        
?>
    
    <form id="symbiostock_admin_form" action="<?php echo $PHP_SELF; ?>" enctype="multipart/form-data" method="post" >
        <div>
        <?php
        
        if ( isset( $_POST[ 'symbiostock_admin_form_submit' ] ) ) {
            echo '<p>You submitted the form</p>';
        } //isset( $_POST[ 'symbiostock_admin_form_submit' ] )
        
        include_once( 'admin/' . $this->active_tab . '.php' );
?>
        <br />
        
        <label for="save_network_values" ><input id="save_network_values" type="checkbox" name="save_network_values" value="" /> <em>Update public network info.</em></label>
        <label for="save_image_info" ><input id="save_image_info" type="checkbox" name="save_image_info" value="" /> <em>Update public image info.</em></label>
        <input type="hidden" name="save_form_info" value="1" />
        <?php
        
        submit_button();
        
        //when saved, update network file
        if(isset($_POST['save_network_values'])){            
            symbiostock_save_network_info();
            echo '<p><em>Network info updated.</em></p>';
            }
        //when saved, update image info file
        if(isset($_POST['save_image_info'])){            
            symbiostock_save_image_list_info();
            echo '<p><em>Image info info updated.</em></p>';
            }    
                
?>
        <img src="<?php
        echo admin_url( 'images/wpspin_light.gif' );
?>" class="waiting" id="symbiostock_admin_form_loader" style="display:none;" />
        </div>
    </form>
</div>
<?php
        
    }
    
    
    public function symbiostock_admin_tabs( $current = '1home' )
    {
        $tabs = $this->options;
        include_once(symbiostock_CLASSROOT . '/paypal.php');            
        echo '<div id="icon-themes" class="icon32"><br></div>';
        
        echo '<h2 class="nav-tab-wrapper">';
        
        //decide which tab to show
        
        if ( isset( $_POST[ 'tab' ] ) ) {
            $current = $_POST[ 'tab' ];
            
        } //isset( $_POST[ 'tab' ] )
        else if ( isset( $_GET[ 'tab' ] ) ) {
            $current = $_GET[ 'tab' ];
            
        } //isset( $_GET[ 'tab' ] )
        else {
            $current = '1home';
        }
        
        //generate the tabs, and choose active tab
        
        if ( file_exists( dirname( __FILE__ ) . '/admin/' . $current . '.php' ) )
            $this->active_tab = $current;
                
        foreach ( $tabs as $tab => $name ) {
            $class = ( $tab == $current ) ? ' nav-tab-active' : '';
            
            echo "<a id='$tab' class='nav-tab$class' href='?page=symbiostock-control-options&tab=$tab'>$name</a>";
            
        } //$tabs as $tab => $name
        
        echo '</h2>';
        
        
    }
    
    //gets pages and builds array for symbiostock_admin_tabs
    
    public function get_pages()
    {
        $tabs = array();
        
        if ( $handle = opendir( dirname( __FILE__ ) . '/admin/' ) ) {
            while ( false !== ( $entry = readdir( $handle ) ) ) {
                if ( $entry != "." && $entry != ".." ) {
                    $entry = explode( '.', $entry );
                    
                    $tab = $entry[ 0 ];
                    
                    $tabs[ $tab ] = $tab_label = ucwords( preg_replace( "/[0-9]/", "", str_replace( '-', ' ', $entry[ 0 ] ) ) );
                    
                } //$entry != "." && $entry != ".."
                
            } //false !== ( $entry = readdir( $handle ) )
            ksort($tabs);
            $this->options = $tabs;
            
        } //$handle = opendir( dirname( __FILE__ ) . '/admin/' )
        
    }
}
/**
 * Do not change anything below here. Its simple wordpress setup stuff
 * for setting up admin page environment with ajax. 
 */
//get styles
function symbiostock_admin_style()
{
   
        wp_register_style( 'symbiostock_admin_css', symbiostock_CSSDIR . '/admin.css', false, '1.0.0' );
        wp_enqueue_style( 'symbiostock_admin_css' );
        
     
}
add_action( 'admin_enqueue_scripts', 'symbiostock_admin_style' );
function symbiostock_render_admin_panel()
{
    $panel = new symbiostock_render_admin_panel();
    
}
add_action( 'admin_menu', 'symbiostock_menu' );
//registers our admin options page
function symbiostock_menu()
{
    global $symbiostock_settings;
    
    $symbiostock_settings = add_menu_page( 'symbiostock Control Panel', 'Symbiostock', 'manage_options', 'symbiostock-control-options', 'symbiostock_render_admin_panel', symbiostock_IMGDIR . '/admin_icon.png', 99 );
}
//gets our javascript for this page only
function symbiostock_load_scripts()
{            
    global $pagenow;
    
    if ( $_GET['page'] != 'symbiostock-control-options' )
        return;
    
    wp_enqueue_script( 'symbiostock_admin', symbiostock_JSDIR . '/admin.js', array(
         'jquery' 
    ) );
    wp_localize_script( 'symbiostock_admin', 'symbiostock_admin_vars', array(
         'symbiostock_nonce' => wp_create_nonce( 'symbiostock-nonce' ) 
    ) );
}
add_action( 'admin_enqueue_scripts', 'symbiostock_load_scripts' );
//------------------ other admin functions, unrelated to main Symbiostock Options Panels ----------------------------------//
//gets our javascript for Image Processor page only
function symbiostock_load_image_processor_scripts()
{ 
    if(isset($_GET[ 'page' ])){
        if ( $_GET[ 'page' ] != 'symbiostock-process-images' )
            return;
    }
    wp_enqueue_script( 'symbiostock_processor_admin', symbiostock_JSDIR . '/admin-image-processor.js', array(
         'jquery' 
    ) );
    wp_localize_script( 'symbiostock_processor_admin', 'symbiostock_processor_vars', array(
         'symbiostock_nonce' => wp_create_nonce( 'symbiostock-nonce' ) 
    ) );
}
add_action( 'admin_enqueue_scripts', 'symbiostock_load_image_processor_scripts' );
//------------------ get ajax ----------------------------------//
include_once('symbiostock_ajax_admin.php');
?>
