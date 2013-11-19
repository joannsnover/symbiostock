<?php

function ss_home_page_author_network(){
    
    
        $symbiocard_location = ABSPATH . '/symbiocard.csv';
    
        if(file_exists(ABSPATH . '/symbiocard.csv')){
    
            $symbiocard_location = ABSPATH . '/symbiocard.csv';
        
            $author = new network_manager;
            $info = $author->csv_to_array($symbiocard_location, ',');
            $info = $info[0];
        ?>
        <div class="panel ss-home-author">
            <div class="panel-heading">
            <h2 class="panel-title"><?php echo $info['symbiostock_display_name'] ?></h2>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-7">
                        <p>
                        <?php 
                        if(isset($info['symbiostock_author_bio']))
                            echo stripslashes($info['symbiostock_author_bio']); 
                        ?>
                        </p>
                    </div>
                    <div class="col-md-5">
                    <?php 
                    if(isset($info['symbiostock_personal_photo'])){
                        ?><img class="img-thumbnail pull-right" title="<?php echo $info['symbiostock_display_name'] ?>" src="<?php echo $info['symbiostock_personal_photo'] ?>"><?php 
                    }
                    ?>
                    </div>
                </div>
            </div>        
        </div>
        <?php    
            
        }
    
    
}

class ss_home_page
{    

    public function __construct($id=-1){
        
        if(isset($_POST['ss_update_home_page'])){

            foreach($_POST as $key => $value){
                $this->$key=$value;                
            }
  
            //update post content
            $new_post = array(
                    'ID'           => $this->ss_home_page_select,
                    'post_content' => $this->ss_home_page_content
            );
            wp_update_post($new_post);
            
            //set the page's template
            update_post_meta($this->ss_home_page_select, '_wp_page_template', 'page-home0.php');
            
            //set the page as home page
            update_option( 'page_on_front', $this->ss_home_page_select );
            update_option( 'show_on_front', 'page' );
            
            //set up site and post meta based on values submitted
            update_post_meta($this->ss_home_page_select, '');
            update_post_meta($this->ss_home_page_select, 'home_page_settings', $this);
            update_option('ss_home_page', '');
            update_option('ss_home_page', $this);
          
            
            
        } else {  
            
            //if we are not in the admin area, we know this class is being called from the home page template
            if(!is_admin()){                
                $settings = get_post_meta($id, 'home_page_settings');                
                                
                foreach ($settings[0] as $key => $value){
                    $this->$key=$value;
                }
                
            }
            
            //if we are in admin, we call the settings from the site meta
            if(is_admin()){
                
            $settings = get_option('ss_home_page', 0);                        
            foreach ($settings as $key => $value){                
                $this->$key=$value;                
                }
            }
                        
        }
        
    }
    
}

if(is_admin()):

add_action( 'admin_menu' , 'ss_home_page_menu' );


function ss_home_page_menu()
{

    add_submenu_page( 'themes.php' , 'Home Page' , 'Home Page' ,
            'manage_options' , 'ss-home-page-menu-generator' ,
            'ss_home_page_options' );
}


function ss_home_page_options()
{
    if ( !current_user_can( 'manage_options' ) )
    {
        wp_die( 
                __( 
                        'You do not have sufficient permissions to access this page.' ) );
    }
  
    
    $homepage_options = new ss_home_page( );
    
?>
	<div class="wrap">
    	<h1>Symbiostock Home Page Generator</h1>
    	
    	<form action="" method="post">        
            <h2>Not a web designer?</h2>
            <p>By setting up the options below, you can easily set up an awesome landing (home) page for your site. <br />
            Simply follow the directions below with each step and you will have a good looking standard Symbiostock landing page.            
            </p>
            <p class="description">
            If you are a web designer, you can use any other method you wish to create a home page.
            </p>
            
            <hr />            
                 
            <h2>#1 - Choose your home page</h2>
            <ol>
                <li>
                <p class="description">If you have not done so already, 
                    <a target="_blank" title="Create New Page" href="<?php echo admin_url( 'post-new.php?post_type=page' )?>">
                        create a new BLANK page
                    </a>. Give it a title, and save it.
                    </p>
                </li>
                <li>
                Refresh this page [ctrl R]
                </li> 
                <li>
                Choose:<br />
                <?php 
                $args = array(    
                        'selected'         => $homepage_options->page_id,                      
                        'name'             => 'ss_home_page_select');
                wp_dropdown_pages( $args ) ?>
                 <span class="description">(the page you've just created, or another)</span>
                </li>   
            </ol>  
    
            
            <hr />
            
            <h2>#2 - Create Your Homepage Slider</h2>
            <p>Very important. Do a good job! Choose up to 4 images (too many increases load time). 
            If using Photoshop or similar program, save them at an appropriate "web" quality to reduce filesize and load times.</p>
            
            <ol>
                <li>Create up to <strong>4</strong> or <strong>5</strong> slider images. 1098px x 440px preferably.
                    <br /> See the sites on <a title="slider examples" href="http://www.symbiostock.info" target="_blank">symbiostock.info</a> for how people present their sliders.
                </li>
                <li>
                    <strong><a title="Slider Plugin" href="<?php echo admin_url('themes.php?page=install-required-plugins') ?>">Install Slideshow Plugin</a></strong> &mdash;
                    It comes pre-suggested with Symbiostock and <a title="Slider Plugin" href="<?php echo admin_url('themes.php?page=install-required-plugins') ?>">can be installed here</a>. 
                    <a title="Slider Plugin" href="http://wordpress.org/plugins/slideshow-jquery-image-gallery/">See plugin page here.</a>
                </li>
                <li>
                    <strong><a title="Create Slideshow" target="_blank" href="<?php echo admin_url('post-new.php?post_type=slideshow') ?>">Create your slideshow</a>
                     <span class="description"></strong> &mdash; (Note, this link will not work if plugin is not installed.)
                     If you must modify it later, it can be found here: <a title="Edit Slideshow" target="_blank" href="<?php echo admin_url('post.php?post=264&action=edit') ?>">Edit Slideshow</a></span>
                </li>
                <li>
                    <strong>Find the shortcode tag </strong> &mdash; During/after creating the slideshow, you will see it gives you a shortcode tag. It will look like this: <code>[slideshow_deploy id='123']</code></li>
                <li>
                    <label for="ss_slideshow_short_code">
                        Enter shortcode tag:<br />
                        <input name="ss_slideshow_short_code" type="text" value="<?php echo trim($homepage_options->ss_slideshow_short_code) ?>" /> <span class="description">(copied from step 4)</span>
                    </label>
                </li>
            </ol>
            
            <hr />
            
            <?php 
            $editor_css = '
<style>
.wp-editor-wrap{
    width: 50%;
    min-width: 500px;
}
.cta-title{
    width: 50%;
    min-width: 500px;    
}
</style>';
            ?>
            
            <h2>#3 - Create Main Content</h2>
            <p>Set up your main content for the home page. General intro, about ... first written impressions.
            <span class="description">
            <br />(<strong>Do not</strong> put a slider shortcode in here. The page template handles this itself. Ignore the "Insert Slideshow" button if its showing.)
            </span>
            </p>
            <?php 
            wp_editor( 
                    trim($homepage_options->ss_home_page_content), 'ss_home_page_content', 
                    $settings = array(                            
                            'textarea_name' => 'ss_home_page_content', 
                            'editor_class' => 'ss-home-page-editor',
                            'editor_css' => $editor_css
                            ) ) 
            ?>

            <hr />            
                 
            <h2>#5 - Author Info / Symbiostock (Right of content area)</h2>
            
            <p>This gives a little information about you and the Symbiostock network. This step is easy!</p>
            
            <ol>
                <li>
                Fill out <a target="_blank" title="Your info" href="<?php echo admin_url( 'profile.php' )?>"> your info </a> completely.<br />
                Do an awesome job and sell yourself!
                Do not overlook the <a target="_blank" title="Symbiostock info" href="<?php echo admin_url( 'profile.php#extended_network_info' )?>"> Symbiostock-specific information</a>.        
                </li>
            </ol>
            
            <p class="description">We mention the network here as a symbiotic effort to collect network-wide customers.</p>
            
            <hr />   

            <h2>#6 - Calls to action! (3 Points)</h2>
            
            <p>Its common/standard practice to have a few call-to-action cells located at the bottom of the page. Simply fill them in respectively below. <br />
            They generally make statements of interest concerning your site, link to important parts of your site, or contain pictures. 
            </p>
            
            <?php 
            if(!isset($homepage_options->ss_cta_one_icon) || empty($homepage_options->ss_cta_one_icon)){
                $homepage_options->ss_cta_one_icon = 'icon-star';
            }
            if(!isset($homepage_options->ss_cta_two_icon) || empty($homepage_options->ss_cta_two_icon)){
                $homepage_options->ss_cta_two_icon = 'icon-star';
            }
            if(!isset($homepage_options->ss_cta_three_icon) || empty($homepage_options->ss_cta_three_icon)){
                $homepage_options->ss_cta_three_icon = 'icon-star';
            }                        
            ?>
            
            <!-- cta-one -->
            <div class="input-text-wrap">
            <label for="ss_cta_one_title">
                <h4 class="description">Call to action #1</h4>
                <input value="<?php echo trim($homepage_options->ss_cta_one_title) ?>" class="cta-title" id="ss_cta_one_title" name="ss_cta_one_title" type="text">
            </label>
            
            <!-- cta icon one -->
            <br />
            <label for="ss_cta_one_icon">                
                <input id="ss_cta_one_icon" name="ss_cta_one_icon" type="text" value="<?php echo trim($homepage_options->ss_cta_one_icon) ?>" />
                <a target="_blank" href="http://fortawesome.github.io/Font-Awesome/icons/">icon-insert</a>
            </label>
                        
            </div>
            <br />
            <?php 
            wp_editor( 
                    trim($homepage_options->ss_cta_one), 'ss_cta_one', 
                    $settings = array(                            
                            'textarea_name' => 'ss_cta_one', 
                            'editor_class' => 'ss-home-page-editor',
                            'editor_css' => $editor_css
                            ) ) 
            ?>
            
            <!-- cta-two -->
            <div class="input-text-wrap">
            <label for="ss_cta_two_title">
                <h4 class="description">Call to action #2</h4>
                <input value="<?php echo trim($homepage_options->ss_cta_two_title) ?>"  class="cta-title" id="ss_cta_two_title" name="ss_cta_two_title" type="text">
            </label>
            <!-- cta icon one -->
            <br />
            <label for="ss_cta_two_icon">                
                <input id="ss_cta_two_icon" name="ss_cta_two_icon" type="text" value="<?php echo trim($homepage_options->ss_cta_two_icon) ?>" />
                <a target="_blank" href="http://fortawesome.github.io/Font-Awesome/icons/">icon-insert</a>
            </label>
            
            </div>
            <br />
            <?php 
            wp_editor( 
                    trim($homepage_options->ss_cta_two), 'ss_cta_two', 
                    $settings = array(                            
                            'textarea_name' => 'ss_cta_two', 
                            'editor_class' => 'ss-home-page-editor',
                            'editor_css' => $editor_css
                            ) ) 
            ?>            

             <!-- cta-three -->
            <div class="input-text-wrap">
            <label for="ss_cta_three_title">
                <h4 class="description">Call to action #3</h4>
                <input value="<?php echo trim($homepage_options->ss_cta_three_title) ?>"  class="cta-title" id="ss_cta_three_title" name="ss_cta_three_title" type="text">
            </label>
            
            <!-- cta icon one -->
            <br />
            <label for="ss_cta_three_icon">                
                <input id="ss_cta_three_icon" name="ss_cta_three_icon" type="text" value="<?php echo trim($homepage_options->ss_cta_three_icon) ?>" />
                <a target="_blank" href="http://fortawesome.github.io/Font-Awesome/icons/">icon-insert</a>
            </label>
            </div>
            <br />
            <?php 
            wp_editor( 
                    trim($homepage_options->ss_cta_three), 'ss_cta_three', 
                    $settings = array(                            
                            'textarea_name' => 'ss_cta_three', 
                            'editor_class' => 'ss-home-page-editor',
                            'editor_css' => $editor_css
                            ) ) 
            ?>           
            
            <table class="form-table">
                <tbody>
                    <tr>...</tr>
                    <tr>...</tr>
                </tbody>
            </table>
            <h2>Second Form Section</h2>
            <p>This is the second section for the form.</p>
            <table class="form-table">
                <tbody>
                    <tr>...</tr>
                    <tr>...</tr>
                </tbody>
            </table>
                <p class="submit"><input type="submit" value="Save Changes" class="button-primary" name="ss_update_home_page"></p>
        </form>
    	
	</div>
	<?php

}
endif;
