<?php
/*
Plugin Name: SYMBIOSTOCK - Emails and Notifications
Plugin URI: http://symbiostock.com/
Description: Modifies email functionality of wordpress. Changes New User notifications, adds essential automated email functions.
Author: Leo Blanchette
Author URI: http://clipartillustration.com/
Version: 1.0.0
License: GNU General Public License v2.0 or later
License URI: http://www.opensource.org/licenses/gpl-license.php
*/
/*
	Copyright 2013	 Leo Blanchette	 (email : leo@symbiostock.com)
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
// Change Welcome Email
if ( !function_exists('wp_new_user_notification') ) {
	function wp_new_user_notification( $user_id, $plaintext_pass = '' ) {
		$user = new WP_User( $user_id );
		$user_login = stripslashes( $user->user_login );
		$user_email = stripslashes( $user->user_email );
		$message  = sprintf( __('New user registration on %s:'), get_option('blogname') ) . "\r\n\r\n";
		$message .= sprintf( __('Username: %s'), $user_login ) . "\r\n\r\n";
		$message .= sprintf( __('E-mail: %s'), $user_email ) . "\r\n";
		@wp_mail(
			get_option('admin_email'),
			sprintf(__('[%s] New Symbiostock User Registration'), get_option('blogname') ),
			$message
		);
		//set up Symbiostock Greeting - 
		$symbiostock_greeting = new symbiostock_mail();
		if ( empty( $plaintext_pass ) )
			return;
		$message  = $symbiostock_greeting->send_registration_email($user_login, $plaintext_pass);
		wp_mail(
			$user_email,
			$user_login . ' - Your username and password for ' . get_bloginfo('wpurl'),
			$message
		);
	}
}
add_filter ("wp_mail_content_type", "symbiostock_mail_content_type");
function symbiostock_mail_content_type() {
	return "text/html";
}
	
add_filter ("wp_mail_from", "symbiostock_mail_from");
function symbiostock_mail_from() {
	
	$email = get_option('symbiostock_correspondence_email');
	
	return $email;
}
	
add_filter ("wp_mail_from_name", "symbiostock_mail_from_name");
function symbiostock_mail_from_name() {
	
	$name = get_option('symbiostock_my_network_name');
	
	return $name;
}
function symbiostock_registration($user_id) {
		$new_pass = wp_generate_password();
		$user_data = get_userdata( $user_id );
		$mail = new symbiostock_mail();
		$mail->send_registration_email($new_pass, $user_data);
}
function symbiostock_set_html_content_type()
{
	return 'text/html';
}
class symbiostock_mail{
	public $to          = '';
	public $subject     = ''; 
	public $message     = ''; 
	public $headers     = ''; 
	public $attachments = ''; 
	public $site_info = array();	
	private $login_pass = '';
	private $login_name = '';
	
	function __construct(){	
		$site_info = array(
			'symbiostock_logo_link',
			'symbiostock_correspondence_email',
			'symbiostock_customer_welcome_body',
			'symbiostock_customer_thank_you',
			'symbiostock_my_network_announcement',
			'symbiostock_my_network_about_page',
			'symbiostock_customer_page'
		);
		foreach($site_info as $info){
			$value = stripslashes(get_option($info, ''));			
			$this->site_info[$info] = $value;
			
			}
		}
	
	public function send(){
		wp_mail( $this->to, $this->subject, $this->message, $this->headers, $this->attachments );
		remove_filter( 'wp_mail_content_type', 'symbiostock_set_html_content_type' ); // reset content-type to to avoid conflicts -- http://core.trac.wordpress.org/ticket/23578
		}
	public function send_registration_email($user_login, $plaintext_pass){
		$body = file_get_contents(symbiostock_INCLUDESROOT . 'email_templates/welcome_template.html');
		$search  = array(
			'*|URL|*', 
			'*|TITLE|*', 		
			'*|LOGO|*', 
			'*|BODY|*',
			'*|CURRENT_YEAR|*',
			'*|ABOUT_PAGE|*',
			'*|DESCRIPTION|*',
			'*|SYMBIOSTOCK|*',
		);
		$replace = array(
			//*|URL|*
			get_bloginfo('wpurl'),
			
	     	//*|TITLE|*
			'Welcome to ' . get_bloginfo('name') ,         
			
			//*|LOGO|*
			$this->site_info['symbiostock_logo_link'],            
			
			//*|BODY|*
			'Login Name: <strong>' . $user_login . '</strong><br />' . 
			'Login Password: <strong>' . $plaintext_pass . '</strong><br />' . 
			'- Please Log In: <strong><em>' . symbiostock_customer_login('Login Page') . '</em></strong><br />' .
			
			'Customer Area: <strong>
			' . symbiostock_customer_area('Licensing and Downloads') . '
			</strong><br /><br />' .
			
			$this->site_info['symbiostock_customer_welcome_body'],
			
			//*|CURRENT_YEAR|*
			date("Y"),                                      
			//*|ABOUT_PAGE|*
			$this->site_info['symbiostock_my_network_about_page'],
			
			//*|DESCRIPTION|*
			get_bloginfo('description'),                    
			
			//*|SYMBIOSTOCK|*
			'<a title="A Symbiostock Site" href="http://www.symbiostock.com/">
				<img src="' . symbiostock_LOGOSMALL . '" />
			</a>'                                           
			
		);
		$this->message = str_replace($search, $replace, $body);
		return $this->message;
		}
public function send_thank_you_email($payer_email, $first_name,  $purchased_items_to_display){
		$body = file_get_contents(symbiostock_INCLUDESROOT . 'email_templates/welcome_template.html');
		$search  = array(
			'*|URL|*', 
			'*|TITLE|*', 		
			'*|LOGO|*', 
			'*|BODY|*',
			'*|CURRENT_YEAR|*',
			'*|ABOUT_PAGE|*',
			'*|DESCRIPTION|*',
			'*|SYMBIOSTOCK|*',
		);
		$replace = array(
			//*|URL|*
			get_bloginfo('wpurl'),
	     	//*|TITLE|*
			'Thank you for your purchase at ' . get_bloginfo('name') ,         
			//*|LOGO|*
			$this->site_info['symbiostock_logo_link'],            
			//*|BODY|*
			$purchased_items_to_display . '<br />' .
	
			'Customer Area: <strong>
			' . symbiostock_customer_area('Licensing and Downloads') . '
			</strong><br /><br />' .
			
			$this->site_info['symbiostock_customer_thank_you'],
			
			//*|CURRENT_YEAR|*
			date("Y"),                                      
			//*|ABOUT_PAGE|*
			$this->site_info['symbiostock_my_network_about_page'],
			//*|DESCRIPTION|*
			get_bloginfo('description'),                    
			//*|SYMBIOSTOCK|*
			'<a title="A Symbiostock Site" href="http://www.symbiostock.com/">
				<img src="' . symbiostock_LOGOSMALL . '" />
			</a>'                                           
			
		);
		$this->message = str_replace($search, $replace, $body);
	
		$user_info = get_userdata(1);
		
		$headers[] = 'From: '.$user_info-> user_firstname.' <'.get_option('symbiostock_correspondence_email', get_bloginfo('admin_email')).'>';
		$headers[] = 'Cc: '.$user_info-> user_firstname.' <'.get_option('symbiostock_correspondence_email', get_bloginfo('admin_email')).'>';
		
		wp_mail(
			$payer_email,
			$first_name . ' - Thank you for your purchase at ' . get_bloginfo('wpurl'),
			$this->message, 
			
			$headers
		);
		
		}
	
	}
?>