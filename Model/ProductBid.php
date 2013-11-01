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
 * Finish Auction method
 * 
 */
	public function finishAuction($product, $options = array()) {
		if ($options['email'] === false) {
			/// then email the winner
			$this->notifySeller($product, $options); 
			$this->notifyWinner($product, $options);
		}
		// do any other auction wrap up stuff here, just don't know what that might be right now...
	}
	
	
/**
 * Notify Auctioneer (Site Admin/Owner) that Auction Product has Expired.
 * @param array $results
 * @return array
 * 
 */
	public function notifySeller($product, $options = array()){
		// note we need to add a field to the product model called sellerid
		$this->__sendMail($product['Creator']['email'],'Webpages.Auctioneer Expired Auction', $product);	
	}
	
/**
 * Notify Auction Bidder that auction has expired
 * @param array $results
 * @return array
 * 
 */	
	public function notifyWinner($product, $options = array()){
		$winner = $this->getWinner($product[$this->alias]['id'], $options);
		if (!empty($winner)) { // there may not have been a winner
			$emailarr = $product + $winner;
			$this->__sendMail($winner['User']['email'],'Webpages.Auction Winner Notification', $emailarr);	
		}
	}

/**
 * Get Winner method
 * Find the highest bid and return the bid and the user. 
 */
	public function getWinner($productId, $options = array()) {
		return $this->find('first', array('conditions' => array('ProductBid.product_id' => $productId), 'contain' => array('User')));
	}
	
}
