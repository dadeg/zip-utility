<?php
class QuakeZipCSVUpload {
 
    public function __construct() {
		
		add_action('admin_menu', array($this, 'qzu_admin_actions'));
		add_action( 'admin_init', array($this, 'register_qzusettings') );
    }
	
	// this handles all of the zip code uploading and csv handling

	// Add submenu to Utilities area for options
	public function qzu_admin_actions() {
		add_submenu_page('edit.php?post_type=utility', 'Zip Codes', 'Zip Codes', 'administrator', 'qzu_zipcodes', array($this, 'qzu_zipcodes_admin')); 
		add_submenu_page('edit.php?post_type=utility', 'General Options', 'General Options', 'administrator', 'qzu_general_options', array($this, 'qzu_general_options_admin')); 
		
	}
	

	// Register the options. Namely the zip code and utility matching. Also the general options page
	public function register_qzusettings() { // whitelist options
	  
	  register_setting('qzu_options', 'qzu_zipcodes_assoc', array($this, 'qzu_csv_handler'));
	  add_settings_section('main_section', 'Zip Code Settings', array($this, 'section_cb'), 'qzu_zipcodes');
	  add_settings_field('zipfile', 'Zip Codes File:', array($this, 'csv_setting'), 'qzu_zipcodes', 'main_section'); // file upload
	  
	  
	  register_setting('qzu_general_optionsgroup', 'qzu_options_rate_submit_url', array($this, 'rate_submit_url_sani'));
	  register_setting('qzu_general_optionsgroup', 'qzu_options_startover_submit_url', array($this, 'startover_url_sani'));
	  add_settings_section('general_options_section', '', array($this, 'section_cb'), 'qzu_general_options');
	  add_settings_field('qzu_options_rate_submit_url', 'URL to redirect to after user chooses a rate and clicks "Enroll": ', array($this, 'rate_submit_setting'), 'qzu_general_options', 'general_options_section'); // file upload
	  add_settings_field('qzu_options_startover_submit_url', 'URL to redirect to if a user wants to start over from beginning: ', array($this, 'rate_startover_setting'), 'qzu_general_options', 'general_options_section'); // file upload
	  
	  }
	


	//////////// This is for the zip codes page.
	// This is what handles converting the CSV file to an array to store in the option "qzu_zipcodes_assoc"
	public function qzu_csv_handler($plugin_options)	{
		$keys = array_keys($_FILES); 
		$i = 0;
		foreach ( $_FILES as $csv ) {   
			// if a files was upload  
			if (($handle = fopen($csv["tmp_name"], "r")) !== FALSE && strpos($csv["name"], '.csv') !== FALSE) {
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					
					$zipassoc[$data[0]]=$data[1];
					
				}
				fclose($handle);
			} else {
				// Not a csv.        
					
				// Die and let the user know that they made a mistake.       
				wp_die('Improper .csv was uploaded.');
			}
			
			$i++; 
		} 
		return $zipassoc;
	}

	// Callback function for form fields section
	public function section_cb() {}

	// Handles the display of the zip code file upload field.
	public function csv_setting()	{
		echo '<input type="file" name="zipfile" />';
	}


	// Create the zip codes options page that the submenu links to.
	function qzu_zipcodes_admin() {
		echo '<div class="wrap"><div class="icon32" id="icon-tools"> <br /> </div><h2>Zip Code & Utility Associations</h2><p>Upload a Comma Separated Values (.csv) file with each line containing a single zip code and a single utility abbreviation. This abbreviation will show up in the "Utilities" area to associate a Utility page with each zip code.</p><p>Example:</p><pre>78704,COH<br>12345,ABC<br>54321,ABC<br>etc.</pre>';
		echo '<form method="post" action="options.php" enctype="multipart/form-data">';  
		settings_fields('qzu_options');
		do_settings_sections('qzu_zipcodes');
		submit_button();
		echo '</form>';
		
		
		echo '<h4>Current Zip Code Associations</h4>';
		echo '<pre>';
		$currentzips=get_option("qzu_zipcodes_assoc");
		foreach ($currentzips as $k=>$v){
			echo $k.", ".$v."<br />";
		}
		echo '</pre>';
		
		
	}






	//////////////// Below is the general options

	// Create the zip codes options page that the submenu links to.
	function qzu_general_options_admin() {
		echo '<div class="wrap"><div class="icon32" id="icon-tools"> <br /> </div><h2>General Options</h2><p>This must be set in order for the plugin to work properly.</p><p>The Enroll URL is the page that has the gravity form that the user will enroll with. The start over URL is a page that you need to make that has the zip code widget on it.</p><p>The URL must begin with a "/". Examples: /, /home, /blog/post1, /?page=123, etc.';
		echo '<form method="post" action="options.php">';  
		settings_fields('qzu_general_optionsgroup');
		do_settings_sections('qzu_general_options');
		submit_button();
		echo '</form>';
		
	}

	// Handles the display of the text field for url to go to after clicking enroll on the utility page
	public function rate_submit_setting()	{
		echo '<input type="text" name="rate_submit_url"  value="'.get_option('qzu_options_rate_submit_url').'"/><br>Example: /form-page';
	}

	// Handles the display of the text field for url to startover
	public function rate_startover_setting()	{
		echo '<input type="text" name="rate_startover_url"  value="'.get_option('qzu_options_startover_submit_url').'"/><br>Example: /form-page';
	}

	// This is what handles converting the CSV file to an array to store in the option "qzu_zipcodes_assoc"
	public function rate_submit_url_sani($plugin_options)	{
		$keys = array_keys($_FILES); 
		
		return $_POST['rate_submit_url'];
	}

	// This is what handles converting the CSV file to an array to store in the option "qzu_zipcodes_assoc"
	public function startover_url_sani($plugin_options)	{
		$keys = array_keys($_FILES); 
		
		return $_POST['rate_startover_url'];
	}
 
}
