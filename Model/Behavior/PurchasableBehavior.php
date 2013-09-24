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
    protected $defaults = array();

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
                // 'conditions' => array('Product.model' => $Model->name)
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
				'Product.model' => $Model->name
				),
			'callbacks' => false // I have a feeling there is a name conflict with another behavior, and causes a loop here if you leave callbacks in
			));
		if (!empty($products)) {
			for ($i = 0; $i < count($results); $i++) {
				$product = Set::extract('/Product[foreign_key='.$results[$i][$Model->alias]['id'].']', $products);
				if (!empty($product)) {
					$results[$i]['Product'] = $product[0]['Product']; // there's only one possible because of the foreign_key = 
				}
			}
		}
		return $results;
	}
	
	
}