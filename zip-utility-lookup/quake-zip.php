<?php 
/*
Plugin Name: Quake Zip Code Utilities2
Plugin URI: http://www.example.com
Description: Plugin for using Zip Codes to find utilities and rates.
Author: Dan DeGreef
Version: 1.0
Author URI: http://www.example.com
*/


include('quake-zip-csv-upload.php');

include('quake-zip-rates-admin.php');

include('quake-zip-widget.php');

include('quake-zip-submit-handlers.php');

include('quake-zip-enroll-form-alteration.php');

class QuakeZip {
 
		private $quakezipcsvupload;
		private $quakezipratesadmin;
		private $quakezipsubmithandlers;
		private $quakezipenrollformalteration;
 
    public function __construct() {
		$quakezipcsvupload = new QuakeZipCSVUpload;
		$quakezipratesadmin = new QuakeZipRatesAdmin;
		
		add_action( 'widgets_init', create_function('', 'return register_widget("QZUwidget");') );
		
		$quakezipsubmithandlers = new QuakeZipSubmitHandlers;
		$quakezipenrollformalteration = new QuakeZipEnrollFormAlteration;
		
		add_action( 'init', array($this, 'qzu_utilities_post' ));
		add_action('admin_enqueue_scripts', array($this, 'qzu_admin_scripts'));
		
		
		
		/* Filter the single_template with our custom function. this is for the display of the utility page for the user. */
		add_filter('single_template', array($this, 'qzu_utility_template'));
    }
	
	// Create Utilities area
	public function qzu_utilities_post() {
		$labels = array(
			'name'               => _x( 'Utilities', 'post type general name' ),
			'singular_name'      => _x( 'Utility', 'post type singular name' ),
			'add_new'            => _x( 'Add New', 'book' ),
			'add_new_item'       => __( 'Add New Utility' ),
			'edit_item'          => __( 'Edit Utility' ),
			'new_item'           => __( 'New Utility' ),
			'all_items'          => __( 'All Utilities' ),
			'view_item'          => __( 'View Utility' ),
			'search_items'       => __( 'Search Utilities' ),
			'not_found'          => __( 'No utilities found' ),
			'not_found_in_trash' => __( 'No utilities found in the Trash' ), 
			'parent_item_colon'  => '',
			'menu_name'          => 'Utilities'
		);
		$args = array(
			'labels'        => $labels,
			'description'   => 'Holds our utilities and utility specific rate data',
			'public'        => true,
			'menu_position' => 17,
			'supports'      => array( 'title', 'editor' ),
			//'rewrite' => false,
			'has_archive'   => true
		);
		register_post_type( 'utility', $args );	
	}
	
	public function qzu_admin_scripts() {
        wp_enqueue_media();
        wp_register_script('qzu-js', WP_PLUGIN_URL.'/quake-zip/quake-zip.js', array('jquery'));
        wp_enqueue_script('qzu-js');
	}
	
	public function qzu_utility_template($single) {
		global $wp_query, $post;
		
		/* Checks for single template by post type */
		if ($post->post_type == "utility" && strpos($single,"single-utility.php")===false ){
			if(file_exists(dirname(__FILE__). '/single-utility.php')) {
				return dirname(__FILE__) . '/single-utility.php';
			}
		}	
	
		return $single;
	}
 
}

$quakezip = new QuakeZip;



?>