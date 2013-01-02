<?php
App::uses('ProductsAppModel', 'Products.Model');
/**
 * Product Brand Model
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
class ProductBrand extends ProductsAppModel {

	public $name = 'ProductBrand';	
    
    public $actsAs = array('Galleries.Mediable');
	
	public $hasMany = array(
		'Product'=>array(
			'className'=>'Products.Product',
			'foreignKey'=>'product_brand_id',
			'dependent' => true,
		),
	);

	public $hasOne = array(
		'Gallery' => array(
			'className' => 'Galleries.Gallery',
			'foreignKey' => 'foreign_key',
			'dependent' => false,
		),
	);

	public $belongsTo = array(
		'ProductStore' => array(
			'className' => 'Products.ProductStore',
			'foreignKey' => 'store_id',
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

	public function get_brands($productId = null) {
		if ($productId) {
			return $this->find('list', array(
				'conditions' => array(
					'ProductBrand.product_id' => $productId
					)
				));
		} else {
			return $this->find('list');	
		}
	}
	
}