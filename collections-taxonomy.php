<?php
function create_image_collection_taxonomies() {
    // Add new taxonomy, make it hierarchical (like categories)
    $labels = array(
        'name'              => _x( 'Collections', 'taxonomy general name' ),
        'singular_name'     => _x( 'Collection', 'taxonomy singular name' ),
        'search_items'      => __( 'Search Collections' ),
        'all_items'         => __( 'All Collections' ),
        'parent_item'       => __( 'Parent Collection' ),
        'parent_item_colon' => __( 'Parent Collection:' ),
        'edit_item'         => __( 'Edit Collection' ),
        'update_item'       => __( 'Update Collection' ),
        'add_new_item'      => __( 'Add New Collection' ),
        'new_item_name'     => __( 'New Collection Name' ),
        'menu_name'         => __( 'Collection' ),
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