<?php
App::uses('ProductsAppController', 'Products.Controller');
/**
 * Products Controller
 *
 * Handles the logic for products.
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
 * @subpackage    zuha.app.plugins.products
 * @since         Zuha(tm) v 0.0.1
 * @license       GPL v3 License (http://www.gnu.org/licenses/gpl.html) and Future Versions
 */
class ProductsController extends ProductsAppController {

/**
 * Name
 *
 * @var string
 */
	public $name = 'Products';

/**
 * Allowed Actions
 *
 * @var array
 */
	public $allowedActions = array('get_attribute_values');

/**
 * Uses
 *
 * @var string
 */
	public $uses = 'Products.Product';
	

/**
 * Ecommerce dashboard.
 *
 */
	public function dashboard(){
        $Transaction = ClassRegistry::init('Transactions.Transaction');
        $TransactionItem = ClassRegistry::init('Transactions.TransactionItem');
        $this->set('counts', $counts = array_count_values(Set::extract('/Transaction/status', $Transaction->find('all'))));
		$this->set('statsSalesToday', $Transaction->salesStats('today'));
		$this->set('statsSalesThisWeek', $Transaction->salesStats('thisWeek'));
		$this->set('statsSalesThisMonth', $Transaction->salesStats('thisMonth'));
		$this->set('statsSalesThisYear', $Transaction->salesStats('thisYear'));
		$this->set('statsSalesAllTime', $Transaction->salesStats('allTime'));
		$this->set('transactionStatuses', $Transaction->statuses());
		$this->set('itemStatuses', $TransactionItem->statuses());
		$this->set('title_for_layout', __('Ecommerce Dashboard'));
		$this->set('page_title_for_layout', __('Ecommerce Dashboard'));
        $this->layout = 'default';
	}

/**
 * Index method.
 *
 * @param void
 * @return void
 */
	public function index($type = null) {

		// setup paginate
//		$this->paginate['contain']['ProductPrice']['conditions']['ProductPrice.user_role_id'] = $this->userRoleId;
//		$this->paginate['conditions']['OR'] = array(
//			array('Product.ended >' => date('Y-m-d h:i:s')),
//			array('Product.ended' => null),
//			array('Product.ended' => '0000-00-00 00:00:00')
//		);

		if ($type !== null) {
			switch ($type) {
				case ('auction') :
					// filter to only "auction" items 
					$this->paginate['conditions'][] = array('Product.started <' => date('Y-m-d H:i:s'));
					$this->paginate['conditions'][] = array('Product.ended >' => date('Y-m-d H:i:s'));
					$this->view = 'auction_index';
					break;
			}
		}
		
        $this->paginate['contain'][] = 'Option';
		$this->paginate['conditions']['Product.parent_id'] = null;

		$this->set('products', $products = $this->paginate());
		$this->set('displayName', 'name');
		$this->set('displayDescription', 'summary'); 
		$this->set('showGallery', true);
		$this->set('galleryForeignKey', 'id');
		
		return $products;
	}

/**
 * Category method.
 *
 * @param void
 * @return void
 */
	public function category($categoryId = null) {
		if (!empty($categoryId)) {
			$this->paginate['joins'] = array(array(
				'table' => 'categorized',
				'alias' => 'Categorized',
				'type' => 'INNER',
				'conditions' => array(
					"Categorized.foreign_key = Product.id",
					"Categorized.model = 'Product'",
					"Categorized.category_id = '{$categoryId}'",
				),
			));
			$this->paginate['contain'][] = 'Category';
		}
		$this->view = 'index';
		return $this->index();
	}


/**
 * It is imperative that we document this function
 * 
 * @todo make this more isolated and modular (its calling multiple related models from other plugins)
 */
	public function view($id = null, $child = false) {
		$this->Product->id = $id;
		if (!$this->Product->exists()) {
			throw new NotFoundException(__('Invalid product'));
		}
              
		$product = $this->Product->find('first' , array(
			'conditions' => array(
				'Product.id' => $id
				),
			'contain' => array(
				'ProductBrand' => array(
					'fields' => array(
						'name',
						'id')
					),
				'ProductPrice' => array(
					'conditions' => array(
						'ProductPrice.user_role_id' => $this->userRoleId,
						),
					),
				'Children' => array(
//                    'Gallery'
                    ),
                'Gallery',
                'Parent',
				'Owner'
				),
			));
        !empty($product['Parent']['id']) && empty($child) ?  $this->redirect(array($product['Parent']['id'])) : null; // redirect to parent
        
        $productsOptions = $this->Product->Option->ProductsOption->find('all', array('conditions' => array('ProductsOption.product_id' => Set::extract('/id', $product['Children'])), 'contain' => 'Option', 'order' => array('Option.parent_id', 'Option.name')));
        $options = array();
        if (!empty($productsOptions)) {
            foreach ($productsOptions as $productsOption) {
                $options[$productsOption['ProductsOption']['product_id']][$productsOption['Option']['id']] = $productsOption['Option']['name']; 
            }
        }
		$product = $this->Product->cleanItemPrice($product, $this->userRoleId);
		$this->set('title_for_layout', $product['Product']['name']);
		$this->set(compact('product', 'options'));
        return $product;
	}
    
    protected function _viewChild($parentId) {
        
    }
    
    public function viewAuction ($id = null, $child = null) {
    	$this->view = 'view_auction';

    	$this->Product->id = $id;
    	if (!$this->Product->exists()) {
    		throw new NotFoundException(__('Invalid product'));
    	}
    	
    	$product = $this->Product->find('first' , array(
    			'conditions' => array(
    					'Product.id' => $id
    			),
    			'contain' => array(
    					'ProductBid',
    					'ProductBrand' => array(
    							'fields' => array('name', 'id')
    					),
    					'ProductPrice' => array(
    							'conditions' => array(
    									'ProductPrice.user_role_id' => $this->userRoleId
    							)
    					),
    					'Children',
    					'Gallery',
    					'Parent',
    					'Owner'
    			)
    	));
    	!empty($product['Parent']['id']) && empty($child) ?  $this->redirect(array($product['Parent']['id'])) : null; // redirect to parent
    	
    	// sort bids by highest -> lowest amount
    	usort($product['ProductBid'], function($a, $b) {
    		return $a['amount'] < $b['amount'];
    	});
    	
    	$productsOptions = $this->Product->Option->ProductsOption->find('all', array('conditions' => array('ProductsOption.product_id' => Set::extract('/id', $product['Children'])), 'contain' => 'Option', 'order' => array('Option.parent_id', 'Option.name')));
    	$options = array();
    	if (!empty($productsOptions)) {
    		foreach ($productsOptions as $productsOption) {
    			$options[$productsOption['ProductsOption']['product_id']][$productsOption['Option']['id']] = $productsOption['Option']['name'];
    		}
    	}
    	$product = $this->Product->cleanItemPrice($product, $this->userRoleId);
    	$this->set('title_for_layout', $product['Product']['name']);
    	$this->set(compact('product', 'options'));
    	
    }

/**
 * Add method
 * * 
 * @param string $type [empty = default, arb, virtual, virtual_arb, auction]
 * @param string $parentId
 */
	public function add($type = 'default', $parentId = null) {
        $function = '_add' . ucfirst($type);
        
        $this->set('productBrands', $this->Product->ProductBrand->find('list'));
    	if (in_array('Categories', CakePlugin::loaded())) {
        	$this->set('categories', $this->Product->Category->generateTreeList());
		}
    	//$this->set('paymentOptions', $this->Product->paymentOptions());
        
        return $this->$function($parentId);
	}
    
    protected function _addDefault($parentId = null) {
    	if (!empty($this->request->data)) {
			if ($this->Product->saveAll($this->request->data)) {
				$this->Session->setFlash(__('Product saved.'));
				$this->redirect(array('action' => 'edit', $this->Product->id));
            } 
		}
		$this->set('page_title_for_layout', __('Create a Product'));
		$this->set('title_for_layout', __('Add Product Form'));
        $this->layout = 'default';
        $this->view = 'add_default';
        return !empty($parentId) ? $this->_addDefaultChild($parentId) : true;
    }
	
    protected function _addArb($parentId = null) {
    	if (!empty($this->request->data)) {
			if ($this->Product->saveAll($this->request->data)) {
				$this->Session->setFlash(__('Product saved.'));
				$this->redirect(array('action' => 'edit', $this->Product->id));
            } 
		}
		$this->set('page_title_for_layout', __('Create an ARB Product'));
		$this->set('title_for_layout', __('Add Product Form'));
        $this->layout = 'default';
        $this->view = 'add_arb';
        return !empty($parentId) ? $this->_addDefaultChild($parentId) : true;
    }
    
    
    protected function _addDefaultChild($parentId) {
        $this->request->data = $this->Product->find('first', array('conditions' => array('Product.id' => $parentId), 'contain' => array('Option' => 'Children')));
        unset($this->request->data['Product']['sku']);
    	$this->set('page_title_for_layout', __('Create a %s Variant', $this->request->data['Product']['name']));
		$this->set('title_for_layout', __('Add Product Variant Form'));
        $this->layout = false; // required for modal to work (but causes the standard view page not to)
        $this->view = 'add_default_child';
    }
    
    protected function _addAuction($parentId = null) {
    	if (!empty($this->request->data)) {
    		if ($this->Product->saveAll($this->request->data)) {
    			$this->Session->setFlash(__('Product saved.'));
    			$this->redirect(array('action' => 'edit', $this->Product->id));
    		}
    	}
    	$this->set('page_title_for_layout', __('Create an Product for Auction'));
    	$this->set('title_for_layout', __('Add Product Form'));
    	$this->layout = 'default';
    	$this->view = 'add_auction';
    	return !empty($parentId) ? $this->_addDefaultChild($parentId) : true;
    }

    
/**
 * Edit method
 *
 * @access public
 * @param string
 * @param type $id
 * @throws NotFoundException
 */
	public function edit($id = null, $child = false) {
        if (!empty($child)) {
            return $this->_editChild($id);
        }
		if (!empty($this->request->data)) {
			if ($this->Product->saveAll($this->request->data)) {
				$this->Session->setFlash(__('Product saved.'));
				if ( isset($this->request->data['SaveAndContinue']) ) {
					$this->redirect(array('action' => 'edit', $this->Product->id));
				} else {
					$this->redirect(array('action' => 'view', $this->Product->id));
				}
            }
		}
		$this->Product->id = $id;
		if (!$this->Product->exists()) {
			throw new NotFoundException(__('Invalid product'));
		}
        
        $this->request->data = $this->Product->find('first', array(
            'conditions' => array(
                'Product.id' => $id
                ),
            'contain' => array(
                'Option',
                'Gallery',
                'Parent',
                'Children' => array(
                    'Option' => array(
                        'order' => array(
                            'Option.parent_id' => 'ASC',
                            'Option.name' => 'ASC',
                            )
                        ),
                    ),
                ),
            ));
        !empty($this->request->data['Parent']['id']) ?  $this->redirect(array($this->request->data['Parent']['id'])) : null; // redirect to parent
        $this->set('productBrands', $this->Product->ProductBrand->find('list'));
        $this->set('categories', $this->Product->Category->generateTreeList());

		$selectedCategories =
				$this->Product->Category->Categorized->find('all', array(
					'conditions' => array(
						'Categorized.model'=>$this->Product->alias,
						'Categorized.foreign_key'=>$this->Product->id
						),
					'contain' => array('Category')
					));
		$this->set('selectedCategories',  Set::extract($selectedCategories, '/Category/id'));

        $this->set('existingOptions', $existingOptions = Set::combine($this->request->data['Option'], '{n}.ProductsProductOption.option_id', '{n}.name'));
        $this->set('options', array_diff($this->Product->Option->find('list', array('conditions' => array('OR' => array(array('Option.parent_id' => ''), array('Option.parent_id' => null))))), $existingOptions));
		//$this->set('paymentOptions', $this->Product->paymentOptions());

		$this->set('page_title_for_layout', __('Edit %s ', $this->request->data['Product']['name']));
		$this->set('title_for_layout', __('Edit %s ', $this->request->data['Product']['name']));
        $this->layout = 'default';
	}


	public function editArb ($id = null, $child = null) {
		return $this->edit($id, $child);
	}
    
/**
 * 
 * @param type $id
 * @throws NotFoundException
 */
    protected function _editChild($id) {
		if (!empty($this->request->data)) {
			if ($this->Product->saveAll($this->request->data)) {
				$this->Session->setFlash(__('Product saved.'));
				$this->redirect(array('action' => 'view', $this->Product->id));
            }
		}
		$this->Product->id = $id;
		if (!$this->Product->exists()) {
			throw new NotFoundException(__('Invalid product'));
		}
        $this->Product->contain(array('Gallery', 'Option', 'Parent'));
        $this->request->data = $this->Product->read(null, $id);
       
        $this->set('productBrands', $this->Product->ProductBrand->find('list'));
        $this->set('existingOptions', null);
        $this->set('options', null);

		$this->set('page_title_for_layout', __('Edit %s <small>a variant of %s</small>', $this->request->data['Product']['name'], $this->request->data['Parent']['name']));
		$this->set('title_for_layout', __('Edit %s <small>a variant of %s</small>', $this->request->data['Product']['name'], $this->request->data['Parent']['name']));        
    }


/**
 * update function is used for create child products with category options selected
 * 
 * @todo I'm relatively sure we need to get rid of this crap
 */
	public function update($parentId = null) {
		if (!empty($this->request->data)) {
			$data = $this->Product->find('first', array(
				'conditions' => array(
					'Product.id' => $this->request->data['Product']['parent_id']
					),
				'recursive' => 2,
				'contain' => array(
					'ProductStore.id',
					'Category.id',
					'ProductBrand',
					'CategoryOption',
					'ProductPrice'
					)
				));

			foreach ($data['Category'] as $k => $val ){
				$data['Category'][$k] = $data['Category'][$k]['id'];
			}
			// setting values to parent values
			foreach ($this->request->data['Product'] as $fieldName => $fieldValue) {
				if(!empty($fieldValue)) {
					$data['Product'][$fieldName] = $fieldValue ;
				}
			}
			$data['Product']['id'] = '' ;
			$data['GalleryImage'] = $this->request->data['GalleryImage'] ;
			$data['CategoryOption'] = $this->request->data['CategoryOption'];

			// create new CI
			if ($this->Product->save($data, $this->Auth->user('id'))) {
				$this->redirect(array('action' => 'edit', $this->request->data['Product']['parent_id']));
			} else {
				$this->Session->setFlash(__('New attribute save failed.', true));
				$this->redirect(array('action' => 'update', $this->request->data['Product']['parent_id']));
			}
		}

		$product = $this->Product->find('first', array(
			'conditions' => array(
				'Product.id' => $parentId,
				),
			'contain' => array(
				'Children' => array(
					'CategoryOption',
					),
				)
			));
		$this->set(compact('product'));
	}


/**
 *
 * @todo I'm relatively sure we need to get rid of this crap
 */
	public function get_product($id = null) {
		$this->layout = false;
		if (!empty($id)) {
			$this->request->data = $this->Product->find('first', array(
				'conditions' => array(
					'Product.id' => $id
					),
				'recursive' => 2,
				'contain' => array(
					'ProductStore.id',
					'Category.id',
					'ProductBrand',
					'CategoryOption',
					'ProductPrice'
					)
				));
			// remodifying data to bring support for controls
			$this->request->data['ProductStore']['id'] = array('0' => $this->request->data['ProductStore']['id']);
			$this->request->data['Category'] = Set::extract('/Category/id', $this->request->data);
			$catOptions = array();
				$catOptions = $this->Product->Category->CategoryOption->find('threaded', array(
				'conditions'=>array('CategoryOption.category_id' => $this->request->data['Category']),
				'order' => 'CategoryOption.type'
			));
			$this->set('options', $catOptions);
		}
	}


/**
 * get_stock function is used to get the stock of products
 * based on the different options
 *
 * @todo I'm relatively sure we need to get rid of this crap
 */
	public function get_stock() {
		if (!empty($this->request->data)) {
			$count_options = 0 ;
			$category_ids = array();
			foreach ($this->request->data['CategoryOption'] as $k => $val) {
				if (is_array($val)) {
					$count_options += count($val);
					$category_ids = array_merge($category_ids, $val);
				} else if (!empty($val)) {
					$count_options += 1;
					$category_ids[$k] = $val;
				}
			}
			App::Import('Model', 'CategorizedOption');
			$category = new CategorizedOption();
			$productStores = $category->find('all', array(
					'fields' => array('count(*), foreign_key'),
					'conditions'=> array('category_option_id' => $category_ids),
					'group' => "foreign_key having count(*) ={$count_options}"
					));
			$id = array();
			foreach ($productStores as $k => $val) {
					$id[$k] = $val['CategorizedOption']['foreign_key'];
			}
			$products = $this->Product->find('all', array('conditions' => array(
					'Product.id' => $id
			)));
			$this->set(compact('products'));
		}

		$this->set('options', $this->Product->Category->CategoryOption->find('threaded', array(
				'conditions'=>array('CategoryOption.category_id' => $this->request->params['named']['category_id']),
				'order'=>'CategoryOption.type'
		)));

	}


	public function delete($id = null, $optionId = null) {
		$this->Product->id = $id;
		if (!$this->Product->exists()) {
			throw new NotFoundException(__('Invalid product'));
		}
        if (!empty($optionId)) {
            if ($this->Product->deleteChildByOptionType($id, $optionId)) {
               $this->Session->setFlash(__('Option deleted'));
            }
        	$this->redirect($this->referer());
        } else {
    		if ($this->Product->delete($id)) {
    			$this->Session->setFlash(__('Item deleted'));
    		}
        	$this->redirect(array('action' => 'index'));
        }
	}
    
    
    public function categories($parentId = null) {
        if (!empty($this->request->data['Option'])) {
            if ($this->Product->Option->save($this->request->data)) {
                $this->Session->setFlash(__('Option saved'));
            }
        }
        if (!empty($this->request->data['Category'])) {
            if ($this->Product->Category->save($this->request->data)) {
                $this->Session->setFlash(__('Category saved'));
            }
        }
		
		$conditions = !empty($parentId) ? array('conditions' => array('Category.parent_id' => $parentId)) : null;
        $categories = $this->Product->Category->find('threaded', $conditions);
        $options = $this->Product->Option->find('threaded');
        
        $this->set('parentCategories', Set::combine($categories, '{n}.Category.id', '{n}.Category.name'));
        $this->set('parentOptions', Set::combine($options, '{n}.Option.id', '{n}.Option.name'));
        $this->set(compact('categories', 'options'));
        $this->set('page_title_for_layout', __('Product Categories & Options'));
		$this->layout = 'default';
		return $categories; // used in element Categories/categories
    }

/**
 * @todo I'm relatively sure we need to get rid of this crap
 */
	public function get_items($productBrandId = null) {
		if ($productBrandId) {
			$this->set('items', $this->Product->find('list', array(
					'conditions' => array('Product.product_brand_id'=>$productBrandId))));
		}
	}


/**
 * Set the variables for n number of random products for display in the random element.
 *
 * @param {int}		The number of items you want to pull.
 */
	public function random_product($count = 3, $productBrandId = null) {
        $conditions = ($productBrandId) ? array('Product.product_brand_id' => $productBrandId) : false;
		$products = $this->Product->find('all', array(
			'limit' => $count,
            'conditions' => $conditions,
			'order' => array('RAND()')
			));
		if (!empty($products) && isset($this->request->params['requested'])) {
        	return $products;
        } else {
			return false;
		}
	}

/**
 * function deal_a_day() uses to find deal of day according to
 * current dateTime
 */
	public function deal_a_day() {
		$options['order'] = array('Product.ended ASC');
		$options['conditions'] = array('Product.ended >' => date('Y-m-d h:i:s'));
		$options['conditions'] = array('Product.parent_id' => null);
		$options['contain'] = array('ProductBrand', 'Gallery' => array('GalleryImage'));
		$dealItem = $this->Product->find('first', $options);
		if (empty($dealItem)) {
			$this->Session->setFlash(__('No Item Is Live', true));
		} else {
			return $dealItem;
		}
	}


/**
 * get attribute values according to selected options
 *
 * @todo I'm relatively sure we need to get rid of this crap
 */
	public function get_attribute_values() {

		$this->layout = false;
		$this->autoRender = false;

		// remove null entries
		$category_options = array_filter($this->request->data['CategoryOption']);

		// clicked category option
		//get products children bases on parent_id
		$productId = !empty($this->request->data['TransactionItem']['parent_id']) ? $this->request->data['TransactionItem']['parent_id'] : $this->request->data['TransactionItem']['product_id'];
		$ci = $this->Product->find('list', array(
				'fields' => array('price', 'stock', 'id'),
				'conditions'=>array('Product.parent_id' => $productId ),
			));
		$children = array_keys($ci);

		$CO = ClassRegistry::init('Categories.CategorizedOption');

		$conditions = array('CategorizedOption.foreign_key' => $children);
		if ($category_options) {
			$conditions ['OR'] = array('category_option_id' => $category_options);
		}

		// all CI with options as clicked radio buttons and foreign key
		$fkey_list = $CO->find('list', array(
				'fields' => array('foreign_key'),
				'conditions'=> $conditions,
				//'conditions'=> array(
				//	'OR' => array('category_option_id' => $category_options),
				//	'CategorizedOption.foreign_key' => $children),
				));

		$co_ids = array();

		if (count($fkey_list) > 0) {
			// only do when there is a children of CI
			// categirt option ids to be shown activated
			 $co_ids = $CO->find('list', array(
			 		'fields' => array('category_option_id'),
			 		'conditions'=> array('foreign_key' => $fkey_list)
			 	 ));
		}

		//unique categorized options
		$data['CategorizedOption'] = array_unique($co_ids);

		if ($category_options) {

			// data of CI with options as selected radio buttons
			$fk_list = $CO->find('list', array(
					'fields' => array('foreign_key'),
					'conditions'=> array(
							'category_option_id' => $category_options,
							'CategorizedOption.foreign_key' => $children
					),
					'group' => "foreign_key having count(*) =".count($category_options)
				));
			if (count($fk_list) == 1) {
				foreach ($fk_list as $fk) {
					$data['Product']['id'] = $fk;
					if (is_array($ci[$fk])) {
						foreach($ci[$fk] as $price => $stock){
							$data['Product']['stock'] = $stock;
							$data['Product']['price'] = $price;
						}

					}
				}
			}
		}

		echo json_encode($data);
	}
}
