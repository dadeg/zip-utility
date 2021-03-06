<?php

class QuakeZipEnrollFormAlteration {
 
    public function __construct() {
		add_filter('gform_pre_render_1', array($this, 'qzu_populate_utilities'));
		
		//commented out because gravity forms does not allow for dynamic form field and option creation.
		//add_filter('gform_pre_render_1', array($this, 'qzu_reorder_rates'));
    }
	
	// update the '1' in gform_pre_render_1 to the ID of your form
	
	public function qzu_populate_utilities($form){
		
		foreach($form['fields'] as &$field){
			
			
			if($field['type'] != 'select' || strpos($field['cssClass'], 'utility-select') === false)
				continue;
			
			// you can add additional parameters here to alter the posts that are retreieved
			// more info: http://codex.wordpress.org/Template_Tags/get_posts
			$posts = get_posts('post_type=utility&numberposts=-1&post_status=publish');
			
			// update 'Select a Post' to whatever you'd like the instructive option to be
			$choices = array(array('text' => 'Select Your Utility', 'value' => ''));
			
			foreach($posts as $post){
				// add the utility to the select option
				$choices[] = array('text' => $post->post_title, 'value' => $post->abbreviation); // use $post->abbreviation for the zip code abbreviation instead. or $post->post_title for the readable name.
				
				/*/ also add its rates as a new select dropdown that only shows if the utility is selected.
				//duplicate the utility select field to get the array
				$utilityrateselect = $field;
				
				//change the options to be rate specific
				
				$utilityrateselect['cssClass']="rate-select";
				$utilityrateselect['id']=$utilityrateselect['id']+10000+$post->ID; // make an id that wil never interfere with other fields in this form.
				$utilityrateselect['inputName']="qzu-rate";
				$utilityrateselect['label']="Rate";
				
				//find the rate choices for this utility
				$custom = get_post_custom($post->ID);
				$rates = unserialize($custom['rates'][0]);
				
				$ratechoices = array(array('text' => 'Select Your Rate', 'value' => ''));
				foreach($rates as $k => $rate){
					if($rate['type']!=""){
						// generate an option for each rate that is not blank
						$ratechoices[] = array('text' => $rate['type'].": ".$rate['value'], 'value' => $k);
				
					}
				}
				
				$utilityrateselect['choices']=$ratechoices;
				*/
				//add the conditional logic to only show when the utility is selected.
				/* example of rate logic:
					array (size=3)
					  'actionType' => string 'show' (length=4)
					  'logicType' => string 'all' (length=3)
					  'rules' => 
						array (size=1)
						  0 => 
							array (size=3)
							  'fieldId' => string '23' (length=2)
							  'operator' => string 'is' (length=2)
							  'value' => string 'this will be auto-generated by the quake zip plugin' (length=51)
				*/
				/*$ratelogic=Array();
				$ratelogicrules=Array();
				$ratelogic['actionType'] = "hide";
				$ratelogic['logicType'] = "all";
				$ratelogicrules[]=Array('fieldId' => $field['id'], 'operator' => 'isnot', 'value' => $post->abbreviation); //if we use $post->post_title for the value in the select dropdown, then we must use it here as well.
				$ratelogic['rules']=$ratelogicrules;
				
				
				//set the conditional logic for this new rate field
				$utilityrateselect['conditionalLogic']=$ratelogic;	
				
				
				$form['fields'][]=$utilityrateselect;
				*/
			
			}
			
			$field['choices'] = $choices;
			
		}
	   
		return $form;
	}

	/*
	
	public function qzu_reorder_rates($form){
		
		//find the rate fields to insert following the utility field
		foreach($form['fields'] as &$field){

			if(strpos($field['cssClass'], 'rate-select') !== false){
				$ratefields[]=$field;
			}
		}
		
		//put the rates following utility field
		foreach($form['fields'] as &$field){
			if(strpos($field['cssClass'], 'rate-select') === false){
				$newfields[]=$field;
				if(strpos($field['cssClass'], 'utility-select') !== false){
					foreach($ratefields as &$ratefield){
						$newfields[]=$ratefield;
					}
				}
			}
		
		}
		
		$form['fields']=$newfields;
		return $form;
	}*/
}