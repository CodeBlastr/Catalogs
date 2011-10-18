<?php
/**
 * Catalog Brand Model
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
class CatalogItemBrand extends CatalogsAppModel {

	var $name = 'CatalogItemBrand';	
	
	var $hasMany = array(
		'CatalogItem'=>array(
			'className'=>'Catalogs.CatalogItem',
			'foreignKey'=>'catalog_item_brand_id',
			'dependent' => true,
		),
	);

	// enbake: not sure about this, since gallery doesnt has model name
	var $hasOne = array(
		'Gallery' => array(
			'className' => 'Galleries.Gallery',
			'foreignKey' => 'foreign_key',
			'dependent' => false,
		),
	);

	var $belongsTo = array(
		'Catalog' => array(
			'className' => 'Catalogs.Catalog',
			'foreignKey' => 'catalog_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Owner' => array(
			'className' => 'Users.User',
			'foreignKey' => 'owner_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);

/**
 * Gets Tree data for displaying user in a more detailed way
 */	
	function generate_tree_wdata(){
		/*$dat = $this->generateTreeList();
			if(count($dat)!=0 ){
				for($i = 0 ; $i < count($dat) ; $i++ ){
				$ret[$i]["id"] = key($dat);
				$ret[$i]["name"] = current($dat);
				next($dat);
			}	
		}else{
			$ret = false;
		}
		return $ret;*/
	}

	function get_brands($catalogId = null) {
		if ($catalogId) {
			return $this->find('list', array(
				'conditions' => array(
					'CatalogItemBrand.catalog_id' => $catalogId
					)
				));
		} else {
			return $this->find('list');	
		}
	}

	function add($data) {
		if ($this->save($data)) {
			$data['Gallery']['model'] = 'CatalogItemBrand';
			$data['Gallery']['foreign_key'] = $this->id;
			if ($this->Gallery->GalleryImage->add($data, 'filename')) {
				#return true;
			} else if (!empty($data['GalleryImage']['filename'])) {
				#return true;
				# gallery image wasn't saved but I'll leave this error message as a todo,
				# because I don't have a decision on whether we should roll back the user 
				# if that happens, or just create the user anyway. 
			}
			return true;
		} else {
			return false;
		}
	}
	
}
?>