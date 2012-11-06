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
		$this->set('transactionStatuses', ClassRegistry::init('Transactions.Transaction')->statuses());
		$this->set('itemStatuses', ClassRegistry::init('Transactions.TransactionItem')->statuses());
		$this->set('page_title_for_layout', __('Ecommerce Dashboard'));
	}

/**
 * Index method.
 *
 * @param void
 * @return void
 */
	public function index() {
		# setup paginate
		$this->paginate['contain']['ProductPrice']['conditions']['ProductPrice.user_role_id'] = $this->userRoleId;
		$this->paginate['conditions']['OR'] = array(
			array('Product.ended >' => date('Y-m-d h:i:s')),
			array('Product.ended' => null),
			array('Product.ended' => '0000-00-00 00:00:00')
		);
		$this->_namedParameterJoins();
		$this->paginate['conditions']['Product.parent_id'] = null;
		$products = $this->paginate();
		# removes items and changes prices based on user role
		$products = $this->Product->cleanItemsPrices($products, $this->userRoleId);
		$this->set(compact('products'));
	}

/**
 * Named Parameter Joins
 *
 * Handles when there are named parameters to populate the variables for the view correctly.
 *
 * @access protected
 * @param void
 * @return void
 */
	protected function _namedParameterJoins() {
		# category id named
		if (!empty($this->request->params['named']['category'])) {
			$categoryId = $this->request->params['named']['category'];
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
			$contain = $this->paginate['contain'][] = 'Category';
			return $this->paginate;
		} else {
			return null;
		}
	}

/**
 * It is imperative that we document this function
 * @todo make this more isolated and modular (its calling multiple related models from other plugins)
 */
	public function view($id = null) {
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
				'ProductStore' => array(
					'fields' => array(
						'name',
						'id'
						)
					),
				'ProductPrice' => array(
					'conditions' => array(
						'ProductPrice.user_role_id' => $this->userRoleId,
						),
					),
				'ProductChildren',
				),
			));
		$product = $this->Product->cleanItemPrice($product, $this->userRoleId);

		$this->request->data = $this->Product->find('first', array(
			'conditions' => array(
				'Product.id' => $id
				),
			'recursive' => 2,
			'contain' => array(
				'ProductStore.id',
				'Category.id',
				'ProductBrand',
				'ProductPrice'
				)
			));
		// remodifying data to bring support for controls
		$this->request->data['ProductStore']['id'] = array('0' => $this->request->data['ProductStore']['id']);
		$this->request->data['Category'] = Set::extract('/Category/id', $this->request->data);
		$catOptions = array();

		$catOptions = $this->Product->Category->CategoryOption->find('threaded', array(
			'conditions' => array(
				'CategoryOption.category_id' => $this->request->data['Category'],
				),
			'fields' => array(
                'CategoryOption.id',
                'CategoryOption.parent_id',
				'CategoryOption.name',
				'CategoryOption.type',
				),
			'order' => 'CategoryOption.type',
			));
//debug($catOptions);die();
		$this->set('options', $catOptions);

		$attributeData = $this->Product->find('all', array(
			'conditions' => array(
				'Product.parent_id' => $id
				),
			'fields' => array(
				'Product.id'
				),
			'recursive' => 1,
			//'group' => 'CategoryOption.parent_id'
			));

		//Set product view vars
		$this->set(compact('attributeData', 'product'));

		//check if the item is already inCart

		$this->set('itemInCart', $this->Product->TransactionItem->find('count', array(
			'conditions' => array(
				'TransactionItem.customer_id' => $this->Auth->user('id'),
				'TransactionItem.status' => 'incart',
				'TransactionItem.foreign_key' => $id,)
			)));
	}


/**
 * Add method
 *
 * Users can add products belonging to stores and brands.
 */
	public function add($productBrandId = null) {

		if (!empty($this->request->data)) {
			// Why wuould product store id ever be an array (there should be a comment about this here)
			if(isset($this->request->data['ProductStore']) && is_array($this->request->data['ProductStore']['id'])) {
				 $this->request->data['ProductStore']['id'] = $this->request->data['ProductStore']['id'][0];
			}

			if ($this->Product->add($this->request->data, $this->Auth->user('id'))) {
				$this->Session->setFlash(__('Product saved.', true));
				$this->redirect(array('action' => 'edit', $this->Product->id));
			} else {
				$this->Session->setFlash(__('Product could not be saved.', true));
			}
		}

		// get webpages records
		App::import('Model', 'Webpages.Webpage');
        $this->Webpage = new Webpage();
        $foreignKeys = $this->Webpage->find('list', array('conditions' => array('Webpage.type' => 'content')));


		$productParentIds = $this->Product->generateTreeList();
		$productBrands = $this->Product->ProductBrand->find('list');
		$productStores = $this->Product->ProductStore->find('list');
		$categories = $this->Product->Category->generateTreeList();

		$this->set('paymentOptions', $this->Product->paymentOptions());

		$categoryElement = array('plugin' => 'categories', 'parent' => 'ProductStore', 'parents' => $productStores);
		if(isset($this->request->params['named']['productStore'])) :
			$categoryElement['parentId'] = $this->request->params['named']['productStore'];
		endif;
		$userRoles = $this->Product->ProductPrice->UserRole->find('list');
		$this->set(compact('productBrandId', 'productBrands', 'productStores', 'categories', 'categoryElement', 'userRoles', 'productParentIds', 'foreignKeys'));

		$categories = array('plugin' => 'categories', 'parent' => 'ProductStore', 'parents' => $productStores);
		if(isset($this->request->params['named']['productStore'])) :
			$categories['parentId'] = $this->request->params['named']['productStore'];
		endif;

		$this->set('page_title_for_layout', __('Create Product'));
		$this->set('title_for_layout', __('Add Item Form'));
	}



	public function add_virtual($productBrandId = null) {

          App::import('Model', 'Webpages.Webpage');
          $this->Webpage = new Webpage();

          if (!empty($this->request->data)) {


            /**
             *from Webpages::add()
             */
            try {
              $this->request->data['Webpages']['id'] = $uuid;

              $this->Webpage->add($this->request->data);
              $this->Session->setFlash(__('Webpage Saved successfully', true));
              #$this->redirect(array('action' => 'index'));

              // set the foreign_key of the virtual Product to the ID of the Webpage that we just created.
              $this->request->data['Product']['foreign_key'] = $this->Webpage->getLastInsertID();

              // Why would product store id ever be an array (there should be a comment about this here)
              if(isset($this->request->data['ProductStore']) && is_array($this->request->data['ProductStore']['id'])) {
                  $this->request->data['ProductStore']['id'] = $this->request->data['ProductStore']['id'][0];
              }

              // Handle payment type (I think this should be in the model)
              if(!empty($this->request->data['Product']['payment_type'])) {
                  $this->request->data['Product']['payment_type'] = implode(',', $this->request->data['Product']['payment_type']);
              }

              if ($this->Product->add($this->request->data, $this->Auth->user('id'))) {
                  $this->Session->setFlash(__('Product saved.', true));
                  #$this->redirect(array('action' => 'edit', $this->Product->id));
                  $this->redirect(array('action' => 'index'));#
              } else {
                  $this->Session->setFlash(__('Product could not be saved.', true));
              }



            } catch (Exception $e) {
              $this->Session->setFlash($e->getMessage());
            }

		}

		// get webpages records
        $foreignKeys = $this->Webpage->find('list', array('conditions' => array('Webpage.type' => 'content')));


		$productParentIds = $this->Product->generateTreeList();
		$productBrands = $this->Product->ProductBrand->find('list');
		$productStores = $this->Product->ProductStore->find('list');
		$categories = $this->Product->Category->generateTreeList();

		$this->set('paymentOptions', $this->Product->paymentOptions());

		$categoryElement = array('plugin' => 'categories', 'parent' => 'ProductStore', 'parents' => $productStores);
		if(isset($this->request->params['named']['productStore'])) {
			$categoryElement['parentId'] = $this->request->params['named']['productStore'];
        }
		$userRoles = $this->Product->ProductPrice->UserRole->find('list');
		$this->set(compact('productBrandId', 'productBrands', 'productStores', 'categories', 'categoryElement', 'userRoles', 'productParentIds', 'foreignKeys'));
		$categories = array('plugin' => 'categories', 'parent' => 'ProductStore', 'parents' => $productStores);
		if(isset($this->request->params['named']['productStore'])) :
			$categories['parentId'] = $this->request->params['named']['productStore'];
		endif;

		$this->set('page_title_for_layout', __('Products'));
		$this->set('title_for_layout', __('Add Product Form'));


        /**
         *from Webpages::add()
         */
        // required to have per page permissions
		$this->request->data['Alias']['name'] = !empty($this->request->params['named']['alias']) ? $this->request->params['named']['alias'] : null;
		$this->UserRole = ClassRegistry::init('Users.UserRole');
		$userRoles = $this->UserRole->find('list');
		$types = $this->Webpage->types();
    	$this->set(compact('userRoles', 'types'));
	}

/**
 * Edit method
 *
 * @access public
 * @param string
 * @return void
 */
	public function edit($id = null) {
		$this->Product->id = $id;
		if (!$this->Product->exists()) {
			throw new NotFoundException(__('Invalid product'));
		}

		if ($this->request->is('post') || $this->request->is('put')) {
			try {
				$this->Product->add($this->request->data);
				$this->Session->setFlash(__('Item saved'));
				$this->redirect(array('action' => 'edit', $this->Product->id), 'success');
			} catch (Exception $e) {
				$this->Session->setFlash($e->getMessage());
			}
		}

		$product = $this->Product->find('first', array(
			'conditions' => array(
				'Product.id' => $id
				),
			'contain' => array(
				'ProductStore',
				'Category',
				'ProductBrand',
				'CategoryOption',
				'ProductPrice',
				)
			));

		$catOptions = array();

		$this->set('paymentOptions', $this->Product->paymentOptions());

		foreach($this->request->data['CategoryOption'] as $catOpt) {
			if($catOpt['type'] == 'Option Type') {
				$catOptions[$catOpt['parent_id']][] = $catOpt['id'];
			} else {
				$catOptions[$catOpt['parent_id']] = $catOpt['id'];
			}
		}
		$this->request->data['CategoryOption'] = $catOptions;

		// get webpages records
		App::import('Model', 'Webpages.Webpage');
		$this->Webpage = new Webpage();
		$foreignKeys = $this->Webpage->find('list', array('conditions' => array('Webpage.type' => 'content')));
		$this->set(compact('foreignKeys'));

		$userRoles = $this->Product->ProductPrice->UserRole->find('list');
		$productBrands = $this->Product->ProductBrand->find('list');
		$this->set(compact('userRoles', 'productBrands'));

		$this->set('productStores', $this->Product->ProductStore->find('list'));
		$this->set('productBrands', $this->Product->ProductBrand->get_brands($this->request->data['ProductStore']['id'][0]));

		$this->set('categories', $this->Product->Category->generateTreeList());

		// NOTE : Previously this said category_id => $this->request->data['Category'] --- but that is an array
		// and was causing an error.  As a temporary fix I put the [0]['id'] thing on.  But I believe
		// this will be a problem for items in multiple categories.
		if (!empty($this->request->data['Category'])) {
			$this->set('options', $this->Product->Category->CategoryOption->find('threaded', array(
				'conditions' => array(
					'CategoryOption.category_id' => $this->request->data['Category'][0]['id']
					),
				'order' => 'CategoryOption.type'
				)));
		}

		$this->request->data = $product;
	}


/**
 * update function is used for create child products with category options selected
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

			foreach($data['Category'] as $k => $val ){
				$data['Category'][$k] = $data['Category'][$k]['id'];
			}
			# setting values to parent values
			foreach($this->request->data['Product'] as $fieldName => $fieldValue) {
				if(!empty($fieldValue)) {
					$data['Product'][$fieldName] = $fieldValue ;
				}
			}
			$data['Product']['id'] = '' ;
			$data['GalleryImage'] = $this->request->data['GalleryImage'] ;
			$data['CategoryOption'] = $this->request->data['CategoryOption'];

			//create new CI
			if ($this->Product->add($data, $this->Auth->user('id'))) {
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
				'ProductChildren' => array(
					'CategoryOption',
					),
				)
			));
		$this->set(compact('product'));
		#$productParentIds = $this->Product->find('list', array('conditions' => array('Product.parent_id' => null)));
		#$this->set(compact('productParentIds'));
		$this->set(compact('parentId'));
	}


/**
 *
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
 *
 */
	public function get_stock() {
		if(!empty($this->request->data)) {
			$count_options = 0 ;
			$category_ids = array();
			foreach($this->request->data['CategoryOption'] as $k => $val) {
				if(is_array($val)) {
					$count_options += count($val);
					$category_ids = array_merge($category_ids, $val);
				} else if (!empty($val)) {
					$count_options += 1;
					$category_ids[$k] = $val;
				}
			}
			App::Import('Model', 'CategorizedOption');
			$category = new CategorizedOption();
			$productStores = $category->find('all', array('fields' => array('count(*), foreign_key'),
						'conditions'=> array('category_option_id' => $category_ids),
						'group' => "foreign_key having count(*) ={$count_options}",
												));
			$id = array();
			foreach($productStores as $k => $val) {
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


	public function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid Item', true));
			$this->redirect(array('action' => 'index'));
		}
		if ($this->Product->delete($id)) {
			$this->Session->setFlash(__('Item deleted', true));
			$this->redirect(array('action' => 'index'));
		}
	}

/*
 * Temp Function Added for trying code
 */
	public function tryme(){
		$data = $this->__content_belongs('Products' , 12);
		$this->set('dat' , $data);
		$this->set('udat' , $this->Auth->user());
	}

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
			'order' => array(
				'RAND()',
				),
			));
		if (!empty($products) && isset($this->request->params['requested'])) {
        	return $products;
        } else {
			return false;
		}
	}

/*
 * function deal_a_day() uses to find deal of day according to
 * current dateTime
 */
	public function deal_a_day() {
		$options['order'] = array('Product.ended ASC');
		$options['conditions'] = array('Product.ended >' => date('Y-m-d h:i:s'));
		$options['conditions'] = array('Product.parent_id' => null);
		$options['contain'] = array('ProductBrand', 'Gallery' => array('GalleryImage'));
		$dealItem = $this->Product->find('first', $options);
		if(empty($dealItem)) {
			$this->Session->setFlash(__('No Item Is Live', true));
		} else {
			return $dealItem;
		}
	}

/**
 *
 */
	public function buy(){
		$ret = $this->Product->TransactionItem->addToCart($this->request->data, $this->Auth->user("id"));
		if ($ret['state']) {
			$this->redirect(array('plugin'=>'transactions','controller'=>'transactions' , 'action'=>'checkout'));
		}
	}


/**
 * get attribute values according to selected options
 *
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
		$fkey_list = $CO->find('list', array('fields' => array('foreign_key'),
							'conditions'=> $conditions,
							//'conditions'=> array(
							//	'OR' => array('category_option_id' => $category_options),
							//					'CategorizedOption.foreign_key' => $children),
						));

		$co_ids = array();


		if (count($fkey_list) > 0) {
		// only do when there is a children of CI
		// categirt option ids to be shown activated
		 $co_ids = $CO->find('list', array('fields' => array('category_option_id'),
		 					'conditions'=> array('foreign_key' => $fkey_list)
		 	 ));
		}

		//unique categorized options
		$data['CategorizedOption'] = array_unique($co_ids);

		if ($category_options) {

			// data of CI with options as selected radio buttons
			$fk_list = $CO->find('list', array('fields' => array('foreign_key'),
								'conditions'=> array('category_option_id' => $category_options,
													'CategorizedOption.foreign_key' => $children),
								'group' => "foreign_key having count(*) =".count($category_options),
							));
			if (count($fk_list) == 1) {
				foreach($fk_list as $fk) {
					$data['Product']['id'] = $fk;
					if(is_array($ci[$fk])) {
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