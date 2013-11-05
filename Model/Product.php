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
		'price' => array('notempty'),
        );

	public $actsAs = array(
		'Tree' => array('parent' => 'parent_id'),
		'Metable',
        );

	public $order = '';

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	public $hasMany = array(
        // This was a conflict, that we are not sure if it should be here or not.  12/5/2012 RK
		//'TransactionItem' => array(
		//	'className' => 'Transactions.TransactionItem',
		//	'foreignKey' => 'foreign_key',
		//	'dependent' => false,
        //   ),
		'ProductPrice' => array(
			'className' => 'Products.ProductPrice',
			'foreignKey' => 'product_id',
			'dependent' => true,
			'order' => 'ProductPrice.user_role_id asc'
            ),
		'Children' => array(
			'className' => 'Products.Product',
			'foreignKey' => 'parent_id',
			'dependent' => true,
            ),
		'ProductBid' => array(
			'className' => 'Products.ProductBid',
			'foreignKey' => 'product_id',
			'dependent' => false,
			'order' => array('amount' => 'DESC'),
			'limit' => 1
			
			),
			
        );
        
    public $hasAndBelongsToMany = array(
        'Option' => array(
            'className' => 'Products.Option',
       		'joinTable' => 'products_product_options',
            'foreignKey' => 'product_id',
            'associationForeignKey' => 'option_id',
    		//'unique' => false,
	        ),
        'Winner' => array(
			'className' => 'Users.User',
			'joinTable' => 'product_bids',
			'foreignKey' => 'product_id',
			'associationForeignKey' => 'user_id',
			'order' => array('amount' => 'DESC'),
			'limit' => 1
			),
       );

	//products association.
	public $belongsTo = array(
		'Parent'=>array(
			'className' => 'Products.Product',
			'foreignKey' => 'parent_id',
			'counterCache' => 'children',
			'counterScope' => array('parent_id NOT' => null),
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
		'Creator' => array(
			'className' => 'Users.User',
			'foreignKey' => 'creator_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
            ),
        );
    
	public function __construct($id = null, $table = null, $ds = null) {
		if(CakePlugin::loaded('Media')) {
			$this->actsAs[] = 'Media.MediaAttachable';
		}
		if (CakePlugin::loaded('Categories')) {
			$this->hasAndBelongsToMany['Category'] = array(
	            'className' => 'Categories.Category',
	       		'joinTable' => 'categorized',
	            'foreignKey' => 'foreign_key',
	            'associationForeignKey' => 'category_id',
	    		'conditions' => array('Categorized.model' => 'Product'),
	    		// 'unique' => true,
	            );
			$this->actsAs['Categories.Categorizable'] = array('modelAlias' => 'Product');
		}
		if (CakePlugin::loaded('Maps')) {
			// address field is in use in canopy, make sure it works there if changing the field name
			/** @see MapableBehavior::beforeSave() **/
			$this->actsAs['Maps.Mapable'] = array('modelAlias' => 'Product', 'addressField' => '!location');
		}
		if(CakePlugin::loaded('Transactions')) {
			$this->actsAs[] = 'Transactions.Buyable';
		}

		parent::__construct($id, $table, $ds); // this order is imortant
		
		$this->categorizedParams = array('conditions' => array($this->alias.'.parent_id' => null));
		$this->order = array($this->alias . '.' . 'price');
	}
    
/**
 * Before Save method
 * 
 * @param type $options
 * @return boolean
 */
    public function beforeSave($options = array()) {
        // commented out when switching to Media.MediaAttachable... $this->Behaviors->attach('Galleries.Mediable'); // attaching the gallery behavior here, because the ProductParent was causing a problem making $Model->alias = 'ProductParent', in the behavior.
		$this->data = $this->_newOptions($this->data);
        $this->data = $this->_cleanAddData($this->data);
        return parent::beforeSave($options);
    }
    
/**
 * After save method
 * 
 * @param type $options
 * @return boolean
 */
    public function afterSave($created, $options = array()) {
    	$foreignKey = $this->field('foreign_key', array('id' => $this->id));
		if (empty($foreignKey)) {
			$this->saveField('foreign_key', $this->id, array('validate' => false, 'callbacks' => false, 'counterCache' => false));
		}
        return parent::afterSave($created, $options);
    }
    
/**
 * 
 * @param array $queryData
 * @return array
 */
	public function beforeFind($queryData) {
		parent::beforeFind($queryData);
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
	public function afterFind($results, $primary = false) {
		// only play with prices if the find is not list type (which doesn't need prices)
		if (!empty($this->filterPrice)) {
			// this is for the find "all" type where the data format is $results[0]['Product']['id'];
			if (isset($results[0][$this->alias]) && !empty($results[0][$this->alias])) {
				$results = $this->cleanItemsPrices($results);
			}

			// this is for single products being returned
			if (isset($results[$this->alias]['id']) && !empty($results[$this->alias]['id'])) {
				$results = $this->cleanItemPrice($results);
			}
		}

		// this was causing problems for the transfer from product to transaction item
		// if you need something like this back, then make sure when you add an arb item
		// to cart that it transfers the arb settings to the transaction item correctly
		// and leave a comment about where this is needed, because it seems pointless like it is.
		// side note, don't use $i = 0, like this, just use a for loop instead of a foreach loop
		// $i = 0;
		// foreach ($results as $result) {
			// $i = $i++;
			// if(!empty($result['Product']) && !empty($result['Product']['arb_settings'])) {
				// // set arb back to input values
				// $arbSettingsArray = unserialize($result['Product']['arb_settings']);
				// $arbSettingsString = '';
				// foreach ($arbSettingsArray as $key => $value ){
					// $arbSettingsString .= "$key = $value\n";
				// }
				// $results[$i]['Product']['arb_settings'] = $arbSettingsString ;
			// }
		// }
        
		return parent::afterFind($results, $primary = false);
	}
	
	

/**
 * Cleans data for adding
 *
 * @access protected
 * @param array
 * @return array
 */
 	protected function _cleanAddData($data) {
		// order is important
		if (empty($data[$this->alias]['price']) && !empty($data[$this->alias]['arb_settings']['PaymentAmount'])) {
			$data[$this->alias]['price'] = $data[$this->alias]['arb_settings']['PaymentAmount'];
		}
		
		if (!empty($data[$this->alias]['arb_settings'])) {
			// serialize the data
			$data[$this->alias]['arb_settings'] = serialize($this->data[$this->alias]['arb_settings']);
		}

		if(!empty($data[$this->alias]['payment_type'])) {
			$data[$this->alias]['payment_type'] = implode(',', $this->data[$this->alias]['payment_type']);
		}

		if (!empty($data[$this->alias]['name']) && empty($data[$this->alias]['sku'])) {
			// generate random sku if none exists
			$data[$this->alias]['sku'] = rand(10000, 99000);
		}
        
        if (!empty($data['Option']['Option'][0]) && !empty($data[$this->alias]['id'])) {
            // need to manually add existing options so they aren't auto-deleted
            $existingOptions = Set::extract('/ProductsOption/option_id', $this->Option->ProductsOption->find('all', array('conditions' => array('ProductsOption.product_id' => $data[$this->alias]['id']), 'callbacks' => false)));
            $data['Option']['Option'] = array_merge($data['Option']['Option'], $existingOptions);
        }

		if (empty($data['Product']['model'])) {
			$data['Product']['model'] = 'Product';
		}
		
		return $data;
	}
    
/**
 * Someone please comment what this is for!!!
 */
    protected function _newOptions($data) {
        $output = !empty($data['Option']['Option']) ? array('Option' => array('Option' => $data['Option']['Option'])) : array();
        unset($data['Option']['Option']);
        if (!empty($data['Option'])) {
            $i = 0;
            foreach ($data['Option'] as $option) {
                if (!empty($option['name'])) {
                    $this->Option->create();
                    if ($this->Option->save(array('Option' => $data['Option'][$i]))) {
                        $data['Option']['Option'][] = $this->Option->id;
                        unset($output['Option']['Option'][$i]);
                    }
                }
                unset($data['Option'][$i]);
                $i++;
            }
        }
        $data = array_merge_recursive($data, $output);
        return $data;
    }

/**
 * Cleans products' prices
 *
 * If the advanced price matrix exists, then we set the price using that.
 * If no price matrix exists we just use the default price
 * If price matrix is there, but empty (because the userRoleId weeded it out in the controller) we remove the item.
 *
 * @param array $products
 */
	public function cleanItemsPrices($products) {
		$i = 0;
		// get the price for the logged in user
        $productPriceCount = 0;
		foreach ($products as $product) {
			// this is to check for single product.
			if (isset($product[$this->alias]['id']) && !empty($product[$this->alias]['id'])) {
				unset($productPriceCount);
				// count the prices to see if the price matrix was used at all
				$productPriceCount = $this->ProductPrice->find('count', array('conditions' => array(
					'ProductPrice.product_id' => $product[$this->alias]['id'],
					)));
				// remove the default price if matrix was used
				if ($productPriceCount > 0) {
					unset($products[$i][$this->alias]['price']);
				}
				$products[$i] = $this->cleanItemPrice($product);

				// remove the product all together if the price matrix was used, and price is 0 for this user's role
				if (empty($products[$i][$this->alias]['price'])) {
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
				$product[$this->alias]['price'] = $price['price'];
			}
		}

		if (!empty($product[$this->alias]['price'])) {
			$product[$this->alias]['price'] = $product[$this->alias]['price'];
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
 * 
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
    		'arb_settings',
    		'is_virtual',
    		'price'
	        );
	    
	    foreach($itemData[$this->alias] as $k => $v) {
    		if(in_array($k, $fieldsToCopyDirectly)) {
    		    $return['TransactionItem'][$k] = $v;
    		}
	    }
	    return $return;
	}
    
/**
 * Delete Children by Option Type
 * 
 * Find all children of this id, which have the option id and delete them
 * 
 * @param string $id
 * @param string $optionId
 * @throw Exception
 * @return bool
 */
    public function deleteChildByOptionType($id, $optionId) {
    	debug($this->Children->find('all', array(
            'conditions' => array(
                'Children.parent_id' => $id
                ),
            'contain' => array(
                'Option' => array(
                    'conditions' => array(
                        'Option.parent_id' => $optionId
                        )
                    )
                )
            )));
			break;
        $childIds = Set::extract('/Option/ProductsProductOption/product_id', Set::extract('/Option', $this->Children->find('all', array(
            'conditions' => array(
                'Children.parent_id' => $id
                ),
            'contain' => array(
                'Option' => array(
                    'conditions' => array(
                        'Option.parent_id' => $optionId
                        )
                    )
                )
            ))));
        if (!empty($childIds)) {
            if ($this->Option->ProductsOption->deleteAll(array('ProductsOption.option_id' => $optionId, 'ProductsOption.product_id' => $id)) && $this->deleteAll(array($this->alias . '.id' => $childIds))) {
                return true;
            } else {
                throw new Exception(__('Child deletes failed'));
            }
        } else if (!empty($optionId)) {
            if ($this->Option->ProductsOption->deleteAll(array('ProductsOption.option_id' => $optionId, 'ProductsOption.product_id' => $id))) {
                return true;
            } else {
                throw new Exception(__('Option delete failed'));
            }
        }
        return true;
    }

/**
 * origin_afterFind callback
 * 
 * A callback from related plugins which are only related by the abstract model/foreign_key in the db
 * 
 * @param array $results
 */
    public function origin_afterFind(Model $Model, $results = array(), $primary = false) {
    	if ($Model->name == 'TransactionItem') {
	        $ids = Set::extract('/TransactionItem/foreign_key', $results);
	        $products = $this->_concatName($this->find('all', array('conditions' => array($this->alias . '.id' => $ids), 'contain' => array('Option'))));
	        $names = Set::combine($products, '{n}.Product.id', '{n}.Product.name');
	        $i = 0;
	        foreach ($results as $result) {
	            if ($names[$result['TransactionItem']['foreign_key']]) {
	                $results[$i]['TransactionItem']['name'] = $names[$result['TransactionItem']['foreign_key']];
	                $results[$i]['TransactionItem']['_associated']['name'] = $names[$result['TransactionItem']['foreign_key']];
	                $results[$i]['TransactionItem']['_associated']['viewLink'] = __('/products/products/view/%s', $result['TransactionItem']['foreign_key']);
	            }
				$i++;
	        }
	        return $results;
    	}
    }
    
/**
 * Concat Name
 * 
 * Add options to the name of the product
 * 
 * @param array $products
 */
    protected function _concatName($products = array()) {
        if (!empty($products[0][$this->alias]) && !empty($products[0]['Option'])) {
            $i = 0;
            foreach ($products as $product) {
                if (!empty($product['Option'])) {
                    $products[$i][$this->alias]['name'] = __('%s (', $product[$this->alias]['name']);
                    $n = 1;
                    $total = count($product['Option']);
                    foreach ($product['Option'] as $option) {
                        if ($n < $total) {
                            $products[$i][$this->alias]['name'] .= __('%s, ', $option['name']);
                        } else {
                            $products[$i][$this->alias]['name'] .= __('%s)', $option['name']);
                        }
                        $n++;
                    }
                }
                $i++;
            }
        }
        return $products;
    }
	
}