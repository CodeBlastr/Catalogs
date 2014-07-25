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
 * @license       GPL v3 License (http://www.gnu.org/licenses/gpl.html) and
 * Future Versions
 */
class AppProductsController extends ProductsAppController {

/**
 * Name
 *
 * @var string
 */
	public $name = 'Products';

/**
 * Uses
 *
 * @var string
 */
	public $uses = 'Products.Product';

/**
 * Products dashboard.
 *
 */
	public function dashboard() {
		if (CakePlugin::loaded('Transactions')) {
			$this->redirect(array('admin' => true, 'plugin' => 'transactions', 'controller' => 'transactions', 'action' => 'dashboard'));
		}
		$this->paginate['conditions']['Product.parent_id'] = null;
		$this->paginate['order'] = array('Product.lft' => 'ASC', 'Product.price' => 'ASC', 'Product.name' => 'ASC');
		$products = $this->paginate('Product');
		$this->set('title_for_layout', __('Store') . ' | ' . __SYSTEM_SITE_NAME);
		$this->set('page_title_for_layout', __('Store') . ' | ' . __SYSTEM_SITE_NAME);
		$this->set('products', $products);
		return $products;
	}

/**
 * Index method.
 *
 * @param void
 * @return void
 */
	public function index() {
		$this->paginate['contain'][] = 'Option';
		$this->paginate['contain'][] = 'Owner';
		$this->paginate['contain'][] = 'Creator';
		$this->paginate['conditions']['Product.parent_id'] = null;
		$this->paginate['order'] = array('Product.lft' => 'ASC', 'Product.price' => 'ASC', 'Product.name' => 'ASC');
		$products = $this->paginate('Product');
		$this->set('title_for_layout', __('Store') . ' | ' . __SYSTEM_SITE_NAME);
		$this->set('page_title_for_layout', __('Store') . ' | ' . __SYSTEM_SITE_NAME);
		$this->set('products', $products);
		return $products;
	}

/**
 * My method.
 *
 * @param void
 * @return void
 */
	public function my() {
		$this->paginate['contain'][] = 'Option';
		$this->paginate['contain'][] = 'Owner';
		$this->paginate['contain'][] = 'Creator';
		$this->paginate['conditions']['Product.parent_id'] = null;
		$this->paginate['conditions']['Product.owner_id'] = $this->Session->read('Auth.User.id');
		$this->paginate['order'] = array('Product.price' => 'ASC', 'Product.name' => 'ASC');
		$products = $this->paginate('Product');
		$this->set('title_for_layout', __('My Store') . ' | ' . __SYSTEM_SITE_NAME);
		$this->set('page_title_for_layout', __('My Store') . ' | ' . __SYSTEM_SITE_NAME);
		$this->set('products', $products);
		return $products;
	}

/**
 * User method.
 *
 * @param void
 * @return void
 */
	public function user($userId = null) {
		$this->paginate['contain'][] = 'Option';
		$this->paginate['contain'][] = 'Owner';
		$this->paginate['contain'][] = 'Creator';
		$this->paginate['conditions']['Product.parent_id'] = null;
		$this->paginate['conditions']['Product.owner_id'] = $userId;
		$this->paginate['order'] = array('Product.price' => 'ASC', 'Product.name' => 'ASC');
		$products = $this->paginate('Product');
		$this->set('title_for_layout', __('My Store') . ' | ' . __SYSTEM_SITE_NAME);
		$this->set('page_title_for_layout', __('My Store') . ' | ' . __SYSTEM_SITE_NAME);
		$this->set('products', $products);
		$this->set('user', $user = $this->Product->User->find('first', array('conditions' => array('User.id' => $userId))));
		return $products;
	}

/**
 * It is imperative that we document this function
 *
 * @todo make this more isolated and modular (its calling multiple related models
 * from other plugins)
 */
	public function view($id = null, $child = false) {
		$this->Product->id = $id;
		if (!$this->Product->exists()) {
			throw new NotFoundException(__('Invalid product'));
		}
		$product = $this->Product->find('first', array(
			'conditions' => array('Product.id' => $id),
			'contain' => array(
				'ProductBrand' => array('fields' => array(
						'name',
						'id'
					)),
				'ProductPrice' => array('conditions' => array('ProductPrice.user_role_id' => $this->userRoleId, ), ),
				'Children',
				'Owner',
			),
		));
		!empty($product['Parent']['id']) && empty($child) ? $this->redirect(array($product['Parent']['id'])) : null;
		// redirect to parent
		$productsOptions = $this->Product->Option->ProductsOption->find('all', array(
			'conditions' => array('ProductsOption.product_id' => Set::extract('/id', $product['Children'])),
			'contain' => 'Option',
			'order' => array(
				'Option.parent_id',
				'Option.name'
			)
		));
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

/**
 * Add method
 *
 * @param string $type [empty = default, arb, virtual, virtual_arb, membership]
 * @param string $parentId
 */
	public function add($type = 'default', $parentId = null) {
		$this->redirect('admin');
		$function = '_add' . ucfirst($type);
		$this->set('productBrands', $this->Product->ProductBrand->find('list'));
		if (CakePlugin::loaded('Categories')) {
			$this->set('categories', $this->Product->Category->generateTreeList(array('Category.model' => 'Product')));
		}
		//$this->set('paymentOptions', $this->Product->paymentOptions());
		return $this->$function($parentId);
	}

/**
 * Post method
 * Same as add, used so one can be public (no admin redirect)
 * 
 * @param string $type [empty = default, arb, virtual, virtual_arb, membership]
 * @param string $parentId
 */
	public function post($type = 'default', $parentId = null) {
		$function = '_post' . ucfirst($type);
		$this->set('productBrands', $this->Product->ProductBrand->find('list'));
		if (CakePlugin::loaded('Categories')) {
			$this->set('categories', $this->Product->Category->generateTreeList(array('Category.model' => 'Product')));
		}
		//$this->set('paymentOptions', $this->Product->paymentOptions());
		return $this->$function($parentId);
	}

/**
 * Post default
 */
	protected function _postDefault($parentId = null) {
		if ($this->request->is('post')) {
			if ($this->Product->saveAll($this->request->data)) {
				$this->Session->setFlash(__('Product saved.'), 'flash_success');
				$this->redirect(array(
					'action' => 'edit',
					$this->Product->id
				));
			}
		}
		$this->set('page_title_for_layout', __('Create a Product'));
		$this->set('title_for_layout', __('Post Product Form'));
		$this->view = 'post_default';
		return !empty($parentId) ? $this->_addDefaultChild($parentId) : true;
	}

/**
 * Add default
 */
	protected function _addDefault($parentId = null) {
		if ($this->request->data) {
			if ($this->Product->saveAll($this->request->data)) {
				$this->Session->setFlash(__('Product saved.'), 'flash_success');
				$this->redirect(array(
					'action' => 'edit',
					$this->Product->id
				));
			}
		}
		$this->set('page_title_for_layout', __('Create a Product'));
		$this->set('title_for_layout', __('Add Product Form'));
		$this->view = 'add_default';
		return !empty($parentId) ? $this->_addDefaultChild($parentId) : true;
	}

	protected function _addArb($parentId = null) {
		if (!empty($this->request->data)) {
			if ($this->Product->saveAll($this->request->data)) {
				$this->Session->setFlash(__('Product saved.'), 'flash_success');
				$this->redirect(array(
					'action' => 'edit',
					$this->Product->id
				));
			}
		}
		$this->set('page_title_for_layout', __('Create an ARB Product'));
		$this->set('title_for_layout', __('Add Product Form'));
		$this->view = 'add_arb';
		return !empty($parentId) ? $this->_addDefaultChild($parentId) : true;
	}

	protected function _addDefaultChild($parentId) {
		$this->request->data = $this->Product->find('first', array(
			'conditions' => array('Product.id' => $parentId),
			'contain' => array('Option' => 'Children')
		));
		unset($this->request->data['Product']['sku']);
		$this->set('page_title_for_layout', __('Create a %s Variant', $this->request->data['Product']['name']));
		$this->set('title_for_layout', __('Add Product Variant Form'));
		//$this->layout = false;
		// required for modal to work (but causes the standard view page not to)
		$this->view = 'add_default_child';
	}

	protected function _addMembership($parentId = null) {
		if (!empty($this->request->data)) {
			if ($this->Product->saveAll($this->request->data)) {
				$this->Session->setFlash(__('Membership saved.'), 'flash_success');
				$this->redirect(array(
					'action' => 'edit',
					$this->Product->id
				));
			}
		}
		$this->set('page_title_for_layout', __('Create a Membership Product'));
		$this->set('title_for_layout', __('Create a Membership Product'));
		$this->view = 'add_membership';
		$userRoles = array_diff($this->Product->Owner->UserRole->find('list'), array(
			'admin',
			'guests'
		));
		foreach ($userRoles as $key => $foreignKey) {
			$userRoles[$key] = Inflector::humanize(Inflector::singularize($foreignKey));
		}
		$this->set('foreignKeys', $userRoles);
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
		// order is important
		if ($this->request->is('put')) {
			if ($this->Product->saveAll($this->request->data)) {
				$this->Session->setFlash(__('Product saved.'), 'flash_success');
				if (isset($this->request->data['SaveAndContinue'])) {
					$this->redirect(array(
						'action' => 'edit',
						$this->Product->id
					));
				} else {
					$this->redirect(array(
						'action' => 'view',
						$this->Product->id
					));
				}
			}
		}
		// check to see if we have a product before even worrying about anything else
		$this->Product->id = $id;
		if (!$this->Product->exists()) {
			throw new NotFoundException(__('Invalid product'));
		}
		// order is important (categories for all products)
		if (CakePlugin::loaded('Categories')) {
			$this->set('categories', $this->Product->Category->generateTreeList(array('model' => 'Product')));
			$selectedCategories = $this->Product->Category->Categorized->find('all', array(
				'conditions' => array(
					'Categorized.model' => $this->Product->alias,
					'Categorized.foreign_key' => $this->Product->id
				),
				'contain' => array('Category')
			));
			$this->set('selectedCategories', Set::extract($selectedCategories, '/Category/id'));
		}
		// check for special products that need to be edited elsewhere
		// order is important for this
		$model = $this->Product->field('model');
		if (!empty($child)) {
			return $this->_editChild($id);
		} elseif ($model == 'UserRole') {
			return $this->_editMembership($id);
		}
		$this->request->data = $this->Product->find('first', array(
			'conditions' => array('Product.id' => $id),
			'contain' => array(
				'Option',
				'Parent',
				'Children' => array('Option' => array('order' => array(
					'Option.parent_id' => 'ASC',
					'Option.name' => 'ASC',
				)))
			)
		));
		!empty($this->request->data['Parent']['id']) ? $this->redirect(array($this->request->data['Parent']['id'])) : null;
		// redirect to parent
		$this->set('productBrands', $this->Product->ProductBrand->find('list'));
		$this->set('existingOptions', $existingOptions = Set::combine($this->request->data['Option'], '{n}.ProductsProductOption.option_id', '{n}.name'));
		$this->set('options', array_diff($this->Product->Option->find('list', array('conditions' => array('OR' => array(
					array('Option.parent_id' => ''),
					array('Option.parent_id' => null)
				)))), $existingOptions));
		//$this->set('paymentOptions', $this->Product->paymentOptions());
		$this->set('page_title_for_layout', __('Edit %s ', $this->request->data['Product']['name']));
		$this->set('title_for_layout', __('Edit %s ', $this->request->data['Product']['name']));
	}

	public function editArb($id = null, $child = null) {
		return $this->edit($id, $child);
	}

/**
 * Edit membership
 */
	protected function _editMembership($id = null) {
		$this->request->data = $this->Product->find('first', array('conditions' => array('Product.id' => $id), ));
		// fix up arb settings so they prefill the form correctly
		$this->request->data['Product']['arb_settings'] = unserialize($this->request->data['Product']['arb_settings']);
		$userRoles = array_diff($this->Product->Owner->UserRole->find('list'), array(
			'admin',
			'guests'
		));
		foreach ($userRoles as $key => $foreignKey) {
			$userRoles[$key] = Inflector::humanize(Inflector::singularize($foreignKey));
		}
		$this->set('foreignKeys', $userRoles);
		$this->set('page_title_for_layout', __('Edit %s ', $this->request->data['Product']['name']));
		$this->set('title_for_layout', __('Edit %s ', $this->request->data['Product']['name']));
		$this->view = 'edit_membership';
		$this->layout = 'default';
	}

/**
 *
 * @param type $id
 * @throws NotFoundException
 */
	protected function _editChild($id) {
		$this->Product->contain(array(
			'Option',
			'Parent'
		));
		$this->request->data = $this->Product->read(null, $id);
		$this->set('productBrands', $this->Product->ProductBrand->find('list'));
		$this->set('existingOptions', null);
		$this->set('options', null);
		$this->set('page_title_for_layout', __('Edit %s <small>a variant of %s</small>', $this->request->data['Product']['name'], $this->request->data['Parent']['name']));
		$this->set('title_for_layout', __('Edit %s <small>a variant of %s</small>', $this->request->data['Product']['name'], $this->request->data['Parent']['name']));
	}

/**
 * Delete method
 * 
 * @param uuid $id
 * @param uuid $optionsId
 */
	public function delete($id = null, $optionId = null) {
		$this->Product->id = $id;
		if (!$this->Product->exists()) {
			throw new NotFoundException(__('Invalid product'));
		}
		if (!empty($optionId)) {
			if ($this->Product->deleteChildByOptionType($id, $optionId)) {
				$this->Session->setFlash(__('Option deleted'), 'flash_success');
			}
			$this->redirect($this->referer());
		} else {
			if ($this->Product->delete($id)) {
				$this->Session->setFlash(__('Item deleted'), 'flash_success');
			}
			$this->redirect(array('action' => 'index'));
		}
	}
	
/**
 * Move up in the tree (lft = 4, rght = 5  ...  becomes lft = 2, rght = 3)
 */
	public function moveup($id = null, $delta = null) {
	    $this->Product->id = $id;
	    if (!$this->Product->exists()) {
	       throw new NotFoundException(__('Invalid item'));
	    }
	
	    if ($delta > 0) {
	        $this->Product->moveUp($this->Product->id, abs($delta));
	    } else {
	        $this->Session->setFlash('Please provide the number of positions the field should be moved down.');
	    }
	    return $this->redirect($this->referer());
	}
	
/**
 * Move down in the tree (lft = 4, rght = 5  ...  becomes lft = 6, rght = 7)
 */
	public function movedown($id = null, $delta = null) {
	    $this->Product->id = $id;
	    if (!$this->Product->exists()) {
	       throw new NotFoundException(__('Invalid item'));
	    }
	
	    if ($delta > 0) {
	        $this->Product->moveDown($this->Product->id, abs($delta));
	    } else {
	        $this->Session->setFlash('Please provide the number of positions the field should be moved down.');
	    }
	
	    return $this->redirect($this->referer());
	}

/**
 * Category method.
 *
 * @param void
 * @return void
 */
	public function category($categoryId = null) {
		if (!empty($categoryId)) {
			$this->paginate['joins'] = array( array(
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
 * Categories method
 * 
 * A page for editing product categories.
 * 
 * @param uuid $parentId
 */
	public function categories($parentId = null) {
		if (!empty($this->request->data['Option'])) {
			if ($this->Product->Option->save($this->request->data)) {
				$this->Session->setFlash(__('Option saved'), 'flash_success');
			}
		}
		if (!empty($this->request->data['Category'])) {
			if ($this->Product->Category->save($this->request->data)) {
				$this->Session->setFlash(__('Category saved'), 'flash_success');
			}
		}
		$conditions = !empty($parentId) ? array(
			'Category.parent_id' => $parentId,
			'Category.model' => 'Product'
		) : array('Category.model' => 'Product');
		$categories = $this->Product->Category->find('threaded', array('conditions' => $conditions));
		$options = $this->Product->Option->find('threaded');
		$this->set('parentCategories', Set::combine($categories, '{n}.Category.id', '{n}.Category.name'));
		$this->set('parentOptions', Set::combine($options, '{n}.Option.id', '{n}.Option.name'));
		$this->set(compact('categories', 'options'));
		$this->set('page_title_for_layout', __('Product Categories & Options'));
		$this->layout = 'default';
		return $categories;	// used in element Categories/categories
	}

/**
 * If we don't see any errors in a month delete this 11/1/2013 RK
 * 
 * update function is used for create child products with category options
 * selected
 * 
 */
	// public function update($parentId = null) {
		// if (!empty($this->request->data)) {
			// $data = $this->Product->find('first', array(
				// 'conditions' => array('Product.id' => $this->request->data['Product']['parent_id']),
				// 'recursive' => 2,
				// 'contain' => array(
					// 'ProductStore.id',
					// 'Category.id',
					// 'ProductBrand',
					// 'CategoryOption',
					// 'ProductPrice'
				// )
			// ));
			// foreach ($data['Category'] as $k => $val) {
				// $data['Category'][$k] = $data['Category'][$k]['id'];
			// }
			// // setting values to parent values
			// foreach ($this->request->data['Product'] as $fieldName => $fieldValue) {
				// if (!empty($fieldValue)) {
					// $data['Product'][$fieldName] = $fieldValue;
				// }
			// }
			// $data['Product']['id'] = '';
			// $data['GalleryImage'] = $this->request->data['GalleryImage'];
			// $data['CategoryOption'] = $this->request->data['CategoryOption'];
			// // create new CI
			// if ($this->Product->save($data, $this->Auth->user('id'))) {
				// $this->redirect(array(
					// 'action' => 'edit',
					// $this->request->data['Product']['parent_id']
				// ));
			// } else {
				// $this->Session->setFlash(__('New attribute save failed.', true));
				// $this->redirect(array(
					// 'action' => 'update',
					// $this->request->data['Product']['parent_id']
				// ));
			// }
		// }
		// $product = $this->Product->find('first', array(
			// 'conditions' => array('Product.id' => $parentId, ),
			// 'contain' => array('Children' => array('CategoryOption', ), )
		// ));
		// $this->set(compact('product'));
	// }

/**
 * If we don't get errors delete this in a month.  11/1/2013 RK
 * @todo I'm relatively sure we need to get rid of this crap
 */
	// public function get_product($id = null) {
		// $this->layout = false;
		// if (!empty($id)) {
			// $this->request->data = $this->Product->find('first', array(
				// 'conditions' => array('Product.id' => $id),
				// 'recursive' => 2,
				// 'contain' => array(
					// 'ProductStore.id',
					// 'Category.id',
					// 'ProductBrand',
					// 'CategoryOption',
					// 'ProductPrice'
				// )
			// ));
			// // remodifying data to bring support for controls
			// $this->request->data['ProductStore']['id'] = array('0' => $this->request->data['ProductStore']['id']);
			// $this->request->data['Category'] = Set::extract('/Category/id', $this->request->data);
			// $catOptions = array();
			// $catOptions = $this->Product->Category->CategoryOption->find('threaded', array(
				// 'conditions' => array('CategoryOption.category_id' => $this->request->data['Category']),
				// 'order' => 'CategoryOption.type'
			// ));
			// $this->set('options', $catOptions);
		// }
	// }

/**
 * If we don't get errors delete this in a month 11/1/2013
 * 
 * get_stock function is used to get the stock of products
 * based on the different options
 *
 * @todo I'm relatively sure we need to get rid of this crap
 */
	// public function get_stock() {
		// if (!empty($this->request->data)) {
			// $count_options = 0;
			// $category_ids = array();
			// foreach ($this->request->data['CategoryOption'] as $k => $val) {
				// if (is_array($val)) {
					// $count_options += count($val);
					// $category_ids = array_merge($category_ids, $val);
				// } else if (!empty($val)) {
					// $count_options += 1;
					// $category_ids[$k] = $val;
				// }
			// }
			// App::Import('Model', 'CategorizedOption');
			// $category = new CategorizedOption();
			// $productStores = $category->find('all', array(
				// 'fields' => array('count(*), foreign_key'),
				// 'conditions' => array('category_option_id' => $category_ids),
				// 'group' => "foreign_key having count(*) ={$count_options}"
			// ));
			// $id = array();
			// foreach ($productStores as $k => $val) {
				// $id[$k] = $val['CategorizedOption']['foreign_key'];
			// }
			// $products = $this->Product->find('all', array('conditions' => array('Product.id' => $id)));
			// $this->set(compact('products'));
		// }
		// $this->set('options', $this->Product->Category->CategoryOption->find('threaded', array(
			// 'conditions' => array('CategoryOption.category_id' => $this->request->params['named']['category_id']),
			// 'order' => 'CategoryOption.type'
		// )));
	// }

/**
 * If we don't get errors delete this in a month 11/1/2013
 */
	// public function get_items($productBrandId = null) {
		// if ($productBrandId) {
			// $this->set('items', $this->Product->find('list', array('conditions' => array('Product.product_brand_id' => $productBrandId))));
		// }
	// }

/**
 * Deprecated, and needs to be moved to a ProductHelper, which auto loads this data via a model call. (to skip acl)
 * 
 * Set the variables for n number of random
 * products for display in the random element.
 *
 * @param int The number of items you want to pull.
 */
	// public function random_product($count = 3, $productBrandId = null) {
		// $conditions = ($productBrandId) ? array('Product.product_brand_id' => $productBrandId) : false;
		// $products = $this->Product->find('all', array(
			// 'limit' => $count,
			// 'conditions' => $conditions,
			// 'order' => array('RAND()')
		// ));
		// if (!empty($products) && isset($this->request->params['requested'])) {
			// return $products;
		// } else {
			// return false;
		// }
	// }

/**
 * Deprecated, and needs to be moved to a ProductHelper, which auto loads this data via a model call. (to skip acl)
 *
 * This needs to be moved to a ProductHelper and 
 * function deal_a_day() uses to find deal of day according to
 * current dateTime
 */
	// public function dailyDeal() {
		// $options['order'] = array('Product.ended ASC');
		// $options['conditions'] = array('Product.ended >' => date('Y-m-d h:i:s'));
		// $options['conditions'] = array('Product.parent_id' => null);
		// $options['contain'] = array(
			// 'ProductBrand',
			// 'Gallery' => array('GalleryImage')
		// );
		// $dealItem = $this->Product->find('first', $options);
		// if (empty($dealItem)) {
			// $this->Session->setFlash(__('No Item Is Live', true));
		// } else {
			// return $dealItem;
		// }
	// }

/**
 * If we don't get errors delete this in a month 11/1/2013
 * 
 * get attribute values according to selected options
 *
 */
	// public function get_attribute_values() {
		// $this->layout = false;
		// $this->autoRender = false;
		// // remove null entries
		// $category_options = array_filter($this->request->data['CategoryOption']);
		// // clicked category option
		// //get products children bases on parent_id
		// $productId = !empty($this->request->data['TransactionItem']['parent_id']) ? $this->request->data['TransactionItem']['parent_id'] : $this->request->data['TransactionItem']['product_id'];
		// $ci = $this->Product->find('list', array(
			// 'fields' => array(
				// 'price',
				// 'stock',
				// 'id'
			// ),
			// 'conditions' => array('Product.parent_id' => $productId),
		// ));
		// $children = array_keys($ci);
		// $CO = ClassRegistry::init('Categories.CategorizedOption');
		// $conditions = array('CategorizedOption.foreign_key' => $children);
		// if ($category_options) {
			// $conditions['OR'] = array('category_option_id' => $category_options);
		// }
		// // all CI with options as clicked radio buttons and foreign key
		// $fkey_list = $CO->find('list', array(
			// 'fields' => array('foreign_key'),
			// 'conditions' => $conditions,
			// //'conditions'=> array(
			// //	'OR' => array('category_option_id' => $category_options),
			// //	'CategorizedOption.foreign_key' => $children),
		// ));
		// $co_ids = array();
		// if (count($fkey_list) > 0) {
			// // only do when there is a children of CI
			// // categirt option ids to be shown activated
			// $co_ids = $CO->find('list', array(
				// 'fields' => array('category_option_id'),
				// 'conditions' => array('foreign_key' => $fkey_list)
			// ));
		// }
		// //unique categorized options
		// $data['CategorizedOption'] = array_unique($co_ids);
		// if ($category_options) {
			// // data of CI with options as selected radio buttons
			// $fk_list = $CO->find('list', array(
				// 'fields' => array('foreign_key'),
				// 'conditions' => array(
					// 'category_option_id' => $category_options,
					// 'CategorizedOption.foreign_key' => $children
				// ),
				// 'group' => "foreign_key having count(*) =" . count($category_options)
			// ));
			// if (count($fk_list) == 1) {
				// foreach ($fk_list as $fk) {
					// $data['Product']['id'] = $fk;
					// if (is_array($ci[$fk])) {
						// foreach ($ci[$fk] as $price => $stock) {
							// $data['Product']['stock'] = $stock;
							// $data['Product']['price'] = $price;
						// }
					// }
				// }
			// }
		// }
		// echo json_encode($data);
	// }

}

if (!isset($refuseInit)) {
	class ProductsController extends AppProductsController {
	}

}