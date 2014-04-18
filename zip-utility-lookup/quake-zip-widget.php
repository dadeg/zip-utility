<?php

// this file holds the widget for users to enter their zip code



// widget for homepage
class QZUwidget extends WP_Widget
{
  function QZUwidget()
  {
    $widget_ops = array('classname' => 'QZUwidget', 'description' => 'Displays a form that asks for Zip Code to look up rates.' );
    $this->WP_Widget('QZUwidget', 'Quake Zip Code Form Rate Lookup', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
    $title = $instance['title'];
?>

	

  <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
 
    //if (!empty($title))
      //echo $before_title . $title . $after_title;;
 
    // WIDGET CODE GOES HERE
    ?>
	<style>
		.utility-col input { position: relative;
			display: block;
			overflow: hidden;
			padding: 0 0 0 15px;
			height: 60px;
			width:180px;
			border: 1px solid #bcbec0;

			text-decoration: none;
			white-space: nowrap;
			line-height: 30px;
			font-size:30px; 
		}

		.rate-col input {
			border:none;
			cursor:pointer;
		}
	</style>

	<div class="cols rate-cols exo_bold clearfix">
		
		<?php $url = "/?action=qzuzipsubmit";
		$action = "zip-submit";
		$link = wp_nonce_url( $url, $action );
		
		
		?>
		<form method="post" action="<?php echo $link;?>">
			<div class="col txt-col"><h1><?php _e('SEE OUR CURRENT RATES'); ?></h1></div><!-- /.col -->
		
			<div class="col utility-col">
				
					<label><?php _e('Enter Your Zip Code:'); ?></label>
					<input type='text' name='zip' id='zip' />
					<?php if($_GET['qzue']){?><p style="color:red;">That zip code is not in our service area.</p><?php } ?>
				
			</div><!-- /.col -->
			
			<div class="col rate-col">
				<input type="submit" class="btn orange-btn" value='<?php _e('FIND RATES'); ?>'>
			</div>
			
		</form>
	</div>
	<?php
 
    echo $after_widget;
  }
 
}




