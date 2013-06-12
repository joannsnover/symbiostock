<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package symbiostock
 * @since symbiostock 1.0
 */
 
$is_marketer_request = symbiostock_marketer();

if($is_marketer_request == true){	
	exit;	
	}

 
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php wp_title( '|', true, 'right' ); ?></title>

<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600' rel='stylesheet' type='text/css'>
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<?php symbiostock_dublin_core(true); ?>

<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div id="page" class="hfeed site container">
	<?php do_action( 'before' ); ?>
	<header id="masthead" class="site-header" role="banner">
    	
   		<div class="row-fluid">
            <div class="span6">	
                <hgroup>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><img alt="<?php bloginfo( 'description' ); ?>" class="logo" src ="<?php echo get_option( 'symbiostock_logo_link', symbiostock_LOGO ); ?>" /></a>
                    </hgroup> 
            </div>
          
            <div class="span6">
                <div class="row-fluid">
                    <div class="span12">
                    <?php 	
					symbiostock_above_header_nav( ); 
					?>
                    </div> 
                    
                </div>        
            				<?php
                //get the search form
                include_once('searchform_symbiostock.php');
                ?>
                
            </div>
        </div>
        
		<div class="row"> 
        
			<nav role="navigation" class="site-navigation main-navigation"> 
               
                <h1 class="assistive-text"><?php _e( 'Menu', 'symbiostock' ); ?></h1>
                <div class="assistive-text skip-link"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'symbiostock' ); ?>"><?php _e( 'Skip to content', 'symbiostock' ); ?></a></div>
            
                <div id="main-navigation" class="span12  header-main-nav"> 
                <?php symbiostock_header_nav( ); ?>
                </div>
             </nav><!-- .site-navigation .main-navigation -->
                
		</div>
            
		
	</header><!-- #masthead .site-header -->
	<div id="main" class="site-main">	