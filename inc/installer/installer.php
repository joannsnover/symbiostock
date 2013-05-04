<?php
function get_include_contents($filename) {
    if (is_file($filename)) {
        ob_start();
        include $filename;
        return ob_get_clean();
    }
    return false;
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
//now make the seeds directory, for collecting network data 
if (!file_exists(ABSPATH . 'symbiostock_network/seeds/')) {
mkdir(ABSPATH . 'symbiostock_network/seeds/', 0755);
}

//make our htaccess, to protect downloadable products
$handle = fopen(symbiostock_CSSROOT . 'styles.css', 'w') or die('Cannot open file:  '.$htaccess);
$data = get_include_contents(symbiostock_CSSROOT . 'styles.php');
fwrite($handle, $data);
fclose($handle);
//protect files
$htaccess = ABSPATH . 'symbiostock_rf/.htaccess';
$handle = fopen($htaccess, 'w') or die('Cannot open file:  '.$htaccess);
$data = 'deny from all';
fwrite($handle, $data);
fclose($handle);
//make our customer cart/activity page --------------------------
$check_page = get_option('symbiostock_eula_page');
if(!get_page($check_page)){
	
	delete_option('symbiostock_eula_page');
	
	$symbiostock_eula_page = array(
	  'post_title'    => 'End User License Agreement',
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
//make our customer cart/activity page --------------------------
$check_page = get_option('symbiostock_customer_page');
if(!get_page($check_page)){
	
	delete_option('symbiostock_customer_page');
	
	$customer_page = array(
	  'post_title'    => 'Customer Licence and File Management Area',
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
if(!get_page($check_page)){
	
	delete_option('symbiostock_login_page');
	
	$login_page = array(
	  'post_title'    => 'Please Log In',
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
if(!get_page($check_page)){
	
	delete_option('symbiostock_registered_page');
	$registered_page = array(
	  'post_title'    => 'Thank You For Registering',
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
if(!get_page($check_page)){
	
	delete_option('symbiostock_categories_page');
	$registered_page = array(
	  'post_title'    => 'Image Categories',
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
//install the Symbiostock Emailer Plugin
$symbiostock_emailer =  dirname(__FILE__) . '/symbiostock_emailer.php';
$symbiostock_plugin_path = ABSPATH . 'wp-content/plugins/';
if (!copy($symbiostock_emailer, $symbiostock_plugin_path . 'symbiostock_emailer.php')) {
    echo "failed to copy emailer plugin.";
}
// Include the plugin.php file so you have access to the activate_plugin() function
require_once(ABSPATH .'/wp-admin/includes/plugin.php');
activate_plugin($symbiostock_plugin_path . 'symbiostock_emailer.php');
//---------------------------------------------------------------------------------------
//notify Symbiostock of successful deployment

//get version 

$theme_data = wp_get_theme('symbiostock');

$theme_version = $theme_data->Version;

$headers[] = 'Cc: Deployment Notifications <deployments@symbiostock.com>';
$message = get_site_url() . "<br />" . $theme_version . '<br />' . date("F d, Y h:ia");
$subject = 'Symbiostock Site Deployed: ' . get_site_url() . ' - ' . date("F d, Y h:ia");;
wp_mail( get_bloginfo( 'admin_email' ), $subject, $message, $headers);
?>