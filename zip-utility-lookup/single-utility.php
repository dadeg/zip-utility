<?php get_header(); ?>	
	<div class="content page-content">
		<div class="shell">
			<section class="main-section">
			
				
				<?php
					$custom = get_post_custom($post->ID);
					$rates = unserialize($custom['rates'][0]);
					//var_dump($post);
			
				?>
				
				<h1><?php _e('Gas Rates for '.$post->post_title); ?></h1>
				<div class="container">
					<?php _e($post->post_content);?>
				</div>
				<div class="cl">&nbsp;</div>
				
				
				<style>
					.rate {
						border:1px solid #ccc;
						margin:10px;
						width:800px;
					}
					.rate p {
						padding:10px;
						
					}
					.rate p.type {
						border-bottom:1px solid #ccc;
						font-weight:bold;
					}
					.rate input {
						width:2em;
						height:2em;
						cursor:pointer;
						float:left;
						
					}
					.rate label {
						padding-left:30px;
						display:block;
						padding-top:5px;
					}
					.rate a.file {
						display:block;
					}
				</style>
				
				
				<div class="rates">
				<p>Choose one of the rates below:</p>
				<?php 
					$url = "/?action=qzuratesubmit";
					$action = "rate-submit";
					$link = wp_nonce_url( $url, $action ); 
				?>
					<form action="<?php echo $link;?>" method="POST">
						<input type='hidden' name='utility' value='<?php echo $post->ID;?>' />
						<?php 
						foreach($rates as $k => $rate){
							if($rate['type']!=""){ ?>
								<div class="rate">
									<p class="type"><input type="radio" name="rate" id="rate<?php echo $k;?>" value="<?php echo $k;?>">

									<label for="rate<?php echo $k;?>"><?php _e(ucfirst($rate['type'])." Rate"); ?></label>
									</p><p>
									<?php _e($rate['value']); ?></p>
									<p>
									<?php _e($rate['desc']); ?></p>
									<p>
										<?php if($rate['file1']!=""){?>
											<a class="file" href="<?php echo $rate['file1']; ?>" target="_blank"><?php _e($rate['label1']);?></a>
										<?php } ?>
										<?php if($rate['file2']!=""){?>
											<a class="file" href="<?php echo $rate['file2']; ?>" target="_blank"><?php _e($rate['label2']);?></a>
										<?php } ?>
										<?php if($rate['file3']!=""){?>
											<a class="file" href="<?php echo $rate['file3']; ?>" target="_blank"><?php _e($rate['label3']);?></a>
										<?php } ?>
									</p>
								</div>	
							<?php }
						}
						?>
						<?php 
							$url = "/?action=qzuzipenter";
							$action = "zip-enter";
							$link = wp_nonce_url( $url, $action ); 
						?>
						<input type="submit" class="btn orange-btn" value='<?php _e('ENROLL NOW'); ?>'> or <a href="<?php echo $link;?>">enter a different zip code</a>.
					</form>
				</div>
				
			</section><!-- /.main-section -->
		</div><!-- /.shell -->
	</div><!-- /.content page-content -->
<?php get_footer(); ?>