<?php
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
  
	var $name = 'CatalogItems';
	var $allowedActions = array('get_attribute_values');
	
	/**
	 * Grabs the variables from the model to send to the index view.
	 */
	function index() {	
		$params['conditions'] = isset($this->request->params['named']['stock']) ? array('CatalogItem.stock_item' => $this->request->params['named']['stock']): null;
		$params['contain']['CatalogItemPrice']['conditions']['CatalogItemPrice.user_role_id'] = $this->userRoleId;
		$params['conditions']['OR']['CatalogItem.end_date >'] = date('Y-m-d h:i:s');
		$params['conditions']['OR']['CatalogItem.end_date'] = null;
		$params['conditions']['OR']['CatalogItem.end_date'] = '0000-00-00 00:00:00';
		$params['conditions']['CatalogItem.parent_id'] = null;

		$this->paginate = $params;
		$catalogItems = $this->paginate();
		# removes items and changes prices based on user role
		$catalogItems = $this->CatalogItem->cleanItemsPrices($catalogItems, $this->userRoleId);
		$this->set(compact('catalogItems'));
	}
	

	function view($id = null) {
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
				'Gallery' => array(
					'GalleryImage'
					),
				'CatalogItemPrice' => array(
					'conditions' => array(
						'CatalogItemPrice.user_role_id' => $this->userRoleId,
						),
					),
				'CatalogItemChildren' => array(
					'Gallery',
					),
				),
			));
		$catalogItem = $this->CatalogItem->cleanItemPrice($catalogItem, $this->userRoleId);
		
		$this->request->data = $this->CatalogItem->find('first', 
				array('conditions'=>array('CatalogItem.id'=>$id), 'recursive'=>2,
					'contain'=>array('Catalog.id', 'Category.id', 'CatalogItemBrand', 
							'CategoryOption', 'CatalogItemPrice')));
				// remodifying data to bring support for controls
		$this->request->data['Catalog']['id'] = array('0' => $this->request->data['Catalog']['id']);
		$this->request->data['Category'] = Set::extract('/Category/id', $this->request->data);
		$catOptions = array();
		
		
		$catOptions = $this->CatalogItem->Category->CategoryOption->find('threaded', array(
		'conditions'=>array('CategoryOption.category_id' => $this->request->data['Category']),
		'order'=>'CategoryOption.type'
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
		$this->set('attributeData', $attributeData); 
		
		$this->set('catalogItem', $catalogItem); 
		$this->set('catalogItem', $catalogItem);
		$this->set('gallery', $catalogItem);
		$this->set('value', $this->CatalogItem->Gallery->_galleryVars($catalogItem)); 
		
	
		//set the stock option 
		if($catalogItem["CatalogItem"]["stock_item"] > 0 || $catalogItem["CatalogItem"]["stock_item"] == null){
			$this->set('no_stock' , false);
		} else {
			$this->set('no_stock' , true);	
		}

		//check if the item is already inCart
		$this->set('itemInCart', $this->CatalogItem->OrderItem->find('count', array(
			'conditions' => array(
				'OrderItem.customer_id' => $this->Auth->user('id'),
				'OrderItem.status' => 'incart',
				'OrderItem.catalog_item_id' => $id,)
			)));
	}
	
	
	/**
	 * Users can add catalog items belonging to catalogs and brands. 
	 */
	function add($catalogItemBrandId = null) {
		
		if (!empty($this->request->data)) {
			if(isset($this->request->data['Catalog']) && is_array($this->request->data['Catalog']['id']))
				 $this->request->data['Catalog']['id'] = $this->request->data['Catalog']['id'][0];
			$this->request->data['CatalogItem']['catalog_id'] = $this->request->data['Catalog']['id'];
			
			$this->request->data['CatalogItem']['arb_settings'] = !empty($this->request->data['CatalogItem']['arb_settings']) 
									? serialize(parse_ini_string($this->request->data['CatalogItem']['arb_settings'])) : '' ; 
			
			if ($this->CatalogItem->add( $this->request->data, $this->Auth->user('id'))) {
				$this->Session->setFlash(__('CatalogItem saved.', true));
				$this->redirect(array('action' => 'edit', $this->CatalogItem->id, 'admin' => 0));
			} else {
				$this->Session->setFlash(__('Catalog Item could not be saved.', true));
			}
		}
		
		$catalogItemParentIds = $this->CatalogItem->generateTreeList();
		$catalogItemBrands = $this->CatalogItem->CatalogItemBrand->find('list');
		$catalogs = $this->CatalogItem->Catalog->find('list');
		$categories = $this->CatalogItem->Category->generateTreeList();
		$categoryElement = array('plugin' => 'categories', 'parent' => 'Catalog', 'parents' => $catalogs);
		if(isset($this->request->params['named']['catalog'])) : 
			$categoryElement['parentId'] = $this->request->params['named']['catalog'];
		endif;
		$userRoles = $this->CatalogItem->CatalogItemPrice->UserRole->find('list');
		$this->set(compact('catalogItemBrandId', 'catalogItemBrands', 'catalogs', 'categories', 'categoryElement', 'userRoles', 'catalogItemParentIds'));
		
		$categories = array('plugin' => 'categories', 'parent' => 'Catalog', 'parents' => $catalogs);
		if(isset($this->request->params['named']['catalog'])) : 
			$categories['parentId'] = $this->request->params['named']['catalog'];
		endif;
		
		$this->set('page_title_for_layout', __('Catalog Items', true));
		$this->set('title_for_layout', __('Add Item Form', true)); 
	}
	
	/**
	 * Edit Existing Catalog Items
	 *
	 * @todo	Having a lot of trouble with multiple catalogs and how to edit catalog items. Plenty of usability work to do there.  (note: the importance of having multiple stores is for easily expanding a business in the future, with multiple price testing, and look and feel locations).
	 */
	function edit($id = null) {
		
		if (!empty($this->request->data)) :
			$this->request->data['CatalogItem']['arb_settings'] = !empty($this->request->data['CatalogItem']['arb_settings']) 
									? serialize(parse_ini_string($this->request->data['CatalogItem']['arb_settings'])) : '' ; 		
			if ($this->CatalogItem->add($this->request->data)) : 
				$this->Session->setFlash(__('Item saved', true));
				$this->redirect(array('action' => 'edit', $this->CatalogItem->id), 'success');
			else : 
				$this->Session->setFlash(__('Save error', true));
				$this->redirect(array('action' => 'edit', $this->CatalogItem->id), 'failure');
			endif;
		endif;
		
		// the following block is to support edit
		if (!empty($id)) :
			$this->request->data = $this->CatalogItem->find('first', array(
				'conditions' => array(
					'CatalogItem.id' => $id),
					'recursive'=>2,
					'contain' => array(
						'Catalog',
						'Category',
						'CatalogItemBrand',
						'CategoryOption',
						'CatalogItemPrice',
						'Location',
						)
					));
			if (!empty($this->request->data)) :
				// remodifying data to bring support for controls
				$this->request->data['Catalog']['id'] = array('0' => $this->request->data['Catalog']['id']);
				# removed in order to work with the new checkboxes (instead of the old expanding category widget)
				# rk 7/27/2011  # delete completely if nothing else is broken
				# $this->request->data['Category'] = Set::extract('/Category/id', $this->request->data);
				$catOptions = array();
				
				//if arb_settings defined for CI then it will unserialize the values
				if(isset($this->request->data['CatalogItem']['arb_settings'])) {
					$arb_settings_array = unserialize($this->request->data['CatalogItem']['arb_settings']);
					$arb_settings_string = '';
					foreach ($arb_settings_array as $key => $value ){
						$arb_settings_string .= "$key = $value\n";
 					}
					$this->request->data['CatalogItem']['arb_settings'] = $arb_settings_string ; 
				}
				
				foreach($this->request->data['CategoryOption'] as $catOpt) {
					if($catOpt['type'] == 'Option Type')
						$catOptions[$catOpt['parent_id']][] = $catOpt['id'];
					else 
						$catOptions[$catOpt['parent_id']] = $catOpt['id'];
				}
				$this->request->data['CategoryOption'] = $catOptions; 
	
				$userRoles = $this->CatalogItem->CatalogItemPrice->UserRole->find('list');
				$priceTypes = ($this->CatalogItem->CatalogItemPrice->PriceType->find('list', 
						array('conditions' => array('PriceType.type' => 'PRICETYPE'),)));
				$catalogItemBrands = $this->CatalogItem->CatalogItemBrand->find('list');
				$this->set(compact('userRoles', 'priceTypes', 'catalogItemBrands'));
			
				$this->set('catalogs', $this->CatalogItem->Catalog->find('list'));
				$this->set('catalogBrands', 
						$this->CatalogItem->CatalogItemBrand->get_brands($this->request->data['Catalog']['id'][0]));
				$this->set('categories', $this->CatalogItem->Category->generateTreeList());
				#NOTE : Previously this said category_id => $this->request->data['Category'] --- but that is an array
				# and was causing an error.  As a temporary fix I put the [0]['id'] thing on.  But I believe
				# this will be a problem for items in multiple categories.
				$this->set('options', $this->CatalogItem->Category->CategoryOption->find('threaded', array(
					'conditions'=>array('CategoryOption.category_id' => $this->request->data['Category'][0]['id']),
					'order'=>'CategoryOption.type'
				)));
			else : 
				$this->Session->setFlash(__('Invalid Item', true));
				$this->redirect(array('action' => 'index'));
			endif;
		else : 
			$this->Session->setFlash(__('Invalid Item', true));
			$this->redirect(array('action' => 'index'));
		endif;
	}
	
	
	/*
	 * update function is used for create child catalog items with category options selected  
	 */
	function update($parentId = null) {
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
	
	/*
	 * 
	 */
	function get_catalog_item($id = null) {
		$this->layout = false;
		if ($id) {
				$this->request->data = $this->CatalogItem->find('first', 
						array('conditions'=>array('CatalogItem.id'=>$id), 'recursive'=>2,
							'contain'=>array('Catalog.id', 'Category.id', 'CatalogItemBrand', 
									'CategoryOption', 'CatalogItemPrice')));
				// remodifying data to bring support for controls
				$this->request->data['Catalog']['id'] = array('0' => $this->request->data['Catalog']['id']);
				$this->request->data['Category'] = Set::extract('/Category/id', $this->request->data);
				$catOptions = array();
				
				$catOptions = $this->CatalogItem->Category->CategoryOption->find('threaded', array(
				'conditions'=>array('CategoryOption.category_id' => $this->request->data['Category']),
				'order'=>'CategoryOption.type'
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
	
	function get_stock() {
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

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid Item', true));
			$this->redirect(array('action' => 'index'));
		}
		if ($this->CatalogItem->delete($id)) {
			$this->Session->setFlash(__('Item deleted', true));
			$this->redirect(array('action' => 'index'));
		}
	}
	
/**
 * Admin page view function. Could code a static_page but have to iverride the pagesController.
 * @TODO this is a place holder because of timing issues. 
 * 
 */	
	function admin_adminpage(){
	}
	
	function admin_catalogs($catalogId = null) {
		$this->set('catalogId', $catalogId);
		$this->CatalogItem->recursive = 1;
		$this->set('catalogItems', $this->paginate('CatalogItem', 
			array('CatalogItem.catalog_id'=>$catalogId)));
	}
	
	/*
	 * Temp Function Added for trying code
	 */
	
	function tryme(){
		$data = $this->__content_belongs('CatalogItems' , 12);
		$this->set('dat' , $data);
		$this->set('udat' , $this->Auth->user());
	}
	
	function get_items($catalogBrandId = null) {
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
	function random_product($count = 3) {
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
	function deal_a_day() {
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
	
	function buy(){
		$ret = $this->CatalogItem->OrderItem->addToCart($this->request->data, $this->Auth->user("id"));
		if ($ret['state']) {
			$this->redirect(array('plugin'=>'orders','controller'=>'order_transactions' , 'action'=>'checkout'));
		}
	}
	
	
	/*
	 * get attribute values according to selected options
	 * 
	 */
	function get_attribute_values() {
		
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
		
		App::Import('Model', 'CategorizedOption');
		$CO = new CategorizedOption();
		
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
?>