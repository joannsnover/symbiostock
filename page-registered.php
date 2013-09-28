<?php
/*
Template Name: Registered, Thank You
*/
/**
 * The template for displaying home landing page.
 *
 * @package symbiostock
 * @since symbiostock 1.0
 */
get_header(); ?>
        <div class="row">
    
            <div id="primary" class="content-area col-md-12">
                <div id="content" class="site-content" role="main">
                <?php
                //add support for YOAST SEO
                if ( function_exists('yoast_breadcrumb') ) {
                yoast_breadcrumb('<span class="text-info" id="breadcrumbs">','</span><hr />');
                } ?>                     
    
                    <?php while ( have_posts() ) : the_post(); ?>
                    <?php
                    
                    //this is the register form which can be included in a number of areas.
                    if(!is_user_logged_in()){
                        $user_login_title = 'Please Log In';
                        
                        } else {
                            
                        $user_login_title = 'You are logged in.';    
                            }                    
                    ?>
                    <div class="jumbotron">Thank you for registering. <strong>Please check your email</strong> and log in below.</div>
                    <div class="jumbotron">
                        
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
                                'redirect' => admin_url(), 
                                'form_id' => 'loginform-custom',
                                'label_username' => __( 'User Name' ),
                                'label_password' => __( 'Password' ),
                                'label_remember' => __( 'Keep me logged in' ),
                                'label_log_in' => __( 'Log In' ),
                                'remember' => true,
                                'redirect' => $symbiostock_redirect,
                            );
                            wp_login_form( $args );
                        } else { // If logged in:
                            echo symbiostock_customer_area('Customer Area');
                            echo " | ";
                            ?> <a href="<?php echo wp_logout_url( $symbiostock_redirect ); ?>" title="Logout"><i class="icon-key"> Logout</i></a> <?php
                        }                       
                    ?>
                    </div> 
                        
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <header class="entry-header">
                          
                        </header><!-- .entry-header -->
                    
                        <div class="entry-content">
                            <?php the_content(); ?>
                            <?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'symbiostock' ), 'after' => '</div>' ) ); ?>
                            <?php edit_post_link( __( 'Edit', 'symbiostock' ), '<span class="edit-link">', '</span>' ); ?>
                        </div><!-- .entry-content -->
                    </article><!-- #post-<?php the_ID(); ?> -->
                    
                    <?php endwhile; // end of the loop. ?>
    
                </div><!-- #content .site-content -->
            </div><!-- #primary .content-area -->
        
        </div>        
<?php get_footer(); ?>