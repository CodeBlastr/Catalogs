<?php
App::uses('CatalogsAppController', 'Catalogs.Controller');
/**
 * Catalog Items Controller
 *
 * Handles the logic for catalog items.
 *
 * PHP versions 5
 *
 * Zuha(tm) : Business Management Applications (http://zuha.com)
 * Copyright 2009-2010, Zuha Foundation Inc. (http://zuha.org)
 *
 * Licensed under GPL v3 License
 * Must retain the above copyright notice and release modifications publicly.
 *
 * @copyright     Copyright 2009-2010, Zuha Foundation Inc. (http://zuha.com)
 * @link          http://zuha.com Zuha� Project
 * @package       zuha
 * @subpackage    zuha.app.plugins.catalogs
 * @since         Zuha(tm) v 0.0.1
 * @license       GPL v3 License (http://www.gnu.org/licenses/gpl.html) and Future Versions
 */
class CatalogItemsController extends CatalogsAppController {

	public $name = 'CatalogItems';
	public $allowedActions = array('get_attribute_values');
	public $uses = 'Catalogs.CatalogItem';

/**
 * Index method.
 *
 * @param void
 * @return void
 */
	public function index() {
		# setup paginate
		$this->paginate['contain']['CatalogItemPrice']['conditions']['CatalogItemPrice.user_role_id'] = $this->userRoleId;
		$this->paginate['conditions']['OR'] = array(
			array('CatalogItem.end_date >' => date('Y-m-d h:i:s')),
			array('CatalogItem.end_date' => null),
			array('CatalogItem.end_date' => '0000-00-00 00:00:00')
		);
		$this->_namedParameterJoins();
		$this->paginate['conditions']['CatalogItem.parent_id'] = null;
		$catalogItems = $this->paginate();
		# removes items and changes prices based on user role
		$catalogItems = $this->CatalogItem->cleanItemsPrices($catalogItems, $this->userRoleId);
		$this->set(compact('catalogItems'));
	}


	private function _namedParameterJoins() {
		# category id named
		if (!empty($this->request->params['named']['category'])) {
			$categoryId = $this->request->params['named']['category'];
			$this->paginate['joins'] = array(array(
				'table' => 'categorizeds',
				'alias' => 'Categorized',
				'type' => 'INNER',
				'conditions' => array(
					"Categorized.foreign_key = CatalogItem.id",
					"Categorized.model = 'CatalogItem'",
					"Categorized.category_id = '{$categoryId}'",
				),
			));
			$contain = $this->paginate['contain'][] = 'Category';
			return $this->paginate;
		} else {
			return null;
		}
	}


	public function view($id = null) {
		$this->CatalogItem->id = $id;
		if (!$this->CatalogItem->exists()) {
			throw new NotFoundException(__('Invalid catalog item'));
		}
		
		$catalogItem = $this->CatalogItem->find('first' , array(
			'conditions' => array(
				'CatalogItem.id' => $id
				),
			'contain' => array(
				'CatalogItemBrand' => array(
					'fields' => array('name' , 'id')
					),
				'Catalog' => array(
					'fields'=>array('name' , 'id')
					),
				'CatalogItemPrice' => array(
					'conditions' => array(
						'CatalogItemPrice.user_role_id' => $this->userRoleId,
						),
					),
				'CatalogItemChildren',
				),
			));
		$catalogItem = $this->CatalogItem->cleanItemPrice($catalogItem, $this->userRoleId);

		$this->request->data = $this->CatalogItem->find('first',
				array('conditions'=>array('CatalogItem.id'=>$id), 'recursive'=>2,
					'contain'=>array('Catalog.id', 'Category.id', 'CatalogItemBrand',
							'CatalogItemPrice')));
				// remodifying data to bring support for controls
		$this->request->data['Catalog']['id'] = array('0' => $this->request->data['Catalog']['id']);
		$this->request->data['Category'] = Set::extract('/Category/id', $this->request->data);
		$catOptions = array();


		$catOptions = $this->CatalogItem->Category->CategoryOption->find('threaded', array(
			'conditions' => array(
				'CategoryOption.category_id' => $this->request->data['Category'],
				),
			'order'=>'CategoryOption.type',
			));

		$this->set('options', $catOptions);

		$attributeData = $this->CatalogItem->find('all', array(
					'fields' => array('CatalogItem.id'),
					'conditions'=>array('CatalogItem.parent_id'=>$id),
					'recursive'=>1,
					'contain'=>array('CategoryOption'),
		//			'group' => 'CategoryOption.parent_id'
		));

		//Set catalog item view vars
		$this->set(compact('attributeData', 'catalogItem'));

		//check if the item is already inCart

		$this->set('itemInCart', $this->CatalogItem->OrderItem->find('count', array(
			'conditions' => array(
				'OrderItem.customer_id' => $this->Auth->user('id'),
				'OrderItem.status' => 'incart',
				'OrderItem.catalog_item_id' => $id,)
			)));
	}


/**
 * add method
 * 
 * Users can add catalog items belonging to catalogs and brands.
 */
	public function add($catalogItemBrandId = null) {

		if (!empty($this->request->data)) {
			# Why wuould catalog id ever be an array (there should be a comment about this here)
			if(isset($this->request->data['Catalog']) && is_array($this->request->data['Catalog']['id'])) {
				 $this->request->data['Catalog']['id'] = $this->request->data['Catalog']['id'][0];
			}
			
			# Handle ARB (automatic recurring billing) Settings (THIS SHOULD BE IN THE MODEL!!!!)
			$this->request->data['CatalogItem']['arb_settings'] = !empty($this->request->data['CatalogItem']['arb_settings'])
				? serialize(parse_ini_string($this->request->data['CatalogItem']['arb_settings'])) : '' ;
									
			# Handle payment type (I think this should be in the model)
			if(!empty($this->request->data['CatalogItem']['payment_type'])) {
				$this->request->data['CatalogItem']['payment_type'] = implode(',', $this->request->data['CatalogItem']['payment_type']);
			}

			if ($this->CatalogItem->add($this->request->data, $this->Auth->user('id'))) {
				$this->Session->setFlash(__('CatalogItem saved.', true));
				$this->redirect(array('action' => 'edit', $this->CatalogItem->id));
			} else {
				$this->Session->setFlash(__('Catalog Item could not be saved.', true));
			}
		}

		// get webpages records
		App::import('Model', 'Webpages.Webpage');
        $this->Webpage = new Webpage();
        $foreignKeys = $this->Webpage->find('list', array('conditions' => array('Webpage.type' => 'page_content')));
        
		
		$catalogItemParentIds = $this->CatalogItem->generateTreeList();
		$catalogItemBrands = $this->CatalogItem->CatalogItemBrand->find('list');
		$catalogs = $this->CatalogItem->Catalog->find('list');
		$categories = $this->CatalogItem->Category->generateTreeList();
		if(defined('__ORDERS_ENABLE_SINGLE_PAYMENT_TYPE')) :
			$paymentOptions = defined('__ORDERS_ENABLE_PAYMENT_OPTIONS') ? unserialize(__ORDERS_ENABLE_PAYMENT_OPTIONS) : null;
			$this->set(compact('paymentOptions'));
		endif;

		$categoryElement = array('plugin' => 'categories', 'parent' => 'Catalog', 'parents' => $catalogs);
		if(isset($this->request->params['named']['catalog'])) :
			$categoryElement['parentId'] = $this->request->params['named']['catalog'];
		endif;
		$userRoles = $this->CatalogItem->CatalogItemPrice->UserRole->find('list');
		$this->set(compact('catalogItemBrandId', 'catalogItemBrands', 'catalogs', 'categories', 'categoryElement', 'userRoles', 'catalogItemParentIds', 'foreignKeys'));

		$categories = array('plugin' => 'categories', 'parent' => 'Catalog', 'parents' => $catalogs);
		if(isset($this->request->params['named']['catalog'])) :
			$categories['parentId'] = $this->request->params['named']['catalog'];
		endif;

		$this->set('page_title_for_layout', __('Catalog Items', true));
		$this->set('title_for_layout', __('Add Item Form', true));
	}

/**
 * edit method
 *
 */
	public function edit($id = null) {
		$this->CatalogItem->id = $id;
		if (!$this->CatalogItem->exists()) {
			throw new NotFoundException(__('Invalid catalog item'));
		}
		
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->request->data['CatalogItem']['arb_settings'] = !empty($this->request->data['CatalogItem']['arb_settings']) ? serialize(parse_ini_string($this->request->data['CatalogItem']['arb_settings'])) : '' ;
			if(!empty($this->request->data['CatalogItem']['payment_type'])) :
				$this->request->data['CatalogItem']['payment_type'] = implode(',', $this->request->data['CatalogItem']['payment_type']);
			endif;

			try {
				$this->CatalogItem->add($this->request->data);
				$this->Session->setFlash(__('Item saved', true));
				$this->redirect(array('action' => 'edit', $this->CatalogItem->id), 'success');
			} catch (Exception $e) {
				$this->Session->setFlash($e->getMessage());
			}
		}

		# _viewVars
		if (!empty($id)) :
			$this->request->data = $this->CatalogItem->find('first', array(
				'conditions' => array(
					'CatalogItem.id' => $id),
					'recursive' => 2,
					'contain' => array(
						'Catalog',
						'Category',
						'CatalogItemBrand',
						'CategoryOption',
						'CatalogItemPrice',
						'Location',
						)
					));
			if (!empty($this->request->data)) {
				// remodifying data to bring support for controls
				$this->request->data['Catalog']['id'] = array('0' => $this->request->data['Catalog']['id']);
				# removed in order to work with the new checkboxes (instead of the old expanding category widget)
				# rk 7/27/2011  # delete completely if nothing else is broken
				# $this->request->data['Category'] = Set::extract('/Category/id', $this->request->data);
				$catOptions = array();

				# if arb_settings defined for CI then it will unserialize the values
				if(!empty($this->request->data['CatalogItem']['arb_settings'])) {
					$arb_settings_array = unserialize($this->request->data['CatalogItem']['arb_settings']);
					$arb_settings_string = '';
					foreach ($arb_settings_array as $key => $value ){
						$arb_settings_string .= "$key = $value\n";
 					}
					$this->request->data['CatalogItem']['arb_settings'] = $arb_settings_string ;
				}

				if(defined('__ORDERS_ENABLE_SINGLE_PAYMENT_TYPE')) :
					$paymentOptions = defined('__ORDERS_ENABLE_PAYMENT_OPTIONS') ? unserialize(__ORDERS_ENABLE_PAYMENT_OPTIONS) : null;
					$this->set(compact('paymentOptions'));
				endif;

				foreach($this->request->data['CategoryOption'] as $catOpt) {
					if($catOpt['type'] == 'Option Type')
						$catOptions[$catOpt['parent_id']][] = $catOpt['id'];
					else
						$catOptions[$catOpt['parent_id']] = $catOpt['id'];
				}
				$this->request->data['CategoryOption'] = $catOptions;

				// get webpages records
				App::import('Model', 'Webpages.Webpage');
		        $this->Webpage = new Webpage();
		        $foreignKeys = $this->Webpage->find('list', array('conditions' => array('Webpage.type' => 'page_content')));
				$this->set(compact('foreignKeys'));

				$userRoles = $this->CatalogItem->CatalogItemPrice->UserRole->find('list');
				$priceTypes = ($this->CatalogItem->CatalogItemPrice->PriceType->find('list',
						array('conditions' => array('PriceType.type' => 'PRICE_TYPE'),)));
				$catalogItemBrands = $this->CatalogItem->CatalogItemBrand->find('list');
				$this->set(compact('userRoles', 'priceTypes', 'catalogItemBrands'));

				$this->set('catalogs', $this->CatalogItem->Catalog->find('list'));
				$this->set('catalogBrands',
						$this->CatalogItem->CatalogItemBrand->get_brands($this->request->data['Catalog']['id'][0]));
				$this->set('categories', $this->CatalogItem->Category->generateTreeList());
				#NOTE : Previously this said category_id => $this->request->data['Category'] --- but that is an array
				# and was causing an error.  As a temporary fix I put the [0]['id'] thing on.  But I believe
				# this will be a problem for items in multiple categories.
				if (!empty($this->request->data['Category'])) {
					$this->set('options', $this->CatalogItem->Category->CategoryOption->find('threaded', array(
						'conditions' => array('CategoryOption.category_id' => $this->request->data['Category'][0]['id']),
						'order' => 'CategoryOption.type'
						)));
				}
			} else {
				$this->Session->setFlash(__('Invalid Item', true));
				$this->redirect(array('action' => 'index'));
			}
		else :
			$this->Session->setFlash(__('Invalid Item', true));
			$this->redirect(array('action' => 'index'));
		endif;
	}


/**
 * update function is used for create child catalog items with category options selected
 */
	public function update($parentId = null) {
		if (!empty($this->request->data)) {
			$data = $this->CatalogItem->find('first', array(
				'conditions' => array(
					'CatalogItem.id' => $this->request->data['CatalogItem']['parent_id']
					),
				'recursive' => 2,
				'contain' => array(
					'Catalog.id',
					'Category.id',
					'CatalogItemBrand',
					'CategoryOption',
					'CatalogItemPrice'
					)
				));

			foreach($data['Category'] as $k => $val ){
				$data['Category'][$k] = $data['Category'][$k]['id'];
			}
			# setting values to parent values
			foreach($this->request->data['CatalogItem'] as $fieldName => $fieldValue) {
				if(!empty($fieldValue)) {
					$data['CatalogItem'][$fieldName] = $fieldValue ;
				}
			}
			$data['CatalogItem']['id'] = '' ;
			$data['GalleryImage'] = $this->request->data['GalleryImage'] ;
			$data['CategoryOption'] = $this->request->data['CategoryOption'];

			//create new CI
			if ($this->CatalogItem->add($data, $this->Auth->user('id'))) {
				$this->redirect(array('action' => 'edit', $this->request->data['CatalogItem']['parent_id']));
			} else {
				$this->Session->setFlash(__('New attribute save failed.', true));
				$this->redirect(array('action' => 'update', $this->request->data['CatalogItem']['parent_id']));
			}
		}

		$catalogItem = $this->CatalogItem->find('first', array(
			'conditions' => array(
				'CatalogItem.id' => $parentId,
				),
			'contain' => array(
				'CatalogItemChildren' => array(
					'CategoryOption',
					),
				)
			));
		$this->set(compact('catalogItem'));
		#$catalogItemParentIds = $this->CatalogItem->find('list', array('conditions' => array('CatalogItem.parent_id' => null)));
		#$this->set(compact('catalogItemParentIds'));
		$this->set(compact('parentId'));
	}


/**
 *
 */
	public function get_catalog_item($id = null) {
		$this->layout = false;
		if (!empty($id)) {
			$this->request->data = $this->CatalogItem->find('first', array(
				'conditions' => array(
					'CatalogItem.id' => $id
					), 
				'recursive' => 2,
				'contain' => array(
					'Catalog.id',
					'Category.id',
					'CatalogItemBrand',
					'CategoryOption',
					'CatalogItemPrice'
					)
				));
			// remodifying data to bring support for controls
			$this->request->data['Catalog']['id'] = array('0' => $this->request->data['Catalog']['id']);
			$this->request->data['Category'] = Set::extract('/Category/id', $this->request->data);
			$catOptions = array();
				$catOptions = $this->CatalogItem->Category->CategoryOption->find('threaded', array(
				'conditions'=>array('CategoryOption.category_id' => $this->request->data['Category']),
				'order' => 'CategoryOption.type'
			));
			$this->set('options', $catOptions);
		}
	}


/**
 * get_stock function is used to get the stock of catalog items
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
			$catalogs = $category->find('all', array('fields' => array('count(*), foreign_key'),
						'conditions'=> array('category_option_id' => $category_ids),
						'group' => "foreign_key having count(*) ={$count_options}",
												));
			$id = array();
			foreach($catalogs as $k => $val) {
					$id[$k] = $val['CategorizedOption']['foreign_key'];
			}
			$catalogitems = $this->CatalogItem->find('all', array('conditions' => array(
					'CatalogItem.id' => $id
			)));
			$this->set(compact('catalogitems'));
		}

		$this->set('options', $this->CatalogItem->Category->CategoryOption->find('threaded', array(
				'conditions'=>array('CategoryOption.category_id' => $this->request->params['named']['category_id']),
				'order'=>'CategoryOption.type'
		)));

	}


	public function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid Item', true));
			$this->redirect(array('action' => 'index'));
		}
		if ($this->CatalogItem->delete($id)) {
			$this->Session->setFlash(__('Item deleted', true));
			$this->redirect(array('action' => 'index'));
		}
	}

/*
 * Temp Function Added for trying code
 */
	public function tryme(){
		$data = $this->__content_belongs('CatalogItems' , 12);
		$this->set('dat' , $data);
		$this->set('udat' , $this->Auth->user());
	}

	public function get_items($catalogBrandId = null) {
		if ($catalogBrandId) {
			$this->set('items', $this->CatalogItem->find('list', array(
					'conditions' => array('CatalogItem.catalog_brand_id'=>$catalogBrandId))));
		}
	}


/**
 * Set the variables for n number of random products for display in the random element.
 *
 * @param {int}		The number of items you want to pull.
 */
	public function random_product($count = 3) {
		$catalogItems = $this->CatalogItem->find('all', array(
			'limit' => $count,
			'order' => array(
				'RAND()',
				),
			));
		if (!empty($catalogItems) && isset($this->request->params['requested'])) {
        	return $catalogItems;
        } else {
			return false;
		}
	}

/*
 * function deal_a_day() uses to find deal of day according to
 * current dateTime
 */
	public function deal_a_day() {
		$options['order'] = array('CatalogItem.end_date ASC');
		$options['conditions'] = array('CatalogItem.end_date >' => date('Y-m-d h:i:s'));
		$options['conditions'] = array('CatalogItem.parent_id' => null);
		$options['contain'] = array('CatalogItemBrand', 'Gallery' => array('GalleryImage'));
		$dealItem = $this->CatalogItem->find('first', $options);
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
		$ret = $this->CatalogItem->OrderItem->addToCart($this->request->data, $this->Auth->user("id"));
		if ($ret['state']) {
			$this->redirect(array('plugin'=>'orders','controller'=>'order_transactions' , 'action'=>'checkout'));
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
		//get catalog_items children bases on parent_id
		$catalogItemId = !empty($this->request->data['OrderItem']['parent_id']) ? $this->request->data['OrderItem']['parent_id'] : $this->request->data['OrderItem']['catalog_item_id'];
		$ci = $this->CatalogItem->find('list', array(
				'fields' => array('price', 'stock_item', 'id'),
				'conditions'=>array('CatalogItem.parent_id' => $catalogItemId ),
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
					$data['CatalogItem']['id'] = $fk;
					if(is_array($ci[$fk])) {
						foreach($ci[$fk] as $price => $stock){
							$data['CatalogItem']['stock'] = $stock;
							$data['CatalogItem']['price'] = $price;
						}

					}
				}
			}
		}

		echo json_encode($data);
	}
	
}