<?php
/**
 * Catalog Item Price Model
 *
 *
 * PHP versions 5
 *
 * Zuha(tm) : Business Management Applications (http://zuha.com)
 * Copyright 2009-2010, Zuha Foundation Inc. (http://zuha.org)
 *
 * Licensed under GPL v3 License
 * Must retain the above copyright notice and release modifications publicly.
 *
 * @copyright     Copyright 2009-2010, Zuha Foundation Inc. (http://zuha.com)
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
			'order' => 'CatalogItemPrice.user_role_id asc, CatalogItemPrice.price_type_id asc'
		),
		'CatalogItem' => array(
			'className' => 'Catalogs.CatalogItem',
			'foreignKey' => 'catalog_item_id',
			'conditions' => '',
			'fields' => '',
			'order' => 'CatalogItemPrice.user_role_id asc, CatalogItemPrice.price_type_id asc' 
		),
		'Catalog' => array(
			'className' => 'Catalogs.Catalog',
			'foreignKey' => 'catalog_id',
			'conditions' => '',
			'fields' => '',
			'order' => 'CatalogItemPrice.user_role_id asc, CatalogItemPrice.price_type_id asc'
		),
		'PriceType' => array(
			'className' => 'Enumeration',
			'foreignKey' => 'price_type_id',
			'conditions' => array('PriceType.type' => 'PRICE_TYPE'),
			'fields' => '',
			'order' => 'CatalogItemPrice.user_role_id asc, CatalogItemPrice.price_type_id asc'
  		),
	);
	
	function get_price($user_role_id, $catalog_item_id) {
		return $this->field('price', array('user_role_id' => $user_role_id,
					'catalog_item_id' => $catalog_item_id		
		));
		
	}
}
?>