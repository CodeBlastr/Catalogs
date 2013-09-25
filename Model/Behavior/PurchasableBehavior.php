<?php
App::uses('ModelBehavior', 'Model');

/**
 * Purchasable Behavior class file. (wanted to name it buyable, but that is taken)
 *
 * 1. Tells us if a record is_buyable
 * 
 * Usage :
 * Attach behavior to a model, and create a product (if product exists, then a virtual field is added to find results)
 *
 * @filesource
 * @author			Richard Kersey
 * @copyright       Buildrr LLC
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link            https://github.com/zuha/Transactions-Zuha-Cakephp-Plugin
 */
class PurchasableBehavior extends ModelBehavior {

/**
 * Behavior settings
 * 
 * @access public
 * @var array
 */
	public $settings = array();

/**
 * Default values for settings.
 *
 *
 * @access private
 * @var array
 */
    protected $defaults = array(
    	'modelName' => '', // set in the setup
		'foreignKey' => 'id'
		);

/**
 * Credits var
 * 
 * @var boolean 
 * @todo in the future this may need to contain a count of available credits??
 */
 	public $credits = 0;

/**
 * Credit var
 * The actual transactionItem which is acting as the credit.
 * 
 * @var array 
 */
 	public $credit = array();
	
/**
 * Configuration method.
 *
 * @param object $Model Model object
 * @param array $config Config array
 * @access public
 * @return boolean
 */
    public function setup(Model $Model, $config = array()) {  	
    	$this->settings = array_merge($this->defaults, $config);
		$this->settings['modelName'] = !empty($this->settings['modelName']) ? $this->settings['modelName'] : $Model->name;
    	return true;
	}
	
/**
 * Before save callback
 * 
 */
 	public function beforeSave(Model $Model, $options = array()) {
 		// look up to make sure that we have this item available
		if (!empty($Model->data[$this->settings['modelName']][$this->settings['foreignKey']])) {
			App::uses('TransactionItem', 'Transactions.Model');
			$TransactionItem = new TransactionItem;
			$transactionItem = $TransactionItem->find('first', array(
				'conditions' => array(
					'TransactionItem.model' => $this->settings['modelName'],
					'TransactionItem.foreign_key' => $Model->data[$this->settings['modelName']][$this->settings['foreignKey']],
					'TransactionItem.status' => 'paid'
					)
				));
			if (!empty($transactionItem)) {
				// then we have a credit to use
				$this->credits = 1;
				$this->credit = $transactionItem;
			} else {
				throw new Exception(__('No credits available'));
			}
		}
		return true;
 	}

/**
 * After save callback
 * 
 */
	public function afterSave(Model $Model, $created, $options = array()){
		if ($this->credits > 0 && !empty($this->credit)) {
			App::uses('TransactionItem', 'Transactions.Model');
			$TransactionItem = new TransactionItem;
			$TransactionItem->id = $this->credit['TransactionItem']['id'];
			if ($TransactionItem->saveField('status', 'used', array('validate' => false, 'callbacks' => false))) {
				return true;
			} else {
				throw new Exception(__('Problem using the credit, please notify the site administrator.'));
			}
		}
		return true;
	}

/**
 * Before find callback
 * 
 * @param object
 * @param array
 */
 	// public function beforeFind(Model $Model, array $query) {
 		// $Model->bindModel(array('belongsTo' => array(
			// 'Product' => array(
				// 'className' => 'Products.Product',
				// 'foreignKey' => 'foreign_key',
                // 'conditions' => array('Product.model' => $this->settings['modelName'])
				// )
			// )), false);
 		// $query['contain'][] = 'Product';
 		// debug($query);
		// break;
		// doesn't work for "list" type of find so we need to do a foreach in the afterFind
 	// }
	
/**
 * After find callback
 * 
 * @param object
 * @param mixed
 * @param boolean
 */
	public function afterFind(Model $Model, $results, $primary = false) {
		$foreignKeys = Set::extract('/'.$Model->alias.'/id', $results);
		App::uses('Product', 'Products.Model');
		$Product = new Product;
		
		$products = $Product->find('all', array(
			'conditions' => array(
				'Product.foreign_key' => $foreignKeys, 
				'Product.model' => $this->settings['modelName']
				),
			'callbacks' => false // I have a feeling there is a name conflict with another behavior, and causes a loop here if you leave callbacks in
			));
		if (!empty($products)) {
			for ($i = 0; $i < count($results); $i++) {
				$product = Set::extract('/Product[foreign_key='.$results[$i][$Model->alias]['id'].']', $products);
				if (!empty($product)) {
					$results[$i]['Product'] = $product[0]['Product']; // there's only one possible because of the foreign_key
					$results[$i] = $this->isPurchased($Model, $results[$i]);
				}
			}
		}
		return $results;
	}
	
/**
 * Is purchased method
 * 
 * Checks to see if a given product has been paid for and is set to the status "paid" (eg. unused)
 * (used should be the status if it exists but is not usable anymore)
 * 
 * @param array $data
 */
 	public function isPurchased(Model $Model, $data = array()) {
		if(CakePlugin::loaded('Transactions')) {
			$userId = CakeSession::read('Auth.User.id');
			if (!empty($userId)) {
				App::uses('TransactionItem', 'Transactions.Model');
				$TransactionItem = new TransactionItem;
				$transactionItems = $TransactionItem->find('all', array(
					'conditions' => array(
						'TransactionItem.customer_id' => $userId,
						'TransactionItem.model' => $Model->alias,
						'TransactionItem.foreign_key' => $data[$Model->alias]['id'],
						'TransactionItem.status' => 'paid'
						)
					));
				if (!empty($transactionItems)) {
					$transactionItems = ZuhaSet::manyize($transactionItems);
					$data['TransactionItem'] = $transactionItems['TransactionItem'];
				}
			}
		}
		return $data;
 	}
	
}