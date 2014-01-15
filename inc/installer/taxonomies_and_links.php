<?php
// our installer uses this file to update taxonomies and links related to Symbiostock
if (!function_exists('wp_insert_link'))
    include_once(ABSPATH . '/wp-admin/includes/bookmark.php');
$parent_term = term_exists('Symbiostock', 'link_category'); // array is returned if taxonomy is given
if(!$parent_term){
    $parent_term_id    = $parent_term['term_id']; // get numeric term id
    $symbiostock_links = wp_insert_term('Symbiostock', // the term 
        'link_category', // the taxonomy
        array(
        'description' => __('Symbiostock resources and microstock links.', 'symbiostock'),
        'slug' => 'symbiostock'
        
    ));
    
    //our starting links to intiialize in Symbiostock
    $symbiostock_links_array = array(
        
        array(
            'link_url' => 'http://www.symbiostock.com/',
            'link_name' => 'Symbiostock',
            'link_target' => '_blank',
            'link_description' => __('Symbiostock. The integrated microstock community. Get connected with the Symbiostock community. Download the theme and start selling your images.', 'symbiostock'),
            'link_rss' => 'http://www.symbiostock.com/feed/'
        ),
        
        array(
            'link_url' => 'http://www.clipartillustration.com/',
            'link_name' => 'ClipArtIllustration.com.',
            'link_target' => '_blank',
            'link_description' => __('Leo\'s illustration website. Was the prototype for Symbiostock.', 'symbiostock'),
            'link_rss' => 'http://www.clipartillustration.com/feed/'
        ),
        
        array(
            'link_url' => 'http://www.microstockgroup.com/',
            'link_name' => 'Microstock Group',
            'link_target' => '_blank',
            'link_description' => __('MicrostockGroup - Professional Microstock Forum', 'symbiostock'),
            'link_rss' => 'http://www.microstockgroup.com/.xml/?type=rss'
        ),
        
        array(
            'link_url' => 'http://www.clipartof.com/',
            'link_name' => 'ClipArtOf.com',
            'link_target' => '_blank',
            'link_description' => __('High resolution royalty free clipart', 'symbiostock'),
            'link_rss' => 'http://www.clipartof.com/feeds/New-Clipart-Illustrations.rss'
        )
        
    );
    
    foreach ($symbiostock_links_array as $link) {
        
        $linkdata = array(
            //"link_id"        => 0, // integer, if updating, the ID of the existing link
            "link_url" => $link['link_url'], // varchar, the URL the link points to
            "link_name" => $link['link_name'], // varchar, the title of the link
            "link_image" => '', // varchar, a URL of an image
            "link_target" => $link['link_target'], // varchar, the target element for the anchor tag
            "link_description" => $link['link_description'], // varchar, a short description of the link
            "link_visible" => 'Y', // varchar, Y means visible, anything else means not
            "link_owner" => '', // integer, a user ID
            "link_rating" => 0, // integer, a rating for the link
            "link_updated" => '0000-00-00 00:00:00', // datetime, when the link was last updated
            "link_rel" => '', // varchar, a relationship of the link to you
            "link_rss" => $link['link_rss'], // varchar, a URL of an associated RSS feed
            "link_category" => $symbiostock_links['term_id'] // int, the term ID of the link category. if empty, uses default link category
        );
        
        wp_insert_link($linkdata, true);
        
        //flush rules is necessary to keep links working
        global $wp_rewrite;
        $wp_rewrite->flush_rules();
        
    }
}
// Now we update our categories. This is a bonus feature, not required.
$symbiostock_categories = array(
    __( 'Abstract | Backgrounds | Textures', 'symbiostock'),
    __( 'Animals',                           'symbiostock'),
    __( 'Architecture | Interiors',          'symbiostock'),
    __( 'Beauty | Fashion',                  'symbiostock'),
    __( 'Business',                          'symbiostock'),
    __( 'Cities | Rural | Places',           'symbiostock'),
    __( 'Concepts',                          'symbiostock'),
    __( 'Education',                         'symbiostock'),
    __( 'Food | Drink',                      'symbiostock'),
    __( 'Health',                            'symbiostock'),
    __( 'Healthcare',                        'symbiostock'),
    __( 'Events',                            'symbiostock'),
    __( 'Landscape',                         'symbiostock'),
    __( 'Miscellaneous',                     'symbiostock'),
    __( 'Nature',                            'symbiostock'),
    __( 'Objects',                           'symbiostock'),
    __( 'People',                            'symbiostock'),
    __( 'Recreation | Hobbies',              'symbiostock'),
    __( 'Science',                           'symbiostock'),
    __( 'Shopping | Retail',                 'symbiostock'),
    __( 'Signs | Symbols | Icons',           'symbiostock'),
    __( 'Sport',                             'symbiostock'),
    __( 'Technology',                        'symbiostock'),
    __( 'Transport',                         'symbiostock'),
    __( 'Travel',                            'symbiostock'),
    __( 'Vectors | Illustrations | 3D',      'symbiostock'),
    __( 'Vintage | Retro',                   'symbiostock'),
    __( 'Music',                             'symbiostock'),
    __( 'Industry',                          'symbiostock'),
);

$categories_installed = get_option('categories_installed', false);

if($categories_installed == false){

    foreach($symbiostock_categories as $category){
        $parent_term = term_exists( $category, 'image-type' ); // array is returned if taxonomy is given
        
        if(!$parent_term){
            
            $parent_term_id = $parent_term['term_id']; // get numeric term id
            wp_insert_term(
              $category, // the term 
              'image-type', // the taxonomy
              array(
                'description'=> $category,
                'slug' => $category . '-' . __('images', 'symbiostock'),
                'parent'=> $parent_term_id
              )
            );
            
        }
    }
}

update_option('categories_installed', true);

//check for erroneously named term and replace it, update term info so we know featured images category
$wrong_name_term = get_term_by('slug', 'symbiostock-featured-images-images', 'image-type');
wp_delete_term( $wrong_name_term->term_id, 'image-type', $args );
//make correct featured images category and use
    $parent_term = term_exists( $category, 'image-type' ); // array is returned if taxonomy is given
    
    if(!$parent_term){        
        $featured_images_category_id = wp_insert_term(
            __('Symbiostock Featured Images', 'symbiostock'), // the term 
            'image-type', // the taxonomy
            array(
                'description'=> __('Category for Symbiostock Featured Images. Used by "Featured Images" widget.', 'symbiostock'),
                'slug' => 'Symbiostock Featured Images',
                'parent'=> $parent_term_id
            )
        );
}
$featured_images_cat = get_term_by('slug', 'symbiostock-featured-images', 'image-type');
update_option('symbiostock_featured_images', $featured_images_cat->term_id);
?>