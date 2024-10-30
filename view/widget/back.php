<?php
if( !defined( 'ABSPATH' ) ){
	
	exit(-1);
}
?>

<p>
<label for="<?php echo $currentObject->get_field_id( 'title' ); ?>"><?php _e( 'Title:','inazo-adv-ads-manager' ); ?></label> 
<input class="widefat" id="<?php echo $currentObject->get_field_id( 'title' ); ?>" name="<?php echo $currentObject->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
</p>

<p>
<label for="<?php echo $currentObject->get_field_id( 'nb_adds_max' ); ?>"><?php _e( 'Max of ads to show:','inazo-adv-ads-manager' ); ?></label> 
<input class="widefat" id="<?php echo $currentObject->get_field_id( 'nb_adds_max' ); ?>" name="<?php echo $currentObject->get_field_name( 'nb_adds_max' ); ?>" type="text" value="<?php echo esc_attr( $nbAddsMax ); ?>">
</p>

<p>
<label for="<?php echo $currentObject->get_field_id( 'mode' ); ?>"><?php _e( 'Order ads by:','inazo-adv-ads-manager' ); ?></label> 
<select id="<?php echo $currentObject->get_field_id( 'mode' ); ?>" name="<?php echo $currentObject->get_field_name( 'mode' ); ?>">
	<option value="0" <?php echo $selectableMode[0];?> ><?php _e('Random', 'inazo-adv-ads-manager'); ?></option>
	<option value="1" <?php echo $selectableMode[1];?> ><?php _e('By start date', 'inazo-adv-ads-manager'); ?></option>
	<option value="2" <?php echo $selectableMode[2];?> ><?php _e('By end date', 'inazo-adv-ads-manager'); ?></option>			
</select>
</p>

<p>
<label for="<?php echo $currentObject->get_field_id( 'multi' ); ?>"><?php _e( 'Show multiple ads:','inazo-adv-ads-manager' ); ?></label> 
<select id="<?php echo $currentObject->get_field_id( 'multi' ); ?>" name="<?php echo $currentObject->get_field_name( 'multi' ); ?>">
	<option value="0" <?php echo $selectableMulti[0];?> ><?php _e('Yes', 'inazo-adv-ads-manager'); ?></option>
	<option value="1" <?php echo $selectableMulti[1];?> ><?php _e('No', 'inazo-adv-ads-manager'); ?></option>			
</select>
</p>

<p>
<label for="<?php echo $currentObject->get_field_id( 'taille_w' ); ?>"><?php _e( 'Width of zone:','inazo-adv-ads-manager' ); ?></label> 
	<input class="widefat" id="<?php echo $currentObject->get_field_id( 'taille_w' ); ?>" name="<?php echo $currentObject->get_field_name( 'taille_w' ); ?>" type="text" value="<?php echo esc_attr( $tailleW ); ?>">
</p>

<p>
<label for="<?php echo $currentObject->get_field_id( 'taille_h' ); ?>"><?php _e( 'Height of zone:','inazo-adv-ads-manager' ); ?></label> 
	<input class="widefat" id="<?php echo $currentObject->get_field_id( 'taille_h' ); ?>" name="<?php echo $currentObject->get_field_name( 'taille_h' ); ?>" type="text" value="<?php echo esc_attr( $tailleH ); ?>">
</p>