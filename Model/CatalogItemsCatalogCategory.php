<?php
/**
 * Catalog Items Catalog Category Model
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
 
class CatalogItemsCatalogCategory extends CatalogsAppModel {

	var $name = 'CatalogItemsCatalogCategory'; 
	
	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $belongsTo = array(
		'Catalog' => array(
			'className' => 'Catalogs.Catalog',
			'foreignKey' => 'catalog_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'CatalogCategory' => array(
			'className' => 'Catalogs.CatalogCategory',
			'foreignKey' => 'catalog_category_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'CatalogItem' => array(
			'className' => 'Catalogs.CatalogItem',
			'foreignKey' => 'catalog_item_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);

}*/
?>