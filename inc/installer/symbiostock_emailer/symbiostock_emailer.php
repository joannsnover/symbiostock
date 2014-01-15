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
    Copyright 2013     Leo Blanchette     (email : leo@symbiostock.com)
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
        $message  = sprintf( __('New user registration on %s:', 'symbiostock'), get_option('blogname') ) . "\r\n\r\n";
        $message .= sprintf( __('Username: %s', 'symbiostock'), $user_login ) . "\r\n\r\n";
        $message .= sprintf( __('E-mail: %s', 'symbiostock'), $user_email ) . "\r\n";
        @wp_mail(
            get_option('admin_email'),
            sprintf(__('[%s] New Symbiostock User Registration', 'symbiostock'), get_option('blogname') ),
            $message
        );
        
        if(isset($_POST['ss_password_1'])){		
			wp_set_password( $_POST['ss_password_1'], $user_id );			
			$plaintext_pass = $_POST['ss_password_1'];		
		} else {		
			$plaintext_pass = wp_generate_password();			
		} 
        
        //set up Symbiostock Greeting - 
        $symbiostock_greeting = new symbiostock_mail();
        if ( empty( $plaintext_pass ) )
            return;
        $message  = $symbiostock_greeting->send_registration_email($user_login, $plaintext_pass);
        wp_mail(
            $user_email,
            $user_login . __(' - Your username and password for ', 'symbiostock') . home_url(),
            $message
        );
    }
}
add_filter ("wp_mail_content_type", "symbiostock_mail_content_type");
function symbiostock_mail_content_type() {
    return "text/html";
}
add_filter('retrieve_password_message', 'symbiostock_reset_password_message', null, 2);
function symbiostock_reset_password_message( $message, $key ) {
    if ( strpos($_POST['user_login'], '@') ) {
        $user_data = get_user_by_email(trim($_POST['user_login']));
    } else {
        $login = trim($_POST['user_login']);
        $user_data = get_userdatabylogin($login);
    }
    $user_login = $user_data->user_login;
    $msg = __('Just wanted to let you know the password for the following account has been requested to be reset:', 'symbiostock'). "<br /><br />";
    $msg .= network_site_url() . "\r\n\r\n";
    $msg .= sprintf(__('Username: %s'), $user_login) . "<br />";
    $msg .= __('If this message was sent in error, please ignore this email.', 'symbiostock') . "<br /><br />";
    $msg .= __('To reset your password, visit the following address:', 'symbiostock');
    
    $go_to = network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') ;
    
    $msg .= '<br /><br /><a href="' . $go_to . '">' . $go_to . '</a><br /><br />';
    return '<p>' . $msg . '</p>';
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
	
		if(isset($_POST['ss_password_1'])){		
			wp_set_password( $_POST['ss_password_1'], $user_id );			
			$new_pass = $_POST['ss_password_1'];		
		} else {		
			$new_pass = wp_generate_password();			
		} 
           
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
            home_url(),
            
             //*|TITLE|*
            __('Welcome to ', 'symbiostock') . get_bloginfo('name') ,         
            
            //*|LOGO|*
            $this->site_info['symbiostock_logo_link'],            
            
            //*|BODY|*
            __('Login Name:', 'symbiostock') . '<strong>' . $user_login . '</strong><br />' . 
            __('Login Password:', 'symbiostock') .'<strong>' . $plaintext_pass . '</strong><br />' . 
            '- '.__(' Please Log In:', 'symbiostock') .' <strong><em>' . symbiostock_customer_login('Login Page') . '</em></strong><br />' .
            
            __('Customer Area:', 'symbiostock') .' <strong>
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
            '<a title="'.__('A Symbiostock Site', 'symbiostock') .'" href="http://www.symbiostock.com/">
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
            home_url(),
             //*|TITLE|*
            __('Thank you for your purchase at ', 'symbiostock') . get_bloginfo('name') ,         
            //*|LOGO|*
            $this->site_info['symbiostock_logo_link'],            
            //*|BODY|*            
            '<br /><strong>' . symbiostock_customer_area(__('Get your images here...', 'symbiostock') ) . '
            </strong><br /><br />' .
                        
            $purchased_items_to_display . '<br />' .
            
            $this->site_info['symbiostock_customer_thank_you'],
            
            //*|CURRENT_YEAR|*
            date("Y"),                                      
            //*|ABOUT_PAGE|*
            $this->site_info['symbiostock_my_network_about_page'],
            //*|DESCRIPTION|*
            get_bloginfo('description'),                    
            //*|SYMBIOSTOCK|*
            '<a title="'.__('A Symbiostock Site', 'symbiostock') .'" href="http://www.symbiostock.com/">
                <img src="' . symbiostock_LOGOSMALL . '" />
            </a>'                                           
            
        );
        $this->message = str_replace($search, $replace, $body);
    
        $user_info = get_userdata(1);
        
        $headers[] = 'From: '.$user_info-> user_firstname.' <'.get_option('symbiostock_correspondence_email', get_bloginfo('admin_email')).'>';
        $headers[] = 'Cc: '.$user_info-> user_firstname.' <'.get_option('symbiostock_correspondence_email', get_bloginfo('admin_email')).'>';
        
        wp_mail(
            $payer_email,
            $first_name . ' - '.__('Thank you for your purchase at ', 'symbiostock')  . home_url(),
            $this->message, 
            
            $headers
        );
        
        }
    
    }
?>
