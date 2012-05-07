<?php
App::uses('CatalogsAppModel', 'Catalogs.Model');
/**
 * Catalog Model
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
class Catalog extends CatalogsAppModel {

	var $name = 'Catalog'; 
	
	var $hasMany = array(
		'CatalogItem'=>array(
			'className'=>'Catalogs.CatalogItem',
			'foreignKey'=>'catalog_id',
			'dependent' => true,
		),
		'CatalogItemBrand'=>array(
			'className'=>'Catalogs.CatalogItemBrand',
			'foreignKey'=>'catalog_id',
			'dependent' => true,
		)
	);
	

    var $hasAndBelongsToMany = array(
        'Category' => array(
            'className' => 'Categories.Category',
       		'joinTable' => 'categorized',
            'foreignKey' => 'foreign_key',
            'associationForeignKey' => 'category_id',
    		'conditions' => 'Categorized.model = "Catalog"',
    		// 'unique' => true,
        ),
    );  

/**
 * Adds a new record to the database
 *
 * @param string $userId, user id
 * @param array post data, should be Contoller->data
 * @return array
 */
	public function add($data = null) {
		if (!empty($data)) {
			$data['Category']['user_id'] = $userId;
			$this->create();
			$result = $this->save($data);
			if ($result !== false) {
				$this->request->data = array_merge($data, $result);
				return true;
			} else {
				throw new Exception(__d('categories', 'Could not save the category, please check your inputs.', true));
			}
			return $return;
		}
	}

    
}
?>