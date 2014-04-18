<?php


class QuakeZipRatesAdmin {
 
    public function __construct() {
		add_action( 'add_meta_boxes', array($this, 'abbreviation_box' ));
		add_action( 'save_post', array($this, 'abbreviation_box_save') );
		add_action( 'add_meta_boxes', array($this, 'rates_box') );
		add_action( 'save_post', array($this, 'rates_box_save') );
    }
	
	
	// this file holds the rate box and zip code association creation and saving functions for the admin utilities page.


	// create the meta-box on the Utility page for choosing the abbreviation associated with zip codes.
	public function abbreviation_box() {
		add_meta_box( 
			'abbreviation_box',
			__( 'Utility Abbreviation', 'qzu_textdomain' ),
			array($this, 'abbreviation_box_content'),
			'utility',
			'side',
			'low'
		);
	}
	

	// create the content for the metabox for utility/zipcode associations.
	public function abbreviation_box_content($utility)	{
		$abbreviation = get_post_meta( $utility->ID, 'abbreviation', true );
		
		wp_nonce_field( plugin_basename( __FILE__ ), 'abbreviation_box_content_nonce' );
		
		//get all abbreviations that are available from the zipcode associations.
		$currentzips=get_option("qzu_zipcodes_assoc");
		$allabbv=Array();
		foreach ($currentzips as $k=>$v){
			if(!in_array($v,$allabbv)){
				$allabbv[]=$v;
			}
		}
		
		echo '<p>This is used to match the utility to the zip code associations. You must associate the abbreviation with at least 1 zip code before you can select it here. If you change abbreviations in the zip code file, you must reselect the abbreviation here.</p><label for="abbreviation"></label>';
		echo '<select id="abbreviation" name="abbreviation">';
		echo '<option value="">Select Abbreviation</option>';
		//display all of the abbreviations.
		foreach($allabbv as $k=>$v){
			echo '<option value="'.$v.'"';
			if($v==$abbreviation){
				echo ' selected="selected"';
			}
			echo '>'.$v.'</option>';
		}
		
		echo '</select>';
	}

	// handles saving the meta-box information for the zip code associations.
	public function abbreviation_box_save( $post_id ) {

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
		return;

		if ( !wp_verify_nonce( $_POST['abbreviation_box_content_nonce'], plugin_basename( __FILE__ ) ) )
		return;

		if ( 'page' == $_POST['post_type'] ) {
			if ( !current_user_can( 'edit_page', $post_id ) )
			return;
		} else {
			if ( !current_user_can( 'edit_post', $post_id ) )
			return;
		}
		$abbreviation = $_POST['abbreviation'];
		update_post_meta( $post_id, 'abbreviation', $abbreviation );
	}
	







	/////////////////////////////// create the meta-box on the Utility page for choosing the rates
	public function rates_box() {
		add_meta_box( 
			'rates_box',
			__( 'Rates', 'qzu_textdomain' ),
			array($this, 'rates_box_content'),
			'utility',
			'advanced',
			'default'
		);
	}
	
	// create the content for the metabox for rates.
	public function rates_box_content($utility)	{
		$rates = get_post_meta( $utility->ID, 'rates', true );
		
		wp_nonce_field( plugin_basename( __FILE__ ), 'rates_box_content_nonce' );
		
		//create 10 rate groups.  change for more. also change in rates_box_save function.
		for ($i = 1; $i <= 10; $i++) { 
		?>	
			<div id='ratebox-<?php echo $i; ?>' style="display:none;background:white;border:1px solid #ccc;padding:10px;margin-bottom:8px;border-radius:3px 3px;">
			<p style="float:right;"><a href="#" id="<?php echo $i; ?>_removerate">Remove Rate #<?php echo $i; ?></a></p>
			<h4>Rate #<?php echo $i; ?></h4>
			<table style='width:100%; '>
				<tr>
					<td>Rate Type</td>
					<td>
						<select name='ratetype-<?php echo $i; ?>' id='ratetype-<?php echo $i; ?>'>
							<option value=''>Select Type</option>
							<option <?php if($rates!="" && $rates[$i]['type']=='fixed'){?>selected='selected'<?php } ?> value='fixed'>Fixed</option>
							<option <?php if($rates!="" && $rates[$i]['type']=='variable'){?>selected='selected'<?php } ?> value='variable'>Variable</option>
						</select>
					</td>
					<td>Rate Value</td>
					<td><input type='text' name='ratevalue-<?php echo $i; ?>' id='ratevalue-<?php echo $i; ?>' value='<?php if($rates!="" && $rates[$i]['value']!=""){echo $rates[$i]['value']; } ?>' /></td>
				</tr>
				<tr>
					<td>Rate Description</td>
					<td colspan='3'><textarea style='width:100%;' name='ratedesc-<?php echo $i; ?>' id='ratedesc-<?php echo $i; ?>'><?php if($rates!="" && $rates[$i]['desc']!=""){echo $rates[$i]['desc']; } ?></textarea></td>
				</tr>
				</table>
				<h4>Download Forms</h4>
				<table>
				<?php for ($n = 1; $n <= 3; $n++) { // 3 files per rate. change for more. also change in rates_box_save function. ?>
					<tr>
						<td>Label for File <?php echo $n;?></td>
						<td><input type='text' name='ratelabel<?php echo $n;?>-<?php echo $i; ?>' id='ratelabel<?php echo $n;?>-<?php echo $i; ?>' value='<?php if($rates!="" && $rates[$i]['label'.$n]!=""){echo $rates[$i]['label'.$n]; } ?>' /></td>
						<td>File <?php echo $n;?></td>
						<td>
							<input id="ratefile<?php echo $n;?>-<?php echo $i; ?>" type="text" size="36" name="ratefile<?php echo $n;?>-<?php echo $i; ?>" value="<?php if($rates!="" && $rates[$i]['file'.$n]!=""){echo $rates[$i]['file'.$n]; } ?>" /> 
							<input id="ratefile<?php echo $n;?>-<?php echo $i; ?>_button" class="button" type="button" value="Upload File" />
							Enter a URL or upload a file
						</td>
					</tr>
				<?php } ?>
			</table>
			</div>
		<?php 
		}
		?>
		<p ><a href="#" id="addrate">Add a Rate</a></p>
		<?php 
	}

	// handles saving the meta-box information for the rates.
	public function rates_box_save( $post_id ) {

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
		return;

		if ( !wp_verify_nonce( $_POST['rates_box_content_nonce'], plugin_basename( __FILE__ ) ) )
		return;

		if ( 'page' == $_POST['post_type'] ) {
			if ( !current_user_can( 'edit_page', $post_id ) )
			return;
		} else {
			if ( !current_user_can( 'edit_post', $post_id ) )
			return;
		}
		
		// up to 10 rates. change for more.
		for ($i = 1; $i <= 10; $i++) {
			$ratetype='ratetype-'.$i;
			$ratevalue='ratevalue-'.$i;
			$ratedesc='ratedesc-'.$i;
			
			$rates[$i]['type']=$_POST[$ratetype];
			$rates[$i]['value']=$_POST[$ratevalue];
			$rates[$i]['desc']=$_POST[$ratedesc];
			
			// up to 3 files per rate. change for more.
			for($n = 1; $n <= 3; $n++){
				$rates[$i]['label'.$n]=$_POST['ratelabel'.$n.'-'.$i];
				$rates[$i]['file'.$n]=$_POST['ratefile'.$n.'-'.$i];
				
			}
		}
		
		
		update_post_meta( $post_id, 'rates', $rates );
	}
	
 
}

