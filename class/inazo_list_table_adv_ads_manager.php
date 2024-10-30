<?php 	
if( !defined( 'ABSPATH' ) ){

	exit(-1);
}

if( ! class_exists( 'WP_List_Table' ) ) {

	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * class pour afficher une liste table comme WP classic
 * @author inazo
 *
 */
class inazo_list_table_adv_ads_manager extends WP_List_Table {
	
	private $baseQuery, $wpdb;
	private $defautlOrder = 'id_adds';
	private $listColumSortable = array('id_adds'  => '', 'date_debut_parution' => '', 'fin_parution'   => '');
	
	function init( $baseQuery ){
	
		global $wpdb;
		$this->wpdb = &$wpdb;
		
		$this->baseQuery = $baseQuery;
	}
	
	/*
	 * Liste des colonnes 
	 */
	function get_columns(){
		
		$columns = array(
				'id_adds'      => '#',
				'name_adds'      => __('Title','inazo-adv-ads-manager'),
				'date_debut_parution' => __('Start date','inazo-adv-ads-manager'),
				'fin_parution'    => __('End date','inazo-adv-ads-manager'),
				
		);
		return $columns;
	}
	
	/*
	 * Preparation des items pour affichage en fonction des colonnes
	 */
	function prepare_items(  ) {
		
		$columns  = $this->get_columns();
		$hidden   = array();
		$sortable = $this->get_sortable_columns();
		
		$this->_column_headers = array($columns, $hidden, $sortable);
		
		$orderBy 	 = $this->usort_reorder();
		$this->items = $this->giveMeList( $orderBy );
	}
	
	/*
	 * Affichage d'une colonne par défaut
	 * 
	 * On peut faire une méthode colmun_NOM-COLONNE pour personalisé cette affichage de cellule
	 */
	function column_default( $item, $column_name ) {
		switch( $column_name ) {
			case 'id_adds':
			case 'name_adds':
				return $item[ $column_name ];
			case 'date_debut_parution':
			case 'fin_parution':
				return date_i18n( get_option( 'date_format' ), strtotime( $item[ $column_name ] ) );;
			default:
				return 'oups...' ; //Show the whole array for troubleshooting purposes
		}
	}
	
	/*
	 * Définir quelles colonnes seront sortable
	 */
	function get_sortable_columns() {
		$sortable_columns = array(
				'id_adds'  => array('id_adds',false),
				'date_debut_parution' => array('date_debut_parution',false),
				'fin_parution'   => array('fin_parution',false)
		);
		return $sortable_columns;
	}
	
	/*
	 * On donne les datas
	 */
	function giveMeList( $orderBy ){
	
		if( trim($orderBy['order']) != 'asc' && trim($orderBy['order']) != 'desc' )
			$orderBy['order'] = 'asc';
		
		if( !array_key_exists($orderBy['orderby'],$this->listColumSortable) )
			$orderBy['orderby'] = 'id_adds';
			
		return $this->wpdb->get_results( $this->baseQuery. ' ORDER BY '.$orderBy['orderby'].' '.$orderBy['order'] ,ARRAY_A);
	}
	
	/*
	 * On regarde si il y a un order demandé par l'utilisateur
	 */
	function usort_reorder() {
		
		$orderList   = array('asc' => '','desc' => '');
		
		// If no sort, default to title
		$orderby = ( ! empty( $_GET['orderby'] ) && array_key_exists($_GET['orderby'],$this->listColumSortable) ) ? $_GET['orderby'] : $this->defautlOrder;
		$order   = ( ! empty($_GET['order'] ) && array_key_exists($_GET['order'],$orderList) ) ? $_GET['order'] : 'asc';

		$orders = array( 'orderby' => $orderby, 'order' => $order );
		
		return $orders;
	}
	
	function column_id_adds($item) {
		
		$urlDelete = wp_nonce_url( sprintf('?page=%s&action=%s&adds=%s',$_REQUEST['page'],'adds_delete',$item['id_adds']), 'trash_adds_inazo','corbeille-adds-inazo' );
		
		$actions = array(
				'edit'      => sprintf('<a href="?page=%s&action=%s&adds=%s">%s</a>','inazo.wp.adv.ads.management.add','edit',$item['id_adds'],__('Edit','inazo-adv-ads-manager')),
				'delete'    => sprintf('<a href="'.$urlDelete.'" onclick="return confirm(\''.sanitize_text_field(__('Are you sure to delete this ad ?', 'inazo-adv-ads-manager')).'\');">%s</a>',__('Delete','inazo-adv-ads-manager')),
		);
	
		return sprintf('%1$s %2$s', $item['id_adds'], $this->row_actions($actions) );
	}
}

	
?>