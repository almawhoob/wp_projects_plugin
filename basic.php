<?php
/*
 * Plugin name: Basic
 * Plugin URI: http://almawhoob.net
 * Author: Ahmed Al Abdulmohsen
 * Author URI: http://almawhoob.net
 * Version: 0.0.4
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
                        //    'supports' => array( 'title', 'editor', 'excerpt', 'custom-fields', 'thumbnail','page-attributes' ),
                           'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
                           'has_archive' => true,
                           'exclude_from_search' => false,
                           'rewrite'     => ['slug' => 'projects'], // my custom slug
                            
                       ]
    );
    
}
add_action('init', 'wppp_project_custom_post_type');


/* Creates Taxonomy for Project Categories */
function wporg_register_taxonomy_project_category(){
    $labels = [
        'name'              => _x('Project Categories', 'taxonomy general name'),
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
    add_meta_box( 'project-information-meta-box', __( 'Project Information', 'textdomain' ), 'wppp_project_info_display_callback', 'wppp_project', 'side' );
}
add_action( 'add_meta_boxes', 'wppp_register_meta_boxes' );
 
/**
 * Meta box display callback.
 *
 * @param WP_Post $post Current post object.
 */
function wppp_project_info_display_callback( $post ) {
    // Display code/markup goes here. Don't forget to include nonces!
     
    $values = get_post_custom( $post->ID );
    $p_startdate = isset( $values['project_startdate'] ) ? esc_attr( $values['project_startdate'][0] ) : ”;
    $p_enddate = isset( $values['project_enddate'] ) ? esc_attr( $values['project_enddate'][0] ) : ”;
    $p_max_members = isset( $values['project_max_members'] ) ? esc_attr( $values['project_max_members'][0] ) : ”;

    // $selected = isset( $values['my_meta_box_select'] ) ? esc_attr( $values['my_meta_box_select'][0] ) : ”;
    // $check = isset( $values['my_meta_box_check'] ) ? esc_attr( $values['my_meta_box_check'][0] ) : ”;
    
    // We'll use this nonce field later on when saving.
    wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );
    ?>

         <p>
            <label for="project_startdate">Project Start Date</label>
            <input type="date" name="project_startdate" id="project_startdate" placeholder="YYYY-MM-DD" value="<?php echo $p_startdate; ?>"/>
        </p>
        <p>
            <label for="project_enddate">Project End Date</label>
            <input type="date" name="project_enddate" id="project_enddate" placeholder="YYYY-MM-DD" value="<?php echo $p_enddate; ?>"/>
        </p>
        <p>
            <label for="project_max_members">Max Number of Participants</label>
            <input type="number" min="0" name="project_max_members" id="project_max_members" value="<?php echo $p_max_members; ?>"/>
        </p> 
    <?php    
}


function cd_meta_box_save( $post_id ) {
    // Bail if we're doing an auto save
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
     
    // if our nonce isn't there, or we can't verify it, bail
    if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;
     
    // if our current user can't edit this post, bail
    if( !current_user_can( 'edit_post' ) ) return;

    // now we can actually save the data
    $allowed = array( 
        'a' => array( // on allow a tags
            'href' => array() // and those anchors can only have href attribute
        )
    );
     
    // Make sure your data is set before trying to save it
    if( isset( $_POST['project_startdate'] ) )
        update_post_meta( $post_id, 'project_startdate', wp_kses( $_POST['project_startdate'], $allowed ) );

    if( isset( $_POST['project_enddate'] ) )
        update_post_meta( $post_id, 'project_enddate', wp_kses( $_POST['project_enddate'], $allowed ) );

    if( isset( $_POST['project_max_members'] ) )
        update_post_meta( $post_id, 'project_max_members', wp_kses( $_POST['project_max_members'], $allowed ) );        
         
    // if( isset( $_POST['my_meta_box_select'] ) )
    //     update_post_meta( $post_id, 'my_meta_box_select', esc_attr( $_POST['my_meta_box_select'] ) );
         
    // This is purely my personal preference for saving check-boxes
    // $chk = isset( $_POST['my_meta_box_check'] ) && $_POST['my_meta_box_select'] ? 'on' : 'off';
    // update_post_meta( $post_id, 'my_meta_box_check', $chk );
}
add_action( 'save_post', 'cd_meta_box_save' );
 
/**
 * Save meta box content.
 *
 * @param int $post_id Post ID
 */
function wppp_save_meta_box( $post_id ) {
    // Save logic goes here. Don't forget to include nonce checks!
}
add_action( 'save_post', 'wppp_save_meta_box' );









    // Register and load the widget
    function wpb_load_widget() {
        register_widget( 'wpb_widget' );
    }
    add_action( 'widgets_init', 'wpb_load_widget' );

    // Creating the widget 
    class wpb_widget extends WP_Widget {

    function __construct() {
        parent::__construct(

        // Base ID of your widget
        'wpb_widget', 

        // Widget name will appear in UI
        __('WPPP Project Info Widget', 'wpb_widget_domain'), 

        // Widget description
        array( 'description' => __( 'WPPP Project Info widget displays project information on theme.', 'wpb_widget_domain' ), ) 
        );
    }

    // Creating widget front-end
    public function widget( $args, $instance ) {
        if ( is_single() ) {
            // making sure that the post type is "wppp_project"
            if ('wppp_project' === get_post_type()) {
            $title = apply_filters( 'widget_title', $instance['title'] );

            // before and after widget arguments are defined by themes
            echo $args['before_widget'];
            if ( ! empty( $title ) )
                echo $args['before_title'] . $title . $args['after_title'];

            // This is where you run the code and display the output
            // echo __( 'Hello, World!', 'wpb_widget_domain' );
            
            // get post id
            $queried_object = get_queried_object();
            if ( $queried_object ) {
                $post_id = $queried_object->ID;
                // echo $post_id;
            }

            if ('wppp_project' === get_post_type()) {
                // project start date 
                echo '<p>Project Starts: <br/><strong>';
                echo get_post_meta($post_id, 'project_startdate', true);
                echo '</strong><br/><br/>';
                // project end date
                echo 'Project Ends: <br/><strong>'; 
                echo get_post_meta($post_id, 'project_enddate', true); 
                echo '</strong><br/>';
                // maximum number of participants
                echo 'Max number of participants: <br/><strong>';
                echo get_post_meta($post_id, 'project_max_members', true);
                echo '</strong></p>';
            };
            echo $args['after_widget'];
            }//end of if-statement for wppp_project
        }
    }


    // Widget Backend 
    public function form( $instance ) {
        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        } else {
            $title = __( 'Project Information Widget', 'wpb_widget_domain' );
        }

    // Widget admin form
        ?>
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
            </p>
        <?php 
    }
        
    // Updating widget replacing old instances with new
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        return $instance;
    }
} // Class wpb_widget ends here