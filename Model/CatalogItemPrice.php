<?php
/**
 * Catalog Item Price Model
 *
 *
 * PHP versions 5
 *
 * Zuha(tm) : Business Management Applications (http://zuha.com)
 * Copyright 2009-2012, Zuha Foundation Inc. (http://zuha.org)
 *
 * Licensed under GPL v3 License
 * Must retain the above copyright notice and release modifications publicly.
 *
 * @copyright     Copyright 2009-2012, Zuha Foundation Inc. (http://zuha.com)
 * @link          http://zuha.com Zuha™ Project
 * @package       zuha
 * @subpackage    zuha.app.plugins.catalogs.models
 * @since         Zuha(tm) v 0.0.1
 * @license       GPL v3 License (http://www.gnu.org/licenses/gpl.html) and Future Versions
 */
class CatalogItemPrice extends CatalogsAppModel {

	var $name = 'CatalogItemPrice'; 

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $belongsTo = array(
		'UserRole' => array(
			'className' => 'UserRole',
			'foreignKey' => 'user_role_id',
			'conditions' => '',
			'fields' => '',
		),
		'CatalogItem' => array(
			'className' => 'Catalogs.CatalogItem',
			'foreignKey' => 'catalog_item_id',
			'conditions' => '',
			'fields' => '',
		),
		'Catalog' => array(
			'className' => 'Catalogs.Catalog',
			'foreignKey' => 'catalog_id',
			'conditions' => '',
			'fields' => '',
		)
	);
	
	function get_price($user_role_id, $catalog_item_id) {
		return $this->field('price', array('user_role_id' => $user_role_id,
					'catalog_item_id' => $catalog_item_id		
		));
		
	}
}
?>