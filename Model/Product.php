<?php
App::uses('ProductsAppModel', 'Products.Model');
/**
 * Product Model
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
class Product extends ProductsAppModel {

	public $name = 'Product';
    
    public $filterPrice = true;

	public $validate = array(
		'name' => array('notempty'),
        );

	public $actsAs = array(
		'Tree' => array('parent' => 'parent_id'), 
        );

	public $order = '';

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	public $hasMany = array(
		'TransactionItem' => array(
			'className' => 'Transactions.TransactionItem',
			'foreignKey' => 'foreign_key_id',
			'dependent' => false,
            ),
		'ProductPrice' => array(
			'className' => 'Products.ProductPrice',
			'foreignKey' => 'product_id',
			'dependent' => true,
			'order' => 'ProductPrice.user_role_id asc'
            ),
		'ProductChildren' => array(
			'className' => 'Products.Product',
			'foreignKey' => 'parent_id',
			'dependent' => true,
            ),
        );

	public $hasOne = array(
		'Gallery' => array(
			'className' => 'Galleries.Gallery',
			'foreignKey' => 'foreign_key',
			'dependent' => false,
			'conditions' => array('Gallery.model' => 'Product'),
			'fields' => '',
			'order' => ''
            )
        );

	//products association.
	public $belongsTo = array(
		'ProductParent'=>array(
			'className' => 'Products.Product',
			'foreignKey' => 'parent_id',
			'counterCache' => 'children',
			'counterScope' => array('Product.parent_id IS NOT NULL'),
            ),
		'ProductStore'=>array(
			'className' => 'Products.ProductStore',
			'foreignKey' => 'store_id',
            ),
		'ProductBrand' => array(
			'className' => 'Products.ProductBrand',
			'foreignKey' => 'product_brand_id',
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

    public $hasAndBelongsToMany = array(
        'Category' => array(
            'className' => 'Categories.Category',
       		'joinTable' => 'categorized',
            'foreignKey' => 'foreign_key',
            'associationForeignKey' => 'category_id',
    		'conditions' => 'Categorized.model = "Product"',
    		// 'unique' => true,
            ),
        'CategoryOption' => array(
            'className' => 'Categories.CategoryOption',
       		'joinTable' => 'categorized_options',
            'foreignKey' => 'foreign_key',
            'associationForeignKey' => 'category_option_id',
    		//'unique' => true,
            ),
        );
    
	public function __construct($id = null, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);

		$this->categorizedParams = array('conditions' => array($this->alias.'.parent_id' => null));
		$this->order = array($this->alias . '.' . 'price');
	}
    
/**
 * Before Save method
 * 
 * @param type $options
 * @return boolean
 */
    public function beforeSave($options) {
        $this->Behaviors->attach('Galleries.Mediable'); // attaching the gallery behavior here, because the ProductParent was causing a problem making $Model->alias = 'ProductParent', in the behavior.
		$this->data = $this->_cleanAddData($this->data);
        return true;
    }
    
/**
 * 
 * @param array $queryData
 * @return array
 */
	public function beforeFind($queryData) {
		// always limit products by the user role if the price matrix is used
        if (class_exists('CakeSession')) {
            $userRoleId = CakeSession::read('Auth.User.user_role_id');
            $queryData['contain']['ProductPrice']['conditions']['ProductPrice.user_role_id'] = $userRoleId;

            // stop filtering the price if we use fields and price isn't included
            $this->filterPrice = !empty($queryData['fields']) && is_array($queryData['fields']) && (array_search('price', $queryData['fields']) === false && array_search('Product.price', $queryData['fields']) === false) ? false : true;
        }
		return $queryData;
	}

/**
 * 
 * @param array $results
 * @param int $primary
 * @return array
 */
	public function afterFind($results, $primary) {
		// only play with prices if the find is not list type (which doesn't need prices)
		if (!empty($this->filterPrice)) {
			// this is for the find "all" type where the data format is $results[0]['Product']['id'];
			if (isset($results[0]['Product']) && !empty($results[0]['Product'])) {
				$results = $this->cleanItemsPrices($results);
			}

			// this is for single products being returned
			if (isset($results['Product']['id']) && !empty($results['Product']['id'])) {
				$results = $this->cleanItemPrice($results);
			}
		}

		$i = 0;
		foreach ($results as $result) {
			$i = $i + 1;
			if(!empty($result['Product']['arb_settings'])) {
				// set arb back to input values
				$arbSettingsArray = unserialize($result['Product']['arb_settings']);
				$arbSettingsString = '';
				foreach ($arbSettingsArray as $key => $value ){
					$arbSettingsString .= "$key = $value\n";
				}
				$results[$i]['Product']['arb_settings'] = $arbSettingsString ;
			}
		}

		return $results;
	}

/**
 * Handles the adding of products and any additional functions that need to run with it.
 *
 * @todo		We need to change this from a random sku generator to something that checks for existence of the sku already, and throws an error back to the controller if it does.
 * @todo		Make it a products plugin setting for whether skus will be randomly generated or not.
 * @todo 		Not sure why we have deleteAll there, when I believe anytime you save a HABTM model it will delete all automatically.  If its not working without that, then there is a problem with the relationships.
 * @todo		The manual items that come after saveAll should be verified and roll back the item if its not updated correctly.
 * @todo		This function should use the throw exception syntax, and the controller should catch.
 */
	//public function save($data = null, $params = array()) {
	//	return parent::save($data, $params);
        
        
//          This needs to be in a behavior too
//			if (isset($data['Product']['id']) || $imageSaved) {
//				// this is how the categories data should look when coming in.
//				if (isset($data['Category']['Category'][0])) {
//					$categorized = array('Product' => array('id' => array($this->id)));
//					foreach ($data['Category']['Category'] as $catId) {
//						$categorized['Category']['id'][] = $catId;
//					}
//					$this->Category->categorized($categorized, 'Product');
//				}
//
//				if(isset($data['CategoryOption'])) {
//					$this->CategoryOption->categorized_option($data, 'Product');
//				}
//				$ret = true;
//			} else {
//				$this->delete($this->id);
//			}
	//}
    
    

/**
 * Cleans data for adding
 *
 * @access protected
 * @param array
 * @return array
 */
 	protected function _cleanAddData($data) {
		if (!empty($data['Product']['arb_settings'])) {
			$data['Product']['arb_settings'] = serialize(parse_ini_string($this->request->data['Product']['arb_settings']));
		}

		if(!empty($data['Product']['payment_type'])) {
			$data['Product']['payment_type'] = implode(',', $this->request->data['Product']['payment_type']);
		}

		if (empty($data['Product']['sku'])) {
			$data['Product']['sku'] = rand(10000, 99000); // generate random sku if none exists
		}
		return $data;
	}

/**
 * Cleans products
 *
 * If the advanced price matrix exists, then we set the price using that.
 * If no price matrix exists we just use the default price
 * If price matrix is there, but empty (because the userRoleId weeded it out in the controller) we remove the item.
 *
 * @param {array} 		Typical structured data array
 */
	public function cleanItemsPrices($products) {
		$i = 0;
		// get the price for the logged in user
        $productPriceCount = 0;
		foreach ($products as $product) {
			// this is to check for single product.
			if (isset($product['Product']['id']) && !empty($product['Product']['id'])) {
				unset($productPriceCount);
				// count the prices to see if the price matrix was used at all
				$productPriceCount = $this->ProductPrice->find('count', array('conditions' => array(
					'ProductPrice.product_id' => $product['Product']['id'],
					)));
				// remove the default price if matrix was used
				if ($productPriceCount > 0) {
					unset($products[$i]['Product']['price']);
				}
				$products[$i] = $this->cleanItemPrice($product);

				// remove the product all together if the price matrix was used, and price is 0 for this user's role
				if (empty($products[$i]['Product']['price'])) {
					unset($products[$i]);
				}
				$i++;
            }
		}
		return $products;
	}

/**
 * Cleans a single products
 *
 * If the advanced price matrix exists, then we set the price using that, other wise leave the default price intact.
 *
 * @param {array} 		Typical structured data array
 * @todo				This price with Zuha::enum() thing is not very reliable, as the names are hard coded.  Haven't thought of a good way around it quite yet, but no one is using multiple or sales prices so removing giving it an easy default for now.  But if we use more prices in the matrix than we need to, its going to cause the wrong prices to be spit out.
 */
	public function cleanItemPrice($product) {
		if (!empty($product['ProductPrice'][0])) {
			foreach ($product['ProductPrice'] as $price) {
				// set the price in the original products to user role price
				$product['Product']['price'] = ZuhaInflector::pricify($price['price']);
			}
		}

		if (!empty($product['Product']['price'])) {
			$product['Product']['price'] = ZuhaInflector::pricify($product['Product']['price']);
		}

		unset($product['ProductPrice']); // its not needed now
		return $product;
	}

/**
 * Payment Options
 *
 * @access public
 * @param void
 * @return string
 */
	public function paymentOptions() {
		if(defined('__ORDERS_ENABLE_SINGLE_PAYMENT_TYPE') && defined('__ORDERS_ENABLE_PAYMENT_OPTIONS')) {
			return unserialize(__ORDERS_ENABLE_PAYMENT_OPTIONS);
		} else {
			return null;
		}
	}

	
	/**
	 * This trims an object, formats it's values if you need to, and returns the data to be merged with the Transaction data.
	 * @param string $key
	 * @return array The necessary fields to add a Transaction Item
	 */
	public function mapTransactionItem($key) {
	    
	    $itemData = $this->find('first', array('conditions' => array('id' => $key)));
	    
	    $fieldsToCopyDirectly = array(
		'name',
		'weight',
		'height',
		'width',
		'length',
		'shipping_type',
		'shipping_charge',
		'payment_type',
		'is_virtual'
	    );
	    
	    foreach($itemData['Product'] as $k => $v) {
		if(in_array($k, $fieldsToCopyDirectly)) {
		    $return['TransactionItem'][$k] = $v;
		}
	    }
	    
	    //$itemData['TransactionItem'] = $itemData['Product'];
	    
	    //unset($itemData['Product']);
	    return $return;
	}
	
}