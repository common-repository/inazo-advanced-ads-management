<?php

if( !defined( 'ABSPATH' ) ){

	exit(-1);
}


/**
 * Models pour la gestion des bases
 */
class m_inazo_adv_ads_management {

	private $tableName;	
	private $wpdb;
	
	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		
		global $wpdb;
		
		$this->wpdb 	 = &$wpdb;
		$this->tableName = $wpdb->prefix.'inazo_wp_adv_ads_management_the_adds';		
	}

	/**
	 * Création de la table à l'installtion
	 *
	 * @return $sql : string avec la requete à executer
	 */
	public function createTable() {
		
		/*
		 * Cette table va contenir les configurations de base du module avec les paramètres 
		 * nécessaire fournit par soColissimo
		 */
		$sqlForCreateTable = 'CREATE TABLE '.$this->tableName.' (
		id_adds int(11) NOT NULL AUTO_INCREMENT,
		date_debut_parution datetime NOT NULL,
		fin_parution datetime NOT NULL,
		type_adds int(11) NOT NULL,
		code_adds text NULL,
		id_media_use int(11) NULL,
		url_desti text NULL,
		target_link int(11) NOT NULL,
		name_adds varchar(255) NULL,
		etat_adds int(1),
		PRIMARY KEY id_adds (id_adds)
		) '.$this->wpdb->get_charset_collate().';';
		
		return $sqlForCreateTable;
	}
	/* Suppression de la table */
	public function removeTable() {
		
		/*
		 * Cette table va contenir les configurations de base du module avec les paramètres 
		 * nécessaire fournit par soColissimo
		 */
		$sqlForDropTable = 'DROP TABLE IF EXISTS '.$this->tableName.';';
		return $sqlForDropTable;
	}
	
	/**
	 * @return $sqlResult
	 */
	public function getAds( $idAds ){
		
		$toReturn = array( 'num_rows' => '', 'valeurs' => '' );
		
		$sql = $this->wpdb->prepare('SELECT * FROM '.$this->tableName.' WHERE id_adds = %d AND etat_adds<>3 ', (integer) $idAds );
		$this->wpdb->query( $sql );
		
		$toReturn['num_rows'] = $this->wpdb->num_rows;
		
		if( $this->wpdb->num_rows > 0 )
			$toReturn['valeurs'] = $this->wpdb->get_results($sql, ARRAY_A);
		
		return $toReturn;
	}
	
	/**
	 * update an ads
	 */
	public function updateAds( $valuesPost, $dateDebut, $dateFin, $idAds ){
		
		$this->wpdb->update(

			$this->tableName,
			
			array(
			'date_debut_parution' => $dateDebut.' 00:00:00',
			'fin_parution' => $dateFin.' 00:00:00',
			'type_adds' => 1,
			'code_adds' => $valuesPost['codeHtmlOfAdds'],
			'id_media_use' => $valuesPost['idMediOfAdds'],
			'url_desti' => $valuesPost['urlAdds'],
			'target_link' => $valuesPost['targetOfAdds'],
			'name_adds' => $valuesPost['nameAdds'],
			'etat_adds' => $valuesPost['etatAdds']
			),
			
			array('id_adds' => (integer) $idAds),
			
			array(
					'%s',
					'%s',
					'%d',
					'%s',
					'%d',
					'%s',
					'%s',
					'%s',
					'%d'
			)
		);
	}
	
	/*
	 * Function insert an ads
	 */
	public function insertAds( $valuesPost, $dateDebut, $dateFin ){
	
		$this->wpdb->insert( $this->tableName,				
			array(

				'date_debut_parution' => $dateDebut.' 00:00:00',
				'fin_parution' => $dateFin.' 00:00:00',
				'type_adds' => 1,
				'code_adds' => $valuesPost['codeHtmlOfAdds'],
				'id_media_use' => $valuesPost['idMediOfAdds'],
				'url_desti' => $valuesPost['urlAdds'],
				'target_link' => $valuesPost['targetOfAdds'],
				'name_adds' => $valuesPost['nameAdds'],
				'etat_adds' => $valuesPost['etatAdds'],
			)				
		);	
	}
	
	/* Put in trash */
	public function putInTrash( $idAds ){
	
		$this->wpdb->update( $this->tableName, array( 'etat_adds' => 3 ), array('id_adds' => (integer) $idAds ) );	
	}
	
	/* Return query string for listing*/
	public function queryList(){
	
		return 'SELECT * FROM '.$this->tableName.' WHERE etat_adds<>3';
	}
	
	/*Méthode pour le widget */
	public function getWidgetAdds($mode, $nbAddsMax=1, $multi = 0 ){
		
		$toReturn = array( 'num_rows' => '', 'valeurs' => '' );
		
		$orderBy = ' ORDER BY RAND() ';
		
		$nbAddsMax = (integer) $nbAddsMax;
		
		if( $mode == 1 )
			$orderBy = ' ORDER BY date_debut_parution ASC ';
		else if( $mode == 2 )
			$orderBy = ' ORDER BY fin_parution ASC ';
		
		if( $multi == 0 && $nbAddsMax > 0 )
			$limit = ' LIMIT 0,'.$nbAddsMax.' ';
		else
			$limit = ' LIMIT 0,1 ';	
		
		$sql = $this->wpdb->prepare('SELECT * FROM '.$this->tableName.' WHERE etat_adds=%d AND ( date_debut_parution <= NOW() OR date_debut_parution = "0000-00-00 00:00:00") AND ( fin_parution >= NOW() OR fin_parution = "0000-00-00 00:00:00") '.$orderBy.$limit.'',1);
		$this->wpdb->query( $sql );
		
		$toReturn['num_rows'] = $this->wpdb->num_rows;
		
		if( $this->wpdb->num_rows > 0 )
			$toReturn['valeurs'] = $this->wpdb->get_results($sql, ARRAY_A);
		
		return $toReturn;
	}	
}