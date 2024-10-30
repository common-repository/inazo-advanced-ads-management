<?php
if( !defined( 'ABSPATH' ) ){
	
	exit(-1);
}
?>
<form method="post" action="<?php echo $actionForm; ?>"> 

<div id="poststuff">
	
	<div class="postbox">
		<div class="inside">
		
			<p>
				<label><?php echo __('Name of ads','inazo-adv-ads-manager');?></label><br />
				<input type="text" name="nameAdds" value="<?php echo $nameAdds; ?>" required />
			</p>
			
			<p>
				<label><?php echo __('Date start of ads (default: today)','inazo-adv-ads-manager');?></label><br />
				<input type="text" name="startDate" value="<?php echo $startDate; ?>" class="dateField" />
			</p>
			
			<p>
				<label><?php echo __('Date end of ads (default: never)','inazo-adv-ads-manager');?></label><br />
				<input type="text" name="endDate" value="<?php echo $endDate; ?>" class="dateField" />
			</p>
			
			<p>
				<label><?php echo __('URL','inazo-adv-ads-manager');?></label><br />
				<input type="text" name="urlAdds" value="<?php echo $urlAdds; ?>" />
			</p>
			
			<p>
				<label><?php echo __('Target of link','inazo-adv-ads-manager');?></label><br />
				<select name="targetOfAdds">
					<option value="0" <?php echo $targetOfAdds[0]; ?>><?php _e('Same window', 'inazo-adv-ads-manager'); ?></option>
					<option value="1" <?php echo $targetOfAdds[1]; ?>><?php _e('New window', 'inazo-adv-ads-manager'); ?></option>
				</select>
			</p>
			
			<p>
				<label><?php echo __('Activate the ads','inazo-adv-ads-manager');?></label><br />
				<select name="etatAdds">
					<option value="1" <?php echo $etatAdds[1]; ?>><?php _e('Enable', 'inazo-adv-ads-manager'); ?></option>
					<option value="0" <?php echo $etatAdds[0]; ?>><?php _e('Disable', 'inazo-adv-ads-manager'); ?></option>
				</select>
			</p>		
			
			
			<div style="width:100%;">
				<div style="width:40%; float:left;">
					<label><?php _e('HTML Codes', 'inazo-adv-ads-manager'); ?></label><br />
					<textarea style="width:100%;" id="codeHtmlOfAdds" name="codeHtmlOfAdds"><?php echo $codeHtmlOfAdds; ?></textarea>	
				</div>
				
				<div style="width:10%; float:left; padding:40px 0; text-align: center; ">
					<?php _e('OR', 'inazo-adv-ads-manager'); ?> 
				</div>
				
				<div style="width:40%; float:left;">
					<label><?php _e('Image from Media library', 'inazo-adv-ads-manager'); ?></label><br />
					<?php
					
						$adminUrl = admin_url('admin.php');
						
					/*	if( is_ssl() )
							$adminUrl = admin_url('admin.php', 'https');*/
					
						$modal_update_href = esc_url( add_query_arg( array(
							'page' => 'inazo.wp.adds.manager.add',
							'_wpnonce' => wp_create_nonce('inazo.wp.adds.manager.add'),
						), $adminUrl ) );
						
						/*
						// Add to the top of our data-update-link page
						if (isset($_REQUEST['file'])) {
							
							check_admin_referer("inazo.wp.adds.manager.add");
							$idOfImage = absint($_REQUEST['file']);
						}*/
					?>        
					<p>
						
						<div id="zoneBtnImage">
							<a id="inazo-choose-from-library-link" href="#" class="button"
								data-update-link="<?php echo esc_attr( $modal_update_href ); ?>"
								data-choose="<?php esc_attr_e( 'Choose an Image','inazo-adv-ads-manager' ); ?>"
								data-update="<?php esc_attr_e( 'Choose this image','inazo-adv-ads-manager' ); ?>"><?php _e( 'Set default image','inazo-adv-ads-manager' ); ?>
							</a>
						</div>						
						
						<input type="hidden" value="<?php echo $idOfImage; ?>" id="id_attach_of_adds" name="idMediOfAdds">
						<div id="imageOfAdds" style="width:60%; display:block;height:60%;"><?php echo wp_get_attachment_image( $idOfImage ); ?></div><br />
					</p>
				</div>
			</div>
			
			<div class="clear"></div>
			<p>
				<?php submit_button(); ?>
			</p>
		</div>
	</div>
</div>

<input type="hidden" name="idAdds" value="<?php echo $idAdds; ?>" />

<?php 

if( !empty($_GET['action']) )
	wp_nonce_field( 'inazo.wp.adv.ads.management.add', 'token_csrf_action_edit' ); 
else
	wp_nonce_field( 'inazo.wp.adv.ads.management.add', 'token_csrf_action_add' );

?>

</form>