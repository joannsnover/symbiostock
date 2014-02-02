<?php 

add_theme_support( 'custom-background' );

function ss_generate_google_font_list(){
    
    $fonts = array(
            'Open Sans',
            'Droid',
            'Droid Sans',
            'Ubuntu',
            'Lora',
            'Vollkorn',
            'Lobster',
            'Bree',
            'Playfair Display',
            'Cabin',
            'Montserrat',            
            'Abril Fatface',
            'Hammersmith One',
            'Raleway',
            'Merriweather',
            'Roboto',
            'Roboto Slab',
            'Alerta',
            'Mouse Memoirs',
            'Dancing Script',
            'Allan',
            'Molengo',
            'Lekton',
            'Nobile',
            'News Cycle', 
            'Lustria',
            'Lato',
            'Quattrocento',
            'Quattrocento Sans',
            'Rosario',
            'Oswald',          
            'League Gothic',
            'Amaranth',
            'Forum',
            'Tikal Sans',                       
    );
    
    sort($fonts);
    
    $font_array = array();
    
    foreach($fonts as $font){
        $font_array[str_replace(' ', '+', $font)] = $font;        
    }
    
    return $font_array;
    
}


function symbiostock_customize_register( $wp_customize ) {

    $fonts = ss_generate_google_font_list();
    
    //NAV MENU
 
    $wp_customize->add_section( 'symbiostock_nav_menu' , array(
            'title'      => __( 'Symbiostock Nav Menus', 'symbiostock' ),
            'priority'   => 34,
    ) );

    //main menu location
    
    $wp_customize->add_setting( 'menu_location' , array(
            'default'     => '0',
            
    ) );    
    
    $wp_customize->add_control( 'menu_location', array(
            'label'        => __( 'Menu Location', 'symbiostock' ),
            'type'       => 'radio',
            'choices' => array(
                    '0' => __('Top Anchored', 'symbiostock'),
                    '1' => __('Under Header', 'symbiostock')                    
            ),            
            'section'    => 'symbiostock_nav_menu',
            'settings'   => 'menu_location',
    ) );

   
    
    //invert main menu
    
    $wp_customize->add_setting( 'invert_main_menu' , array(
            'default'     => '1',
    
    ) );
     
    
    $wp_customize->add_control( 'invert_main_menu', array(
            'label'        => __( 'Main/Nav Menu Style', 'symbiostock' ),
            'type'       => 'radio',
            'choices' => array(
                    '1' => __('Basic', 'symbiostock'),
                    '0' => __('Inverted', 'symbiostock')
            ),
            'section'    => 'symbiostock_nav_menu',
            'settings'   => 'invert_main_menu',
    ) ); 

    //invert search menu
    
    $wp_customize->add_setting( 'invert_search_menu' , array(
            'default'     => '1',
    
    ) );
     
    
    $wp_customize->add_control( 'invert_search_menu', array(
            'label'        => __( 'Search/Account Menu Style', 'symbiostock' ),
            'type'       => 'radio',
            'choices' => array(
                    '1' => __('Basic', 'symbiostock'),
                    '0' => __('Inverted', 'symbiostock')
            ),
            'section'    => 'symbiostock_nav_menu',
            'settings'   => 'invert_search_menu',
    ) );

    //style search menu
    
    $wp_customize->add_setting( 'invert_search_menu_type' , array(
            'default'     => '1',
    
    ) );     
    
    $wp_customize->add_control( 'invert_search_menu_type', array(
            'label'        => __( 'Search/Account Menu Fill', 'symbiostock' ),
            'type'       => 'radio',
            'choices' => array(
                    '1' => __('Basic', 'symbiostock'),
                    '0' => __('Blank', 'symbiostock')
            ),
            'section'    => 'symbiostock_nav_menu',
            'settings'   => 'invert_search_menu_type',
    ) );    
    
    //separate search/account menu

    $wp_customize->add_setting( 'separate_search_menu' , array(
            'default'     => '0',    
    ) );
     
    
    $wp_customize->add_control( 'separate_search_menu', array(
            'label'        => __( 'Separate Search/Account Menu?', 'symbiostock' ),
            'type'       => 'radio',
            'choices' => array(
                    '2' => __('Yes - Anchored', 'symbiostock'),
                    '1' => __('Yes - Floated', 'symbiostock'),
                    '0' => __('No', 'symbiostock')
            ),
            'section'    => 'symbiostock_nav_menu',
            'settings'   => 'separate_search_menu',
    ) );
    
    
    //allow blog search
    $wp_customize->add_setting( 'show_blog_search' , array(
            'default'     => '1',
    
    ) );
     
    
    $wp_customize->add_control( 'show_blog_search', array(
            'label'        => __( 'Show "Image/Blog" search option?', 'symbiostock' ),
            'type'       => 'radio',
            'choices' => array(
                    '1' => __('Yes', 'symbiostock'),
                    '0' => __('No', 'symbiostock')
            ),
            'section'    => 'symbiostock_nav_menu',
            'settings'   => 'show_blog_search',
    ) );    
    
    
    
    //GENERAL 
    $wp_customize->add_section( 'symbiostock_general' , array(
        'title'      => __( 'Symbiostock General', 'symbiostock' ), 
        'priority'   => 35,
    ) );

    
    //logo location
    
    $wp_customize->add_setting( 'logo_location' , array(
            'default'     => '0',
    
    ) );
    
    $wp_customize->add_control( 'logo_location', array(
            'label'        => __( 'Logo Location', 'symbiostock' ),
            'priority'    => 0,
            'type'       => 'radio',
            'choices' => array(
                    '0' => __('Left', 'symbiostock'),
                    '1' => __('Centered', 'symbiostock')
            ),
            'section'    => 'symbiostock_general',
            'settings'   => 'logo_location',
    ) );
    
    //main background-color
    
    $wp_customize->add_setting( 'background_color' , array(
            'default'     => '#fff',
            'transport'   => 'postMessage',
    ) );    
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'background_color', array(
            'label'        => __( 'Background Color', 'symbiostock' ),
            'priority' => 1,
            'section'    => 'symbiostock_general',
            'settings'   => 'background_color',
    ) ) );    

    
    //main container background color
    
    $wp_customize->add_setting( 'container_background_color' , array(
            'default'     => '#fff',
            'transport'   => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'container_background_color', array(
            'label'        => __( 'Panel Background Color', 'symbiostock' ),
            'priority' => 2,
            'section'    => 'symbiostock_general',
            'settings'   => 'container_background_color',
    ) ) );    

    //Panel Heading Color 

    //top
    
    $wp_customize->add_setting( 'ph0_color' , array(
            'default'     => '#F5F5F5',
            'transport'   => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'ph0_color', array(
            'label'        => __( 'Panel Head [TOP] Gradient', 'symbiostock' ),
            'priority' => 3,
            'section'    => 'symbiostock_general',
            'settings'   => 'ph0_color',
    ) ) );    
    
    //bottom
    
    $wp_customize->add_setting( 'ph1_color' , array(
            'default'     => '#E8E8E8',
            'transport'   => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'ph1_color', array(
            'label'        => __( 'Panel Head [BOTTOM] Gradient', 'symbiostock' ),
            'priority' => 4,
            'section'    => 'symbiostock_general',
            'settings'   => 'ph1_color',
    ) ) );

    
    //P COLOR
    $wp_customize->add_setting( 'p_color' , array(
            'default'     => '#333',
            'transport'   => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'p_color', array(
            'label'        => __( 'Content/Paragraph Color', 'symbiostock' ),
            'priority' => 6,
            'section'    => 'symbiostock_general',
            'settings'   => 'p_color',
    ) ) );
    
    //H COLOR
    $wp_customize->add_setting( 'h_color' , array(
            'default'     => '#333',
            'transport'   => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'h_color', array(
            'label'        => __( 'Content/Header Color', 'symbiostock' ),
            'priority' => 7,
            'section'    => 'symbiostock_general',
            'settings'   => 'h_color',
    ) ) );

    //FOOTER SECTION
    $wp_customize->add_section( 'symbiostock_footer' , array(
            'title'      => __( 'Symbiostock Footer', 'symbiostock' ),
            'priority'   => 36,
    ) );
    
    //footer container background color
    
    //top
    
    $wp_customize->add_setting( 'f0' , array(
            'default'     => '#E8E8E8',
            'transport'   => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'f0', array(
            'label'        => __( 'Footer [TOP] Gradient', 'symbiostock' ),
            'priority' => 8,
            'section'    => 'symbiostock_footer',
            'settings'   => 'f0',
    ) ) );
    
    //bottom
    
    $wp_customize->add_setting( 'f1' , array(
            'default'     => '#F5F5F5',
            'transport'   => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'f1', array(
            'label'        => __( 'Footer [BOTTOM] Gradient', 'symbiostock' ),
            'priority' => 9,
            'section'    => 'symbiostock_footer',
            'settings'   => 'f1',
    ) ) );   

    //footer panels
    
    $wp_customize->add_setting( 'f-panels' , array(
            'default'     => '#fff',
            'transport'   => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'f-panels', array(
            'label'        => __( 'Footer Panels', 'symbiostock' ),
            'priority' => 10,
            'section'    => 'symbiostock_footer',
            'settings'   => 'f-panels',
    ) ) );
    
    
    //Headings Font
    $wp_customize->add_setting( 'header_font' , array(
            'default'     => 'Open+Sans', 
    
    ) );
    
    $wp_customize->add_control( 'header_font', array(
            'label'        => __( 'Header Font', 'symbiostock' ),
            'priority' => 20,
            'type'       => 'select',
            'choices' => $fonts,
            'section'    => 'symbiostock_general',
            'settings'   => 'header_font',
    ) );
    
    //Body Font
    $wp_customize->add_setting( 'body_font' , array(
            'default'     => 'Open+Sans',           
    
    ) );
    
    $wp_customize->add_control( 'body_font', array(
            'label'        => __( 'Body Font', 'symbiostock' ),
            'priority' => 21,
            'type'       => 'select',
            'choices' => $fonts,
            'section'    => 'symbiostock_general',
            'settings'   => 'body_font',
    ) ); 

    //Rounded Minipic
    $wp_customize->add_setting( 'minipic_corner_radius' , array(
            'default'     => 3,
    
    ) );
    
    $wp_customize->add_control( 'minipic_corner_radius', array(
            'label'        => __( 'Minipic Corner Radius', 'symbiostock' ),
            'priority' => 21,
            'type'       => 'select',
            'choices' => array(
                    0  => '0',
                    3  => '3 px',
                    5  => '5 px',
                    7  => '7 px',
                    10 => '10 px',
                    15 => '15 px',
                    ),
            'section'    => 'symbiostock_general',
            'settings'   => 'minipic_corner_radius',
    ) );    
    
    /*
     * Image Page Related Stuff
    */    
    
    $wp_customize->add_section( 'image_page' , array(
            'title'      => __( 'Symbiostock Image Page', 'symbiostock' ),
            'priority'   => 36,
    ) );

    //image container
    
    $wp_customize->add_setting( 'image_container' , array(
            'default'     => '#fff',
            'transport'   => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'image_container', array(
            'label'        => __( 'Image Container (Background Color)', 'symbiostock' ),
            'section'    => 'image_page',
            'settings'   => 'image_container',
    ) ) );
    
    //bio box

    $wp_customize->add_setting( 'bio_box' , array(
            'default'     => '#eaeaea',
            'transport'   => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bio_box', array(
            'label'        => __( 'Author Container (Background Color)', 'symbiostock' ),
            'section'    => 'image_page',
            'settings'   => 'bio_box',
    ) ) );
    
    //image side widget
    
    $wp_customize->add_setting( 'image_page_side' , array(
            'default'     => '#fff',
            'transport'   => 'postMessage',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'image_page_side', array(
            'label'        => __( 'Widget - Page Side (Background Color)', 'symbiostock' ),
            'section'    => 'image_page',
            'settings'   => 'image_page_side',
    ) ) ); 

    //under image widget
    
    
    $wp_customize->add_setting( 'image_page_bottom' , array(
            'default'     => '#fff',
            'transport'   => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'image_page_bottom', array(
            'label'        => __( 'Widget - Under Image (Background Color)', 'symbiostock' ),
            'section'    => 'image_page',
            'settings'   => 'image_page_bottom',
    ) ) ); 

    
    //under image widget
    
    
    $wp_customize->add_setting( 'image_page_bottom_fullwidth' , array(
            'default'     => '#fff',
            'transport'   => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'image_page_bottom_fullwidth', array(
            'label'        => __( 'Widget - Bottom Fullwidth (Background Color)', 'symbiostock' ),
            'section'    => 'image_page',
            'settings'   => 'image_page_bottom_fullwidth',
    ) ) );
    
    $wp_customize->add_setting( 'strictly_minimal' , array(
            'default'     => 1,  
            'priority' => 5,
    
    ) );
    
    $wp_customize->add_control( 'strictly_minimal', array(
            'label'        => __( 'Image Page Content', 'symbiostock' ),            
            'type'       => 'radio',
            'choices' => array(
                    0  => __('Strictly Minimal', 'symbiostock'),
                    1  => __('Default', 'symbiostock')
            ),
            'section'    => 'image_page',
            'settings'   => 'strictly_minimal',
    ) );
        
    
}
add_action( 'customize_register', 'symbiostock_customize_register' );

function symbiostock_customize_css()
{
    ?>
    <style type="text/css">  
    .container p, .container a:link, .container  
    {
    font-family: '<?php echo str_replace('+', ' ', get_theme_mod('body_font')) ?>', sans-serif !important;        
    }
    
    body 
    { 
    background-color: <?php echo get_theme_mod('background_color'); ?>; 
    }
    
    .container 
    {
    background-color: <?php echo get_theme_mod('container_background_color'); ?>;
    }   
    <?php 
    
    //establish logo location
    $logo_location = get_theme_mod( 'logo_location' );
    if( $logo_location != '' ) {
         
        if($logo_location == 1){
            ?>
            .symbiostock_logo{
            margin-left: auto;
            margin-right: auto;
            text-align: center;
            }
            <?php
        } 
    } 
    
    ?>
    
    .panel-default .panel-heading {
        background-image: linear-gradient(to bottom, <?php echo get_theme_mod('ph0_color') ?>  0%, <?php echo get_theme_mod('ph1_color') ?> 100%);        
    }   
     
    footer.well {    
        background-image: linear-gradient(to bottom, <?php echo get_theme_mod('f0') ?>  0%, <?php echo get_theme_mod('f1') ?> 100%); 
    }
    footer .panel {    
        background-color: <?php echo get_theme_mod('f-panels') ?>; 
    }        
    
    .type-image .front-page-featured { 
    background-color: <?php echo get_theme_mod('widget_background_color'); ?>; 
    }
       
    .symbiostock_branding
    {
    background-image: url(<?php echo header_image(); ?>); 
    background-repeat: no-repeat;
    }   
    
    h1, h2, h3, h4, h5, h6,
    h1 a:link, h2 a:link, h3 a:link, h4 a:link, h5 a:link, h6 a:link 
    {
    color: <?php echo get_theme_mod('h_color'); ?>;
    font-family: '<?php echo str_replace('+', ' ', get_theme_mod('header_font')) ?>', sans-serif !important; 
    }
    
    .panel-heading h1, .panel-heading h2, .panel-heading h3, .panel-heading h4, .panel-heading h5, .panel-heading h6,
    .panel-heading span, .panel-heading p
     {
        font-size: 20px !important;
    }
    
    p 
    {
    color: <?php echo get_theme_mod('p_color'); ?>;
    }  

    .image_container 
    {
    background-color: <?php echo get_theme_mod('image_container'); ?>;
    } 

    .bio_box 
    {
    background-color: <?php echo get_theme_mod('bio_box'); ?>;
    } 
    
    .image_page_side 
    {
    background-color: <?php echo get_theme_mod('image_page_side'); ?>;
    }
     
    .image_page_bottom 
    {
    background-color: <?php echo get_theme_mod('image_page_bottom'); ?>;
    } 
     
    .image_page_bottom_fullwidth 
    {
    background-color: <?php echo get_theme_mod('image_page_bottom_fullwidth'); ?>;
    }

    <?php $px = get_theme_mod('minipic_corner_radius') . 'px'; ?>
    .thumb, .search-result img
    {
    -webkit-border-radius: <?php echo $px ?>;
    -moz-border-radius: <?php echo $px ?>;
    border-radius: <?php echo $px ?>;
    } 
     
    </style>
    <?php
}
add_action( 'wp_head', 'symbiostock_customize_css');

/**
 * Used by hook: 'customize_preview_init'
 *
 * @see add_action('customize_preview_init',$func)
 */
function symbiostock_customizer_live_preview()
{
    wp_enqueue_script(
            'symbiostock-themecustomizer',            //Give the script an ID
            get_template_directory_uri().'/js/customizer.js',//Point to file
            array( 'jquery','customize-preview' ),    //Define dependencies
            '',                        //Define a version (optional)
            true                        //Put script in footer?
    );
}
add_action( 'customize_preview_init', 'symbiostock_customizer_live_preview' );

?>