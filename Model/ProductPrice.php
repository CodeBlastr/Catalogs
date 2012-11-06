<?php
/**
 * Product Price Model
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
class ProductPrice extends ProductsAppModel {

	public $name = 'ProductPrice'; 

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	public $belongsTo = array(
		'UserRole' => array(
			'className' => 'UserRole',
			'foreignKey' => 'user_role_id',
			'conditions' => '',
			'fields' => '',
		),
		'Product' => array(
			'className' => 'Products.Product',
			'foreignKey' => 'product_id',
			'conditions' => '',
			'fields' => '',
		),
		'ProductStore' => array(
			'className' => 'Products.ProductStore',
			'foreignKey' => 'store_id',
			'conditions' => '',
			'fields' => '',
		)
	);
	
	public function get_price($userRoleId, $productId) {
		return $this->field('price', array('user_role_id' => $userRoleId,
					'product_id' => $productId		
		));
		
	}
}