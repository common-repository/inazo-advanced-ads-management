<?php

if( !defined( 'ABSPATH' ) ){

	exit(-1);
}


/**
 * Adds Foo_Widget widget.
 */
class inazo_widget_adds_manager extends WP_Widget {

	private $modelInazo;
	
	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		
		require_once(plugin_dir_path(__FILE__).'../models/m_inazo_adv_ads_management.php');
		$this->modelInazo = new m_inazo_adv_ads_management();
		
		parent::__construct(
				'inazo_adv_ads_manager', // Base ID
				__( 'Advanced ads Manager', 'inazo-adv-ads-manager' ), // Name
				array( 'description' => __( 'A widget for include your ads', 'inazo-adv-ads-manager' ), ) // Args
				);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {

		$nbAddsMax = ! empty( $instance['nb_adds_max'] ) ? $instance['nb_adds_max'] : __( '4', 'inazo-adv-ads-manager' );
		$title 	   = ! empty( $instance['title'] ) ? $instance['title'] : __( 'New title', 'inazo-adv-ads-manager' );
		$mode	   = ! empty( $instance['mode'] ) ? $instance['mode'] : __( '0', 'inazo-adv-ads-manager' );
		$multi	   = ! empty( $instance['multi'] ) ? $instance['multi'] : __( '0', 'inazo-adv-ads-manager' );
		$tailleW   = ! empty( $instance['taille_w'] ) ? $instance['taille_w'] : __( '290', 'inazo-adv-ads-manager' );
		$tailleH   = ! empty( $instance['taille_h'] ) ? $instance['taille_h'] : __( '290', 'inazo-adv-ads-manager' );
		
		$resultsQuery = $this->modelInazo->getWidgetAdds( $mode, $nbAddsMax, $multi );
		
		if( $resultsQuery['num_rows'] > 0 ){
			
			$theAdds = $resultsQuery['valeurs'];		
			include(plugin_dir_path(__FILE__).'../view/widget/front.php');
		}
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		
		$nbAddsMax = ! empty( $instance['nb_adds_max'] ) ? $instance['nb_adds_max'] : __( '4', 'inazo-adv-ads-manager' );		
		$title 	   = ! empty( $instance['title'] ) ? $instance['title'] : __( 'New title', 'inazo-adv-ads-manager' );
		$mode	   = ! empty( $instance['mode'] ) ? $instance['mode'] : __( '0', 'inazo-adv-ads-manager' );
		$multi	   = ! empty( $instance['multi'] ) ? $instance['multi'] : __( '0', 'inazo-adv-ads-manager' );
		$tailleW   = ! empty( $instance['taille_w'] ) ? $instance['taille_w'] : __( '290', 'inazo-adv-ads-manager' );
		$tailleH   = ! empty( $instance['taille_h'] ) ? $instance['taille_h'] : __( '290', 'inazo-adv-ads-manager' );
		
		$selectableMode = array('0' => '', '1' => '', '2' => '');
		$selectableMode[$mode] = 'selected';
		
		$selectableMulti = array('0' => '', '1' => '');
		$selectableMulti[$multi] = 'selected';
		
		$currentObject = &$this;
		
		include(plugin_dir_path(__FILE__).'../view/widget/back.php');
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['nb_adds_max'] = ( ! empty( $new_instance['nb_adds_max'] ) ) ? strip_tags( $new_instance['nb_adds_max'] ) : '';
		$instance['mode'] = ( ! empty( $new_instance['mode'] ) ) ? strip_tags( $new_instance['mode'] ) : '';
		$instance['multi'] = ( ! empty( $new_instance['multi'] ) ) ? strip_tags( $new_instance['multi'] ) : '';
		
		$instance['taille_w'] = ( ! empty( $new_instance['taille_w'] ) ) ? strip_tags( $new_instance['taille_w'] ) : '';
		$instance['taille_h'] = ( ! empty( $new_instance['taille_h'] ) ) ? strip_tags( $new_instance['taille_h'] ) : '';

		return $instance;
	}

}