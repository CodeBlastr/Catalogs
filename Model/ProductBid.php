<?php
App::uses('ProductsAppModel', 'Products.Model');
class ProductBid extends ProductsAppModel {

	public $name = 'ProductBid';

	public $validate = array(
		'amount' => array(
			'notempty' => array(
				'rule' => 'notEmpty',
				'allowEmpty' => false, 
				'message' => 'Please enter bid value',
				'required' => 'create'
				),
			'checkHighBid' => array(
				'rule' => array('_checkHighestBid'),
				'allowEmpty' => false, 
				'message' => 'Your bid should be higher than the current highest bid.',
				),
			)
		);

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
			'fields' => '',
			'order' => array('amount' => 'DESC'),
			'limit' => 1
		)
	);
	
	public function _checkHighestBid() {
		if (!empty($this->data['ProductBid']['product_id'])) {
			$highestBid = $this->field('amount', array('ProductBid.product_id' => $this->data['ProductBid']['product_id']), 'ProductBid.amount DESC');
			if ($highestBid > $this->data['ProductBid']['amount']) {
				return false;
			}
		}
		return true;
	}

/**
 * Get Winner method
 * Find the highest bid and return the bid and the user. 
 */
	public function getWinner($productId) {
		return $this->find('first', array('conditions' => array('ProductBid.product_id' => $productId), 'contain' => array('User')));
	}
	
}
