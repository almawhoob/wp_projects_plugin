<?php
/*
 * Plugin name: Basic
 * Plugin URI: http://almawhoob.net
 * Author: Ahmed Al Abdulmohsen
 * Author URI: http://almawhoob.net
 * Version: 0.0.1
 * Description: This is my first wordpress plugin!
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

function building_blocks_dashboard_widgets() {
    //... do something!
    wp_add_dashboard_widget(
                 'building_blocks_widget',         // Widget slug.
                 'Building Blocks Widget',         // Title.
                 'add_building_blocks_dashboard_widget_function' // Display function.
        );	
}
add_action( 'wp_dashboard_setup', 'building_blocks_dashboard_widgets' );

/**
 * Create the function to output the contents of our Dashboard Widget.
 */
function add_building_blocks_dashboard_widget_function() {

	// Display whatever it is you want to show.
	echo "Hello World, I'm the First Building Blocks Widget =)";
}



// Create the function to use in the action hook
function example_remove_dashboard_widget() {
 	remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
} 
 
// Hook into the 'wp_dashboard_setup' action to register our function
add_action('wp_dashboard_setup', 'example_remove_dashboard_widget' );


// Adds the wp_projects_plugin link to the admin bar menu
function wppp_add_plugin_link_to_admin_bar_menu($wp_admin_bar) {
    // global $wp_admin_bar;
    // var_dump($wp_admin_bar);
        $args = array(
        'id'    => 'wppp_projects',
        'title' => 'WPPP Projects',
        'href' => '#',
        'meta'  => array(
            'class' => 'ab-item'
        ),
    );
    $wp_admin_bar->add_node( $args );    
 
    $args = array();
 
    array_push( $args,array(
        'id'     => 'twitter',
        'title'  => __( 'Twitter', 'textdomain' ),
        'href'   => 'http://www.twitter.com',
        'parent' => 'social_media',
    ) );

     array_push( $args,array(
        'id'     => 'youtube',
        'title'  => __( 'YouTube', 'textdomain' ),
        'href'   => 'http://www.YouTube.com',
        'parent' => 'social_media',
        'meta'   => array(
            'class' => 'first-toolbar-group'
        ),
    ) );
 
    array_push( $args,array(
        'id'     => 'fb',
        'title'  => __( 'Facebook', 'textdomain' ),
        'href'   => 'http://www.facebook.com',
        'parent' => 'social_media',
    ) );
     
    sort( $args );
 
    for ( $a=0; $a < sizeOf( $args ); $a++ ) {
        $wp_admin_bar->add_node( $args[ $a ] );
    }

}
add_action( 'admin_bar_menu', 'wppp_add_plugin_link_to_admin_bar_menu', '80' ); // I put priority=80 to add iteam at the end 



/* Creates a new post type 'wppp_project' (Projects) */
function wppp_project_custom_post_type() {
    register_post_type('wppp_project',
                       [
                           'labels' => [
                               'name'          => __('Projects'),
                               'singular_name' => __('Projects'),
                           ],
                           'public'      => true,
                           'has_archive' => true,
                           'rewrite'     => ['slug' => 'projects'], // my custom slug
                            
                       ]
    );
    
}
add_action('init', 'wppp_project_custom_post_type');


/* Creates Taxonomy for Project Categories */
function wporg_register_taxonomy_project_category(){
    $labels = [
        'name'      => _x('Project Categories', 'taxonomy general name'),
        'singular_name'     => _x('Project Categories', 'taxonomy singular name'),
        'search_items'      => __('Search Project Categories'),
        'all_items'         => __('All Categories'),
        'parent_item'       => __('Parent Project Category'),
        'parent_item_colon' => __('Parent Project Category:'),
        'edit_item'         => __('Edit Project Category'),
        'update_item'       => __('Update Project Category'),
        'add_new_item'      => __('Add New Project Category'),
        'new_item_name'     => __('New Project Category Name'),
        'menu_name'         => __('Project Categories'),
    ];
    $args = [
        'hierarchical'      => true, // make it hierarchical (like categories)
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'project_category'],
    ];
    register_taxonomy('project_category', ['wppp_project'], $args);
}
add_action('init', 'wporg_register_taxonomy_project_category');


/* Adds custom fields to projects */
// function wppp_add_custom_fields( ) {
//     add_post_meta( $post_id, $meta_key, $meta_value, $unique = false );
//     // the stupidity of your action is proposional to the number of people watching you.
// }
// add_action('init', 'wppp_add_custom_fields');




/**
 * Register meta box(es).
 */
function wppp_register_meta_boxes() {
    add_meta_box( 'meta-box-id', __( 'Project Information', 'textdomain' ), 'wppp_my_display_callback', 'wppp_project' );
}
add_action( 'add_meta_boxes', 'wppp_register_meta_boxes' );
 
/**
 * Meta box display callback.
 *
 * @param WP_Post $post Current post object.
 */
function wppp_my_display_callback( $post ) {
    // Display code/markup goes here. Don't forget to include nonces!
    echo'Project Start Date: <input type="date" name="project_startdate" required>';
    echo'Project End Date: <input type="date" name="project_enddate" required>';
}
 
/**
 * Save meta box content.
 *
 * @param int $post_id Post ID
 */
function wppp_save_meta_box( $post_id ) {
    // Save logic goes here. Don't forget to include nonce checks!
}
add_action( 'save_post', 'wppp_save_meta_box' );
