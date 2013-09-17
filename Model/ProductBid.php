<?php
App::uses('ProductsAppModel', 'Products.Model');
class ProductBid extends ProductsAppModel {

	public $name = 'ProductBid';

	public $belongsTo = array(
		'Product' => array(
			'className' => 'Products.Product',
			'foreignKey' => 'product_id',
			'conditions' => '',
			'fields' => ''
		),
		'User' => array(
			'className' => 'Users.User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => ''
		)
	);

}
