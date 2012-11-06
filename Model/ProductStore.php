<?php
App::uses('ProductsAppModel', 'Products.Model');
/**
 * ProductStore Model
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
 * @link          http://zuha.com Zuha� Project
 * @package       zuha
 * @subpackage    zuha.app.plugins.products.models
 * @since         Zuha(tm) v 0.0.1
 * @license       GPL v3 License (http://www.gnu.org/licenses/gpl.html) and Future Versions
 */
class ProductStore extends ProductsAppModel {

	public $name = 'ProductStore'; 
	
	public $hasMany = array(
		'Product'=>array(
			'className'=>'Products.Product',
			'foreignKey'=>'store_id',
			'dependent' => true,
		),
		'ProductBrand'=>array(
			'className'=>'Products.ProductBrand',
			'foreignKey'=>'store_id',
			'dependent' => true,
		)
	);
	

    public $hasAndBelongsToMany = array(
        'Category' => array(
            'className' => 'Categories.Category',
       		'joinTable' => 'categorized',
            'foreignKey' => 'foreign_key',
            'associationForeignKey' => 'category_id',
    		'conditions' => 'Categorized.model = "ProductStore"',
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
		try {
			$data['Category']['user_id'] = CakeSession::read('Auth.User.id');
			$this->create();
			$this->save($data);
            return true;
		} catch (Exception $e) {
				throw new Exception($e->getMessage());
        }
	}

}