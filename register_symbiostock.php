<?php
//this is the register form which can be included in a number of areas.

if ( ! is_user_logged_in() ){
    ?>
   
    <div>
            <div id="register-form">  
            <div class="title">  
                <h2><i class="icon-edit"></i> <?php _e('Register', 'symbiostock') ?></h2>  
                
            </div>  
            <?php
            
            $registered_page = 'http';
            if ($_SERVER["HTTPS"] == "on") {
                $registered_page .= "s";
            }
            $registered_page .= "://";
            if ($_SERVER["SERVER_PORT"] != "80") {
                $registered_page .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
            } else {
                $registered_page .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
            }
            
            ?>
<form method="post" action="<?php echo site_url('wp-login.php?action=register', 'login_post') ?>" class="wp-user-form">
                <input id="ss_password_no_match_text" type="hidden" name="ss_password_no_match_text" value="<?php _e('Password does not match.', 'symbiostock'); ?>" />
                <input id="ss_create_account_text" type="hidden" name="ss_create_account_text" value="<?php _e('Create account!', 'symbiostock'); ?>" />
                
                
                
                <div class="username form-group">
                    <label for="user_login"><?php _e('Username', 'symbiostock'); ?>: </label>
                    <input class="form-control" type="text" name="user_login" value="<?php echo esc_attr(stripslashes($user_login)); ?>" size="20" id="user_login" tabindex="101" />
                </div>
                <div class=" form-group">
                    <label for="user_email"><?php _e('Your Email', 'symbiostock'); ?>: </label>
                    <input class="form-control" type="text" name="user_email" value="<?php echo esc_attr(stripslashes($user_email)); ?>" size="25" id="user_email" tabindex="102" />
                </div>
                
                <!--confirm pass-->
                
                <div class=" form-group">
                    <label for="ss_password_1"><?php _e('Password', 'symbiostock'); ?>: </label>
                    <input id="ss_password_1" class=" password_mismatch form-control password " type="password" name="ss_password_1" value="" size="25" id="user_email" tabindex="102" />
                </div>
                
                <div class=" form-group">
                    <label for="ss_password_2"><?php _e('Confirm Password', 'symbiostock'); ?>: </label>
                    <input id="ss_password_2" class=" password_mismatch form-control password" type="password" name="ss_password_2" value="" size="25" id="user_email" tabindex="102" />
                </div> 
                
                <!--confirm pass-->
                                
                <div class="login_fields">
                    <?php do_action('register_form'); ?>
                    <input id="ss_account_submit" class="btn btn-primary form-control user-submit" type="submit" name="user-submit" value="<?php _e('Sign up!', 'symbiostock'); ?>" tabindex="103" />
                    <?php $register = $_GET['register']; if($register == true) { _e('<p>Check your email for the password!</p>', 'symbiostock'); }
                    else { _e('<p><br />*A password will be emailed to you.</p>', 'symbiostock');} ?>
                                                           
                    
                    <input type="hidden" name="redirect_to" value="<?php echo $registered_page; ?>?register=true" />
                    <input type="hidden" name="user-cookie" value="1" />
                    
                </div>
            </form>
            </div>  
    </div>
    <hr />
<?php }

if(!is_user_logged_in()){
    $user_login_title = __('Please Log In', 'symbiostock');
    
    } else {
        
    $user_login_title = __('You are logged in.', 'symbiostock');    
        }                    
?>
            
    <h2 class="entry-title"><i class="icon-user"> </i> <?php echo $user_login_title; ?></h2>
    
    <?php
    
    if ( 'image' == get_post_type() ){
        
        $symbiostock_redirect =  $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
    
    } else {
        
        $symbiostock_redirect = get_option('symbiostock_customer_page', get_option('page_on_front'));
        
        $symbiostock_redirect =  get_permalink( $symbiostock_redirect  );
        
        }
            
    if ( ! is_user_logged_in() ) { // Display WordPress login form:
        $args = array(
            'echo' => false,
            'form_id' => 'loginform-custom',
            'label_username' => __( 'User Name' ),
            'label_password' => __( 'Password' ),
            'label_remember' => __( 'Keep me logged in' ),
            'label_log_in' => __( 'Log In' ),
            'remember' => true,
            'redirect' => $symbiostock_redirect,
        );
        
        $login_find = array(
                'class="login-username"',
                'class="input"',
                'class="login-password"',
                'class="login-submit"',
                'class="button-primary"',
                );
        $login_replace = array(
                'class="login-username form-group"',
                'class="input form-control"',
                'class="login-password form-group"',
                'class="login-submit form-group"',
                'class="button-primary btn btn-primary form-control"',
                );        
        
        echo str_replace($login_find, $login_replace, wp_login_form( $args ));
    } else { // If logged in:
        echo symbiostock_customer_area(__('Customer Area', 'symbiostock'));
        echo " | ";
        ?> <a href="<?php echo wp_logout_url( $symbiostock_redirect ); ?>" title="<?php _e('Logout', 'symbiostock') ?> "><i class="icon-key"> <?php _e('Logout', 'symbiostock') ?></i></a> <?php
    }
