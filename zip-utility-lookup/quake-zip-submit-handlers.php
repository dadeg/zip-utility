<?php

class QuakeZipSubmitHandlers {
 
    public function __construct() {
		// attempt to make a session for passing the utility and zip information.
		add_action('init', array($this, 'myStartSession'), 1);
		add_action('wp_logout', array($this, 'myEndSession'));
		add_action('wp_login', array($this, 'myEndSession'));
		
		add_action('init', array($this, 'qzu_zip_submit'));
		add_action('init', array($this, 'qzu_zip_enter'));
		add_action('init', array($this, 'qzu_rate_submit'));
    }
	
	//this is where the redirect rules are kept.

	

	public function myStartSession() {
		if(!session_id()) {
			session_start();
		}
	}

	public function myEndSession() {
		session_destroy ();
	}


	// this takes the zip code that was submitted and tries to find a utility associated with it. it redirects to the utility if found.
	public function qzu_zip_submit() {
		
		if ( isset( $_GET['action'] ) && $_GET['action'] == 'qzuzipsubmit' ) {
			
			check_admin_referer( 'zip-submit' ); // die if invalid or missing nonce
			
			if(!session_id()) {
				session_start();
			}
			$currentzips = get_option("qzu_zipcodes_assoc");
			
			$utility = $currentzips[$_POST['zip']];

			// if the zip code is not supported, kick them back with an error.
			if($utility === NULL){
				if(strpos(get_option('qzu_options_startover_submit_url'),'?')){
					$tidbit="&";
				}else{
					$tidbit="?";
				}
				header("Location: ".get_option('qzu_options_startover_submit_url').$tidbit."qzue=1#zip");
				exit;
			}

			// find the utility 
			$args = array(
			'post_type' => 'utility',
				'posts_per_page' => 10,
				'orderby' => 'meta_value_num',
				'order' => 'ASC',
				'meta_query' => array(
							'relation' => 'AND',
					array(
							'key' => 'abbreviation',
							'value' => $utility,

					)
				 )
			);

			$query = new WP_Query( $args );
			if(count($query->posts)>0){
				$_SESSION['zipcode']=$_POST['zip'];
				$_SESSION['abbreviation']=$utility;
				// send them to the url of that utility. In the case that there are more than 1 utility with that abbreviation, it just chooses the first one. this is unpredictable so only having 1 utility assigned to each abbreviation is suggested.
				header("Location: /?utility=".$query->post->post_name);
				exit;
			} else {
				if(strpos(get_option('qzu_options_startover_submit_url'),'?')){
					$tidbit="&";
				}else{
					$tidbit="?";
				}
				header("Location: ".get_option('qzu_options_startover_submit_url').$tidbit."?qzue=1#zip");
				exit;
			}

			
		}
	}
	



	// function for choosing a zipcode. empty page with 'enter zip'. This is used to send a user back to the beginning to choose a zipcode.
	public function qzu_zip_enter() {
		
		if ( isset( $_GET['action'] ) && $_GET['action'] == 'qzuzipenter' ) {
			
			check_admin_referer( 'zip-enter' ); // die if invalid or missing nonce
			
			header("Location: ".get_option('qzu_options_startover_submit_url')."#zip");
				exit;
			
			
			
		}
	}
	


	// if a user chooses a rate and submits the form on the utilities page, redirect with this function.
	public function qzu_rate_submit() {
		
		if ( isset( $_GET['action'] ) && $_GET['action'] == 'qzuratesubmit' ) {
			check_admin_referer( 'rate-submit' ); // die if invalid or missing nonce
			if(!session_id()) {
				session_start();
			}
			//forward to the appropriate page for filling out the form.
			$_SESSION['rate']=$_POST['rate'];
			$_SESSION['utility']=$_POST['utility'];

			if(strpos(get_option('qzu_options_rate_submit_url'),'?')){
				$tidbit="&";
			}else{
				$tidbit="?";
			}
			header("Location: ".get_option('qzu_options_rate_submit_url').$tidbit."qzu-zip=".$_SESSION['zipcode']."&qzu-utility=".$_SESSION['abbreviation']."&qzu-rate=".$_SESSION['rate']);
			exit;
			
		}
	}
	
}

