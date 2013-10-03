<?php
//this is the register form which can be included in a number of areas.
if(!is_user_logged_in()){
    $user_login_title = 'Please Log In';
    
    } else {
        
    $user_login_title = 'You are logged in.';    
        }                    
?>
    
    <span>Not a member? <em><a title="Register" href="#register-form">Register...</a></em></span>
    
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
        echo symbiostock_customer_area('Customer Area');
        echo " | ";
        ?> <a href="<?php echo wp_logout_url( $symbiostock_redirect ); ?>" title="Logout"><i class="icon-key"> Logout</i></a> <?php
    }
    if ( ! is_user_logged_in() ){
    ?>
    <hr />
    
    <div>
            <div id="register-form">  
            <div class="title">  
                <h2><i class="icon-edit"></i> Register</h2>  
                
            </div>  
            <?php
            
            $registered_page = get_option( 'symbiostock_registered_page' );
            
            $link_to_registered = get_permalink($registered_page);
            
            ?>
<form method="post" action="<?php echo site_url('wp-login.php?action=register', 'login_post') ?>" class="wp-user-form">
                <div class="username form-group">
                    <label for="user_login"><?php _e('Username'); ?>: </label>
                    <input class="form-control" type="text" name="user_login" value="<?php echo esc_attr(stripslashes($user_login)); ?>" size="20" id="user_login" tabindex="101" />
                </div>
                <div class="password form-group">
                    <label for="user_email"><?php _e('Your Email'); ?>: </label>
                    <input class="form-control" type="text" name="user_email" value="<?php echo esc_attr(stripslashes($user_email)); ?>" size="25" id="user_email" tabindex="102" />
                </div>
                <div class="login_fields">
                    <?php do_action('register_form'); ?>
                    <input class="btn btn-primary form-control" type="submit" name="user-submit" value="<?php _e('Sign up!'); ?>" class="user-submit" tabindex="103" />
                    <?php $register = $_GET['register']; if($register == true) { echo '<p>Check your email for the password!</p>'; }
                    else {echo '<p><br />*A password will be emailed to you.</p>';} ?>
                    <input type="hidden" name="redirect_to" value="<?php echo $link_to_registered; ?>?register=true" />
                    <input type="hidden" name="user-cookie" value="1" />
                    
                </div>
            </form>
            </div>  
    </div>
<?php }
?>
