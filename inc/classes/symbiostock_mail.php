<?php
// A class for Symbiostock Automated Communications
add_action('user_register', 'symbiostock_registration');
function symbiostock_registration($user_id) {
       
		$new_pass = wp_generate_password();
	
		$user_data = get_userdata( $user_id );
		
		$mail = new symbiostock_mail();
		
		$mail->send_registration_email($new_pass, $user_data);
		
}
add_filter( 'wp_mail_content_type', 'symbiostock_set_html_content_type' );
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
	
	function construct(){
		
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
			
			$value = get_option($info, '');
			
			$this->site_info[$info] = $value[0];
			
			}
		
		}
	
	public function send(){
		
		wp_mail( $this->to, $this->subject, $this->message, $this->headers, $this->attachments );
		
		}
	
	public function send_registration_email($new_pass, $user_data){
				
		$this->to = $user_data->user_email;
		$this->subject = 'To' . $user_data->user_login . ': Thank you for signing up at ' . get_bloginfo('wpurl') ;
		
		$this->login_pass = $new_pass;
		$this->login_name = $user_data->user_login;
		
		$body = file_get_contents(symbiostock_CLASSROOT . 'email/welcome_template.html');
		
		$search  = array(
			'*|TITLE|*', 		
			'*|LOGO|*', 
			'*|BODY|*',
			'*|CURRENT_YEAR|*',
			'*|WEBSITE|*', 
			'*|ABOUT_PAGE|*',
			'*|DESCRIPTION|*',
			'*|SYMBIOSTOCK|*',
		);
		$replace = array(
	     	//*|TITLE|*
			'Welcome to ' . get_bloginfo('name') ,         
			
			//*|LOGO|*
			$this->site_info['symbiostock_logo_link'],            
			
			//*|BODY|*
			'Login Name: ' . $this->login_name . '<br />' . 
			'Login Password: ' . $this->login_pass . '<br />' . 
			'Customer Area: ' . $this->site_info['symbiostock_customer_page'] . '<br />' .
			$this->site_info['symbiostock_customer_welcome_body'],
			
			//*|CURRENT_YEAR|*
			date("Y"),                                      
			
			//*|WEBSITE|*
			get_bloginfo('wpurl'),                          
			
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
		$this->send();
		}
	
	}
remove_filter( 'wp_mail_content_type', 'symbiostock_set_html_content_type' ); // reset content-type to to avoid conflicts -- http://core.trac.wordpress.org/ticket/23578
?>