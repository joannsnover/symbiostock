<?php


//this turns creates xml results from a local query
function symbiostock_xml_results($network_query){
    
    $wp_query= new WP_Query($network_query);
    //var_dump($wp_query);
    $meta_values = array(
         'collection_img',
        'live',
        'price_bloggee',
        'price_small',
        'price_medium',
        'price_large',
        'price_vector',
        'price_zip',
        'locked',
        'discount_percent',
        'exclusive',
        'symbiostock_minipic',
        'symbiostock_preview',
        'symbiostock_transparency',
        'size_eps',
        'size_zip',
        'symbiostock_rating',
        'symbiostock_rank' 
    );
    
    $meta_values_serialized = array(
         'extensions',
        'collections',
        'related_images',
        'size_info' 
    );
        
    // NOTE - this script needs to be protected more from crashes if the data its fetching is corrupt or wrongly formatted
    
    
    //create the xml document
    $symbiostock_xml = new DOMDocument();    
    
    $root = $symbiostock_xml->appendChild( $symbiostock_xml->createElement( "symbiostock_search_results" ) );
    
    if(!is_404()){        
        //create the root element    
        $symbiostock_init_search_cat = get_query_var('image-type');
        $symbiostock_init_search_keyword = get_query_var('image-tags');
        
        if(isset($symbiostock_init_search_cat)){
            
            $symbiostock_init_search = get_query_var('image-type');
            
            } elseif (isset($symbiostock_init_search_keyword)){
                
            $symbiostock_init_search = get_query_var('image-tags');
            
            }
        
        if(isset($symbiostock_init_search)){
        
        if($wp_query->have_posts()) :        
            
            $totalResults = $root->appendChild( 
                $symbiostock_xml->createElement( "total_results", $wp_query->found_posts ) );

            $totalResults = $root->appendChild( 
                $symbiostock_xml->createElement( "pages", $wp_query->max_num_pages ) );
                    
            while($wp_query->have_posts()) : $wp_query->the_post();
                
                $meta = get_post_custom();
                
                //get post id    
                $ID = get_the_ID();
                //print_r($meta);
                 
                //create a image element
                $imageTag = $root->appendChild( 
                    $symbiostock_xml->createElement( "image" ) );
                $author = get_the_author();
        
                //create the permalink element
                $imageTag->appendChild( 
                    $symbiostock_xml->createElement( "permalink", get_permalink( $ID ) ) );
        
        
                //create the id element
                $imageTag->appendChild( 
                    $symbiostock_xml->createElement( "id", $ID ) );
                
                //title -> basic verbage for preview
                $imageTag->appendChild( 
                    $symbiostock_xml->createElement( "title", ssde(get_the_title()) ) );
                
                //loop through our meta values to build the elements
                
                foreach ( $meta_values as $name ) {
                    
                    if(isset($meta[$name][0])){
                        $imageTag->appendChild( 
                            $symbiostock_xml->createElement( $name, ssde($meta[$name][0]) ) );
                    }
                    
                }
                
                //deal with our serialized data
                
                foreach ( $meta_values_serialized as $name ) {
                    switch ( $name ) {
                        
                        case $name == 'extensions':
                            
                            $extTag = $imageTag->appendChild( 
                                $symbiostock_xml->createElement( ssde($name) ) );
                            
                        
                            if(!empty($meta[$name][0])){
                                    
                                //get_post_custom is double serialized due to wordpress, so we have to adjust a bit
                                
                                $extensions = maybe_unserialize( $meta[$name][0] );
                                
                                $array = maybe_unserialize( $extensions );
                                
                                
                                foreach ( $array as $extension ) {
                                    $extTag->appendChild( 
                                        $symbiostock_xml->createElement( "ext", ssde($extension )) );
                                }
                            
                            }
                            break;
                        
                        case $name == 'size_info':
                            
                            $sizeTag = $imageTag->appendChild( 
                                $symbiostock_xml->createElement( ssde($name) ) );
                            
                            if(!empty($meta[$name][0])){
                                
                                //get_post_custom is double serialized due to wordpress, so we have to adjust a bit
                                
                                $sizes = maybe_unserialize( $meta[$name][0] );
                                
                                $array = maybe_unserialize( $sizes );
                                
                                if ( is_array( $array ) ) {
                                    foreach ( $array as $size => $info ) {
                                        $infoTag = $sizeTag->appendChild( 
                                            $symbiostock_xml->createElement( ssde($size) ) );
                                        
                                        foreach ( $info as $size => $size_specs ) {
                                            $infoTag->appendChild( 
                                                $symbiostock_xml->createElement( $size, ssde($size_specs) ) );
                                           
                                        }
                                    }
                                }
                            }
                            
                            break;
                        
                        case $name == 'related_images':
                            
                            break;
                        
                        case $name == 'collections':
                            
                            break;
                            
                    }
                    
                }
                
                //create keywords
                $keyTag = $imageTag->appendChild( 
                    $symbiostock_xml->createElement( "keywords" ) );
                
                $keywords = get_the_terms( $ID, 'image-tags' );
                
                if ( $keywords ) {
                    foreach ( $keywords as $keyword ) {
                        $keyTag->appendChild( 
                            $symbiostock_xml->createElement( "keyword", ssde($keyword->name) ) );
                    }
                }
                
                //create the author element
                $imageTag->appendChild( 
                    $symbiostock_xml->createElement( "author", ssde($author) ) );
                    
                //create the if this is a network search element
                $imageTag->appendChild( 
                    $symbiostock_xml->createElement( "network", ssde($author) ) );    
                
                endwhile;
          
            endif;
        
        //if there are any "seeds" in our directory containing the searched term, we can list them    
        $foundSeeds = $root->appendChild( 
                $symbiostock_xml->createElement( "found_seeds" ) );
        
        //get seeds, if any        
        $find_seeds = new network_manager();
        $seeds = $find_seeds->get_seeds_by_keyword( );
        
        if(is_array($seeds) && !empty($seeds)){
            
            foreach ( $seeds as $found_seed ) {
                    foreach ( $found_seed as $seed ) {
        
                    $foundSeeds->appendChild( 
                        $symbiostock_xml->createElement( "seed", $seed ) );
                    
                    } //$promoted_site as $site
                } //$promoted_sites as $promoted_site
        
            }
                
        
        //make our pagination
        global $wp_rewrite; 
        
        if ( get_query_var( 'paged' ) )
            $paged = get_query_var( 'paged' );
        
        elseif ( get_query_var( 'page' ) )
            $paged = get_query_var( 'page' );
        
        else
            $paged = 1;
                 
        //echo str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) );
        $big = 999999999; // need an unlikely integer
        //$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $symbiostock_search_pagination =  paginate_links( array(
            'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'format' => '?page=%#%',
            'current' => max( 1, $paged ),
            'total' => $wp_query->max_num_pages,
            'type' => 'array',
            'prev_text'    => __('Previous', 'symbiostock'),
            'next_text'    => __('Next', 'symbiostock'),
        ) );
        
        wp_reset_query();
        
        $pagination = $root->appendChild( 
            $symbiostock_xml->createElement( "pagination" ) );
        
        if(!empty($symbiostock_search_pagination)){
            
            foreach($symbiostock_search_pagination as $page){
                        
                $pagination->appendChild( 
                    $symbiostock_xml->createElement( "page",htmlentities($page) ) );
                        
                }
            
            }
        }
    } else {
        
        $noResults = $root->appendChild( 
            $symbiostock_xml->createElement( "no_results", __('No results found.', 'symbiostock') ) );
        
        }
        
        $symbiostock_network_info = get_query_var('symbiostock_network_info');
        
        if(isset($symbiostock_network_info)){
        
        $network_info_array = symbiostock_network_info();    
        
        $network_info = $root->appendChild( 
        $symbiostock_xml->createElement( "network_info" ) );
        
        foreach($network_info_array as $key => $value){
            $network_info->appendChild( 
            $symbiostock_xml->createElement( $key, ssde($value) ) );        
            
            }
            get_site_url();
            
            $network_info->appendChild( 
            $symbiostock_xml->createElement( 'url', ssde(get_site_url()) ) );            
        }
        
    //make the output pretty
    $symbiostock_xml->formatOutput = true;
    return $symbiostock_xml->saveXML();
}
//this simulates "generic" results for special cases, like when a site does not respond in time and 
//results must be substituted
function symbiostock_xml_generic_results($url, $site=''){
    
    $meta_values = array(
         'collection_img'           => '#',
        'live'                      => '#',
        'price_bloggee'             => '#',
        'price_small'               => '#',
        'price_medium'              => '#',
        'price_large'               => '#',
        'price_vector'              => '#',
        'price_zip'                 => '#',
        'locked'                    => '#',
        'discount_percent'          => '#',
        'exclusive'                 => '#',
        'symbiostock_minipic'       => symbiostock_IMGDIR . '/generic-user.jpg',
        'symbiostock_preview'       => '#',
        'symbiostock_transparency'  => '#',
        'size_eps'                  => '#',
        'size_zip'                  => '#'
    );
    
    $meta_values_serialized = array(
         'extensions',
        'collections',
        'related_images',
        'size_info' 
    );
            
    //create the xml document
    $symbiostock_xml = new DOMDocument();    
    
    $root = $symbiostock_xml->appendChild( $symbiostock_xml->createElement( "symbiostock_search_results" ) );
    
        //create a image element
        $totalResults = $root->appendChild( 
            $symbiostock_xml->createElement( "total_results", $wp_query->found_posts ) );

        //create "directive" tags that will say this is a generic response, so initiate something...
        
        //create the permalink element
        $totalResults = $root->appendChild( 
            $symbiostock_xml->createElement( "load_ajax", 1 ) );

        $retry_search = $root->appendChild( 
            $symbiostock_xml->createElement( "retry_search", ssde( $url ) ) );    
    
        $network_info = $root->appendChild( 
            $symbiostock_xml->createElement( "network_info" ) );
            
            $network_info->appendChild( 
                $symbiostock_xml->createElement( 'url', $site ) );                    
            
        //----------------------------------------------
                
        //create a image element
        $imageTag = $root->appendChild( 
            $symbiostock_xml->createElement( "image" ) );
        $author = '#';

        //create the permalink element
        $imageTag->appendChild( 
            $symbiostock_xml->createElement( "permalink", '#' ) );
        
        //create the id element
        $imageTag->appendChild( 
            $symbiostock_xml->createElement( "id", '0' ) );
        
        //title -> basic verbage for preview
        $imageTag->appendChild( 
            $symbiostock_xml->createElement( "title", 'Symbiostock' ) );
        
        //loop through our meta values to build the elements
        
        foreach ( $meta_values as $name => $value ) {
                        
            $imageTag->appendChild( 
                $symbiostock_xml->createElement( $name, $value ) );
        
            
        }
        

        //create the author element
        $imageTag->appendChild( 
            $symbiostock_xml->createElement( "author", '#' ) );
            
        //create the if this is a network search element
        $imageTag->appendChild( 
            $symbiostock_xml->createElement( "network", '#' ) );                            

        
    //make the output pretty
    $symbiostock_xml->formatOutput = true;
    
    return $symbiostock_xml->saveXML();
}
?>