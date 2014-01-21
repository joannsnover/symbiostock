<?php
function create_image_collection_taxonomies() {
    // Add new taxonomy, make it hierarchical (like categories)
    $labels = array(
        'name'              => _x( 'Collections', 'taxonomy general name', 'symbiostock' ),
        'singular_name'     => _x( 'Collection', 'taxonomy singular name', 'symbiostock' ),
        'search_items'      => __( 'Search Collections', 'symbiostock' ),
        'all_items'         => __( 'All Collections', 'symbiostock'  ),
        'parent_item'       => __( 'Parent Collection', 'symbiostock'  ),
        'parent_item_colon' => __( 'Parent Collection:', 'symbiostock'  ),
        'edit_item'         => __( 'Edit Collection', 'symbiostock'  ),
        'update_item'       => __( 'Update Collection', 'symbiostock'  ),
        'add_new_item'      => __( 'Add New Collection', 'symbiostock'  ),
        'new_item_name'     => __( 'New Collection Name', 'symbiostock'  ),
        'menu_name'         => __( 'Collection', 'symbiostock'  ),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'Collection' ),
    );

    register_taxonomy( 'Collection', array( 'image' ), $args );

}
?>