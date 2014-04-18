jQuery(document).ready(function($){
 
 // This handles the file uploads for the rates files.
    var custom_uploader;
	var filetarget;

	$('[id$=_button]').click(function(e) {
		
		e.preventDefault();
		
		filetarget = e.target.id.replace("_button","");
		
		//If the uploader object has already been created, reopen the dialog
		if (custom_uploader) {
			custom_uploader.open();
			return;
		}
 
		//Extend the wp.media object
		custom_uploader = wp.media.frames.file_frame = wp.media({
			title: 'Choose File',
			button: {
				text: 'Choose File'
			},
			multiple: false
		});
 
		//When a file is selected, grab the URL and set it as the text field's value
		custom_uploader.on('select', function() {
			attachment = custom_uploader.state().get('selection').first().toJSON();
			$('#'+filetarget).val(attachment.url);
			
		});
 
		//Open the uploader dialog
		custom_uploader.open();
 
	});
	
	
	
	// This handles adding new rates to the screen.
	var ratestatus = new Array();
	
	//go through each rate's values to determine whether it should be shown or not on page load.
	for (var i=1;i<=10;i++)
	{
		
		if($('#ratetype-'+i).val()=="" && $('#ratevalue-'+i).val()=="" && $('#ratedesc-'+i).val()=="" && $('#ratelabel1-'+i).val()=="" && $('#ratelabel2-'+i).val()=="" && $('#ratelabel3-'+i).val()=="" && $('#ratefile1-'+i).val()=="" && $('#ratefile2-'+i).val()=="" && $('#ratefile3-'+i).val()==""){
			//do not show because they are all blank
			//add to available rates to show
			ratestatus[i]=0;
			
			
		} else {
			// show because it has values
			$('#ratebox-'+i).show();
			ratestatus[i]=1;
		}
	}
	
	
	// add the next hidden rate.
	var hiderate;
	
	$('#addrate').click(function(e) {
		
		e.preventDefault();
		
		//find a hidden rate
		for (var i = 1; i <= ratestatus.length; i++) {
			if(ratestatus[i]==0){
				// rate status is not shown (hidden), so show it and change the status to show.
				$('#ratebox-'+i).show();
				ratestatus[i]=1;
				break;
			}
			
		}
		
 
	});
	
	// This handles hiding a particular rate and setting the values to "" blank.
	$('[id$=_removerate]').click(function(e) {
		
		e.preventDefault();
		
		//find which rate to hide based on ID
		hiderate = e.target.id.replace("_removerate","");
		
		//hide the ratebox
		$('#ratebox-'+hiderate).hide();
		
		// set the values to zero, blank.
		$('#ratetype-'+hiderate).val('');
		$('#ratevalue-'+hiderate).val('');
		$('#ratedesc-'+hiderate).val('');
		
		$('#ratelabel1-'+hiderate).val('');
		$('#ratelabel2-'+hiderate).val('');
		$('#ratelabel3-'+hiderate).val('');
		
		$('#ratefile1-'+hiderate).val('');
		$('#ratefile2-'+hiderate).val('');
		$('#ratefile3-'+hiderate).val('');
		 
		 //add the ratebox back to the hidden array
		 ratestatus[hiderate]=0;
		 
		 
	});
	
	
	
	
});