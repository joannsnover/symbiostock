<?php
function get_include_contents($filename) {
    if (is_file($filename)) {
        ob_start();
        include $filename;
        return ob_get_clean();
    }
    return false;
}

//get version 
$theme_data = wp_get_theme('symbiostock');
$theme_version = $theme_data->Version;

$purged = get_option('symbiostock_network_purged');

if($purged != '2.2.4'){    
    $count = 0;    
    while($count >= 9){        
        delete_option('symbiostock_network_site_' . $count);
        $count++;        
        }    
    update_option('symbiostock_network_purged', $theme_version);    
    }


//create our RF content folder - 
update_option('symbiostock_credit_links', 'product_page');
$upload_dir = wp_upload_dir();
//make our content directory for wordpress, which holds previews on product pages
if (!file_exists($upload_dir['basedir'] . '/symbiostock_rf_content/')) {
mkdir($upload_dir['basedir'] . '/symbiostock_rf_content/', 0755);
}


//make our directory for downloadable products 
if (!file_exists(ABSPATH . 'symbiostock_rf/')) {
mkdir(ABSPATH . 'symbiostock_rf/', 0700);
}


//make our directory for network management
if (!file_exists(ABSPATH . 'symbiostock_network/')) {
mkdir(ABSPATH . 'symbiostock_network/', 0755);
}


//make our directory for paypal IPN
if (!file_exists(ABSPATH . 'symbiostock_ipn/')) {
mkdir(ABSPATH . 'symbiostock_ipn/', 0755);
}

//set up ipn
if(file_exists(get_theme_root() . '/symbiostock/ipn/index-draft.php')){
    copy(get_theme_root() . '/symbiostock/ipn/index-draft.php', ABSPATH . 'symbiostock_ipn/index.php');
    }
    
    
//set up index file for possible use    
if(file_exists(get_theme_root() . '/symbiostock/ipn/paypal_ipn.php')){
    copy(get_theme_root() . '/symbiostock/ipn/paypal_ipn.php', ABSPATH . 'symbiostock_ipn/paypal_ipn.php');
    }
    
        
//we have to move our download script to the content directory because sometimes its blocked in themes area 
copy(symbiostock_CLASSROOT . 'image-processor/symbiostock_file_download.php', WP_CONTENT_DIR . '/symbiostock_file_download.php');
//make our directory for temp files
if (!file_exists(ABSPATH . 'symbiostock_xml_cache/')) {
mkdir(ABSPATH . 'symbiostock_xml_cache/', 0755);
}

//now make the seeds directory, for collecting network data 
if (!file_exists(ABSPATH . 'symbiostock_network/seeds/')) {
mkdir(ABSPATH . 'symbiostock_network/seeds/', 0755);
}

//make our htaccess, to protect downloadable products
//protect files
$htaccess = ABSPATH . 'symbiostock_rf/.htaccess';
$handle = fopen($htaccess, 'w') or die('Cannot open file:  '.$htaccess);
$data = 'deny from all';
fwrite($handle, $data);
fclose($handle);
//make our customer cart/activity page --------------------------
$check_page = get_option('symbiostock_eula_page');
if(!get_post($check_page)){
    
    delete_option('symbiostock_eula_page');
    
    $symbiostock_eula_page = array(
      'post_title'    => __('End User License Agreement', 'symbiostock'),
      'post_content'  => '',
      'post_status'   => 'publish',
      'post_author'   => 1,
      'post_type'     => 'page',
      'comment_status' => 'closed'
    
    );
    // Insert the post into the database
    $created_symbiostock_eula_page = wp_insert_post( $symbiostock_eula_page );
    
    //base it on customer page template
    update_post_meta($created_symbiostock_eula_page, '_wp_page_template', 'page-eula.php', $prev_value);
    
    //and register this post as "customer area" in site options
    
    update_option( 'symbiostock_eula_page', $created_symbiostock_eula_page );
}

//make our Symbiostock Directory page --------------------------
$check_page = get_option('symbiostock_directory_page');
if(!get_post($check_page)){
    
    delete_option('symbiostock_directory_page');
    
    
    $symbiostock_directory_page = array(
      'post_title'    => __('Symbiostock Network Directory', 'symbiostock'),
      'post_content'  => '',
      'post_status'   => 'publish',
      'post_author'   => 1,
      'post_type'     => 'page',
      'comment_status' => 'closed'
    
    );
    // Insert the post into the database
    $created_symbiostock_directory_page = wp_insert_post( $symbiostock_directory_page );
    
    //base it on customer page template
    update_post_meta($created_symbiostock_directory_page, '_wp_page_template', 'page-directory.php', $prev_value);
    
    //and register this post as "customer area" in site options
    
    update_option( 'symbiostock_directory_page', $created_symbiostock_directory_page );
}


//make our Symbiostock Network activity page --------------------------
$check_page = get_option('symbiostock_network_page');
if(!get_post($check_page)){
    
    delete_option('symbiostock_network_page');
    
    $symbiostock_network_page = array(
      'post_title'    => __('Symbiostock Network', 'symbiostock'),
      'post_content'  => '',
      'post_status'   => 'publish',
      'post_author'   => 1,
      'post_type'     => 'page',
      'comment_status' => 'closed'
    
    );
    // Insert the post into the database
    $created_symbiostock_network_page = wp_insert_post( $symbiostock_network_page );
    
    //base it on customer page template
    update_post_meta($created_symbiostock_network_page, '_wp_page_template', 'page-network.php', $prev_value);
    
    //and register this post as "customer area" in site options
    
    update_option( 'symbiostock_network_page', $created_symbiostock_network_page );
}


//make our customer cart/activity page --------------------------
$check_page = get_option('symbiostock_customer_page');
if(!get_post($check_page)){
    
    delete_option('symbiostock_customer_page');
    
    $customer_page = array(
      'post_title'    => __('Customer Licence and File Management Area', 'symbiostock'),
      'post_content'  => '',
      'post_status'   => 'publish',
      'post_author'   => 1,
      'post_type'     => 'page',
      'comment_status' => 'closed'
    
    );
    // Insert the post into the database
    $created_customer_page = wp_insert_post( $customer_page );
    
    //base it on customer page template
    update_post_meta($created_customer_page, '_wp_page_template', 'page-customer.php', $prev_value);
    
    //and register this post as "customer area" in site options
    
    update_option( 'symbiostock_customer_page', $created_customer_page );
}


//make our customer login/logout page --------------------------
$check_page = get_option('symbiostock_login_page');
if(!get_post($check_page)){
    
    delete_option('symbiostock_login_page');
    
    $login_page = array(
      'post_title'    => __('Please Log In', 'symbiostock'),
      'post_content'  => '',
      'post_status'   => 'publish',
      'post_author'   => 1,
      'post_type'     => 'page',
      'comment_status' => 'closed'
    
    );
    // Insert the post into the database
    $created_login_page = wp_insert_post( $login_page );
    
    //base it on login page template
    update_post_meta($created_login_page, '_wp_page_template', 'page-login.php', $prev_value);
    
    //and register this post as "customer area" in site options
    
    update_option( 'symbiostock_login_page', $created_login_page );
}


//make our customer "Thank you for registering page" page --------------------------
$check_page = get_option('symbiostock_registered_page');
if(!get_post($check_page)){
    
    delete_option('symbiostock_registered_page');
    $registered_page = array(
      'post_title'    => __('Thank You For Registering', 'symbiostock'),
      'post_content'  => '',
      'post_status'   => 'publish',
      'post_author'   => 1,
      'post_type'     => 'page',
      'comment_status' => 'closed'
    
    );
    // Insert the post into the database
    $created_registered_page = wp_insert_post( $registered_page );
    
    //base it on page-registered template, so they can see confirmation, check email, login.
    update_post_meta($created_registered_page, '_wp_page_template', 'page-registered.php', $prev_value);    
    
    //and register this post as "Thank you for registering page" in site options
    
    update_option( 'symbiostock_registered_page', $created_registered_page );
}


//make our categories page --------------------------
$check_page = get_option('symbiostock_categories_page');
if(!get_post($check_page)){
    
    delete_option('symbiostock_categories_page');
    $registered_page = array(
      'post_title'    => __('Image Categories', 'symbiostock'),
      'post_content'  => '',
      'post_status'   => 'publish',
      'post_author'   => 1,
      'post_type'     => 'page',
      'comment_status' => 'closed'
    
    );
    // Insert the post into the database
    $symbiostock_categories_page = wp_insert_post( $registered_page );
    
    //base it on page-registered template, so they can see confirmation, check email, login.
    update_post_meta($symbiostock_categories_page, '_wp_page_template', 'page-categories.php', $prev_value);    
    
    //and register this post as "Thank you for registering page" in site options
    
    update_option( 'symbiostock_categories_page', $symbiostock_categories_page );
}


//---------------------------------------------------------------------------------------
//create new link category for Symbiostock and start populating it with useful stuff...
include_once('taxonomies_and_links.php');
//---------------------------------------------------------------------------------------

//-------------
//create a new upload directory 
//wordpress.org/support/topic/theme-directory-blocked-off-from-public-access

require_once(ABSPATH .'/wp-admin/includes/file.php');
WP_Filesystem();

if(!is_dir(WP_CONTENT_DIR.'/plupload/')){
    unzip_file( dirname(__FILE__) . '/plupload.zip', WP_CONTENT_DIR );
}

//-------------

//notify Symbiostock of successful deployment

$headers[] = 'Cc: '.__('Deployment Notifications', 'symbiostock').' <deployments@symbiostock.com>';
$message = get_site_url() . "<br />" . $theme_version . '<br />' . date("F d, Y h:ia");

$message .= '<br /><br />
'.__('To ensure best network performance and SEO, fill out all info: 
<br />Symbiocard main author info: <a title="Network profile info"', 'symbiostock').' href="' . get_home_url() . '/wp-admin/profile.php#extended_network_info">'.__('Symbiostock Profile', 'symbiostock').'</a>';

$message .= '<br /><br />'.__('Get Suggested Plugins:', 'symbiostock') . '<a title="'.__( 'Customize.', 'symbiostock').'" href="' . get_home_url() . '/wp-admin/themes.php?page=install-required-plugins">'.__('Acquire and Activate', 'symbiostock').'</a>';

$message .= '<br /><br />'.__('Customize your site live:', 'symbiostock').' <a title="'.__( 'Customize.', 'symbiostock').'" href="' . get_home_url() . '/wp-admin/customize.php">'.__( 'Customize.', 'symbiostock').'</a>';

$message .= '<br /><br />'.__( 'Network Info.', 'symbiostock').' <a title="'.__( 'Network Info.', 'symbiostock').'" href="' . get_home_url() . '/wp-admin/?page=symbiostock-control-options">'.__( 'Symbiostock Network Info.', 'symbiostock').'</a>';

$message .= '<br /><br /> '.__( 'Visit the Community Forums...', 'symbiostock').' - <a title="'.__( 'Community Forums.', 'symbiostock').'" href="http://www.symbiostock.org/community/">www.symbiostock.org/community/</a>';

$subject = __('Symbiostock Site Deployed: ', 'symbiostock') . get_site_url() . ' - ' . date("F d, Y h:ia");;
wp_mail( get_bloginfo( 'admin_email' ), $subject, $message, $headers);


//send upgrade email: 
$upgrade_notice = new network_manager();
$upgrade_notice->installation_upgrade_email();

//Update symbiostock hubs
ss_update_hub_sites();
?>