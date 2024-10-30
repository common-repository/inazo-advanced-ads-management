<?php
/*
Plugin Name: Advanced ads Management by Inazo
Description: Plugin for manage ads with widget, on your wordpress site. Use media form library or code to display ads.
Version:     1.5
Author:      Inazo
Author URI:  https://www.kanjian.fr
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
Text Domain: inazo-adv-ads-manager
*/

/*
 * @todo : faire une configuration CSS ?
 */


/*
 * Pour des raisons de sécurité aucun code ne doit être placé au dessus de cette ligne
 */
if( !defined( 'ABSPATH' ) ){
	
	exit(-1);
}


class inazo_adv_ads_management{
	
	const VERSION_IAAM = '1.4';
	const PLUGIN_NAME  = 'inazo_adv_ads_management';
	
	private $modelInazo;
		
	function __construct(){
	
		require_once('models/m_'.self::PLUGIN_NAME.'.php');
		$this->modelInazo = new m_inazo_adv_ads_management();
		
		
		add_action('admin_menu', array($this, 'createAdminMenu') );
		add_action('admin_enqueue_scripts', array($this, 'loadAdminScripts'));
		add_action('wp_ajax_inazo_wp_adds_manager_ajax_add_callback', array($this, 'ajaxAddCallback') );
		add_action('wp_enqueue_scripts', array($this, 'frontEndScript') );	
		

		//add action to load my plugin files
		add_action('plugins_loaded', array($this, 'inazo_adv_ads_management_translation_files'));
		
		//-- appel de la "liste" pour afficher la liste des publicités

		require_once('class/inazo_widget_adds_manager.php');
		add_action('widgets_init', create_function('', 'return register_widget("inazo_widget_adds_manager");') );
	}
	
	/*
	 * Create the install of the plugin
	 */
	function installPlugin(){
	
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		
		dbDelta( $this->modelInazo->createTable() );	
		
		add_option( self::PLUGIN_NAME.'_db_version', self::VERSION_IAAM );		
		/*
		if ( $installed_ver != $inazo_wp_adds_manager_db_version ) {
		
			$this->updateInstall();
		}*/
	}
	
	
	function updateInstall(){
		
		//@todo to develop when i'll create an update
	}
	
	function checkCapability(){
		

		if ( !current_user_can( 'administrator' ) )  {
		
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
	}
	
	/*
	 * Création du menu dans le back office de Wordpress
	 */
	function createAdminMenu(){
		
		if ( current_user_can( 'administrator' ) )  {
			
			add_menu_page( __('Ads Management','inazo-adv-ads-manager'), __('Ads Management','inazo-adv-ads-manager'), 'edit_posts', 'inazo.wp.adv.ads.management.list', array($this, 'listAction') );
			add_submenu_page( 'inazo.wp.adv.ads.management.list', __('List of ads','inazo-adv-ads-manager'), __('List of ads','inazo-adv-ads-manager'),'edit_posts', 'inazo.wp.adv.ads.management.list', array($this, 'listAction') );
			add_submenu_page( 'inazo.wp.adv.ads.management.list', __('Add an ads','inazo-adv-ads-manager'), __('Add an ads','inazo-adv-ads-manager'),'edit_posts', 'inazo.wp.adv.ads.management.add', array($this, 'addAction' ) );
		}
	}
	
	/*
	 * Fonction de retouche de la date
	 */
	
	function replaceDateElement( $date ){
		
		if( !empty($date) && preg_match('/(\d{2})-(\d{2})-(\d{4})/iu', $date)){
			
			$listInfo = explode('-',$date);			
			return $listInfo[2].'-'.$listInfo[1].'-'.$listInfo[0];
		}
		else
			return date('Y-m-d');
	}

	/*
	 * Fonction de reload de la date dans le formulaire de saisie
	 */
	function reloadDateElementInForm( $date ){
		

		$listInfo = explode('-',str_replace(' 00:00:00','',$date));

		return $listInfo[2].'-'.$listInfo[1].'-'.$listInfo[0];
	}
	
	/*
	 * Chargement des scripts nécessaire pour le BO
	 */
	function loadAdminScripts( $hook ) {

		
		if( $hook == 'ads-management_page_inazo.wp.adv.ads.management.add' || preg_match('/inazo\.wp\.adv\.ads\.management\.add/iu',$hook)) {
			
			wp_enqueue_script('media-upload');
			wp_enqueue_script('thickbox');
			wp_enqueue_script('jquery');		
			wp_enqueue_script('jquery-ui-datepicker');
			wp_enqueue_script( 'inazo-adds-manager-script', plugins_url( '/js/admin_add.js', __FILE__ ),array('jquery'));
		
			wp_enqueue_style('jquery-ui-complete',  plugins_url( '/css/jquery-ui.css', __FILE__ ));
			
			// in JavaScript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
			wp_localize_script( 'inazo-adds-manager-script', 'ajax_object',
					array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );
		}
	}
	
	
	/*
	 * Chargement des styles nécessaire pour le BO
	 */
	function loadAdminStyle() {
		
		wp_enqueue_style('thickbox');
	}
	
	function ajaxAddCallback(){
	
		$whatever = intval( $_POST['whatever'] );
		$whatever += 10;
			echo $whatever;
		wp_die();
	}
	
	/*
	 * Controller pour ajouter un ads
	 */
	function addAction() {
	
		$this->checkCapability();
		
		/*
		 * Initialisation des variables
		 */
		$idOfImage 		= 0;
		$idAdds	   		= 0;
		$nameAdds		= '';
		$startDate		= '';
		$endDate		= '';
		$urlAdds		= '';
		$targetOfAdds 	= array(0 => '', 1 => '');
		$etatAdds 		= array(0 => '', 1 => '');
		$codeHtmlOfAdds = '';
		
		//$actionForm = get_option('siteurl').'/wp-admin/admin.php?page=inazo.wp.adv.ads.management.add&noheader=true';
		$actionForm = admin_url('admin.php?page=inazo.wp.adv.ads.management.add&noheader=true');
		
		if( !empty($_GET['action']) ){
			
			if( $_GET['action'] == 'edit' ){
				
				$idAdds = (integer) $_GET['adds'];
				
				$actionForm .= '&action=edit&adds='.$idAdds;
				
				$isAdds = $this->modelInazo->getAds( $idAdds );
				
				//-- on est sur la sauvegarde de la publicité
				if ( ! empty( $_POST ) && check_admin_referer( 'inazo.wp.adv.ads.management.add', 'token_csrf_action_edit' ) ) {
				
					if( $isAdds['num_rows'] != 1 ){
					
						//wp_redirect(get_option('siteurl').'/wp-admin/admin.php?page=inazo.wp.adv.ads.management.list',301);
						wp_redirect(admin_url('admin.php?page=inazo.wp.adv.ads.management.list'),301);
					}					
					
					$dateDebut = date('Y-m-d');
					$dateFin   = '2042-00-00';
					
					if( !empty($_POST['startDate']) && preg_match('/(\d{2})-(\d{2})-(\d{4})/iu', $_POST['startDate']) ){
							
						$dateDebut = $this->replaceDateElement($_POST['startDate']);
					}
					
					if( !empty($_POST['endDate']) && preg_match('/(\d{2})-(\d{2})-(\d{4})/iu', $_POST['endDate'])){
							
						$dateFin = $this->replaceDateElement($_POST['endDate']);
					}
					
					$this->modelInazo->updateAds( $_POST, $dateDebut, $dateFin, $_GET['adds'] );					
					//wp_redirect(get_option('siteurl').'/wp-admin/admin.php?page=inazo.wp.adv.ads.management.list&isUpdated',301);
					wp_redirect(admin_url('admin.php?page=inazo.wp.adv.ads.management.list&isUpdated'),301);
				}
				
				if( $isAdds['num_rows'] == 1 ){
					
					$result 		= $isAdds['valeurs'][0];
					
					$idOfImage 		= $result['id_media_use'];
					$idAdds	   		= $result['id_adds'];
					$nameAdds		= wp_unslash($result['name_adds']);
					$startDate		= $this->reloadDateElementInForm($result['date_debut_parution']);
					$endDate		= $this->reloadDateElementInForm($result['fin_parution']);
					$urlAdds		= $result['url_desti'];
					$codeHtmlOfAdds = wp_unslash($result['code_adds']);
					
					$targetOfAdds[$result['target_link']] 	= 'selected';
					$etatAdds[$result['etat_adds']] 		= 'selected';
				}
			}
		}
		else{
			/*
			 * On va appeler l'ajout de la médiathèque dans la page d'ajout
			 */
		
			if ( ! empty( $_POST ) && check_admin_referer( 'inazo.wp.adv.ads.management.add', 'token_csrf_action_add' ) ) {
		
				$dateDebut = date('Y-m-d');
				$dateFin   = '0000-00-00';
				
				if( !empty($_POST['startDate']) ){
					
					$dateDebut = $this->replaceDateElement($_POST['startDate']);
				}
				
				if( !empty($_POST['endDate']) ){
					
					$dateFin = $this->replaceDateElement($_POST['endDate']);
				}
					
				//$wpdb->show_errors();
				$this->modelInazo->insertAds( $_POST, $dateDebut, $dateFin );				
				//wp_redirect(get_option('siteurl').'/wp-admin/admin.php?page=inazo.wp.adv.ads.management.list&isAdded',301);
				wp_redirect(admin_url('admin.php?page=inazo.wp.adv.ads.management.list&isAdded'),301);
			}
		}	
		
		add_action('admin_print_styles', array( $this, 'loadAdminStyle') );

		wp_enqueue_media();
		wp_enqueue_script( 'custom-header' );
		
		echo '<div class="wrap">';
		echo '<h1>'.__('Add an ads','inazo-adv-ads-manager').'</h1>';
		
		include('view/add.php');
		
		echo '</div>';
	}
	
	/*
	 * Fonction pour lister les ads
	 */
	function listAction() {
		
		global $wpdb;
		
		$this->checkCapability();
		
		if( isset($_GET['isAdded']) ){
			
			include('view/messages/ajout-ok.php');
		}
		else if( isset($_GET['isUpdated']) ){
			
			include('view/messages/update-ok.php');
		}
		
		//-- on check que l'on a bien l'objet de dispo
		
		if( ! class_exists( 'inazo_list_table_adv_ads_manager' ) ) {
			
			require_once( 'class/inazo_list_table_adv_ads_manager.php' );
		}
		
		if( !empty($_GET['action']) ){
			
			switch($_GET['action']){
				
				case 'adds_delete':
					
					//-- on a le bon referer
					if( check_admin_referer( 'trash_adds_inazo', 'corbeille-adds-inazo' ) ) {
						
						$idAds = (integer) $_GET['adds'];
						
						$this->modelInazo->putInTrash( $idAds);						
						include('view/messages/delete-ok.php');
					}
					
				break;
			}
		}
		
		$theList 			= new inazo_list_table_adv_ads_manager();
		$theList->init( $this->modelInazo->queryList() );

		echo '<div class="wrap">';
		echo '<h1>'.__('List of ads','inazo-adv-ads-manager').'<a href="'.get_option('siteurl').'/wp-admin/admin.php?page=inazo.wp.adv.ads.management.add" class="page-title-action">'.__('Add an ads','inazo-adv-ads-manager').'</a></h1>';
		
		include('view/list.php');
		
		echo '</div>';
	}

	/* Script & style */
	function frontEndScript( $hook ){	

		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('inazo-ads-mutli-script', plugins_url( '/js/front_i.a.a.m.js', __FILE__,array(),1,true ));		
		wp_enqueue_style('inazo-adds-manager-css-s', plugins_url( '/css/s.css', __FILE__));
	}
	
	function inazo_adv_ads_management_translation_files() {
		
		load_plugin_textdomain( 'inazo-adv-ads-manager', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
	}
	
}

// inclusion de la librairie models
require_once('models/m_inazo_adv_ads_management.php');


//-- déclaration de l'objet
$inazoAdvAdsManagement = new inazo_adv_ads_management();
register_activation_hook( __FILE__, array( &$inazoAdvAdsManagement, 'installPlugin' ) );

		
//add_action('wp_enqueue_scripts', array(&$inazoAdvAdsManagement, 'frontEndScript') );