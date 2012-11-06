<?php
/**
 * ProductStores Controller
 *
 * Handles the logic for product stores. 
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
class ProductStoresController extends ProductsAppController {

/**
 * var string
 */
	public $name = 'ProductStores';

/**
 * var string
 */
	public $uses = 'Products.ProductStores';

	
/**
 * Index method.
 *
 * @param void
 * @return void
 */
	public function index() {
		$this->paginate = array(
			'fields' => array(
				'id',
				'name',
				'summary',
				'is_public',
				));
		$this->set('productStores', $this->paginate());
	}
	
/**
 * View method.
 *
 * @param string
 * @return void
 */
	public function view($id = null) {
		# setup paginate
		$this->paginate['contain']['ProductPrice']['conditions']['ProductPrice.user_role_id'] = $this->userRoleId;
		$this->paginate['conditions']['OR'] = array(
			array('Product.ended >' => date('Y-m-d h:i:s')),
			array('Product.ended' => null),
			array('Product.ended' => '0000-00-00 00:00:00')
		);
		$this->paginate['conditions']['Product.parent_id'] = null;
		$products = $this->paginate('Product');
		# removes items and changes prices based on user role
		$products = $this->ProductStore->Product->cleanItemsPrices($products, $this->userRoleId);
		$this->set(compact('products'));
	}

	
/**
 * Add method.
 *
 * @param void
 * @return void
 */
	public function add() {
		if (!empty($this->request->data)) {
			$this->ProductStore->create();
			if ($this->ProductStore->save($this->request->data)) {
				$this->flash(__('Store saved.'), array('action' => 'index'));
			} else {
			}
		}
	}

	
/**
 * Edit method.
 *
 * @param string
 * @return void
 */
	public function edit($id = null) {
		if (!$id && empty($this->request->data)) {
			$this->flash(__('Invalid Store', true), array('action' => 'index'));
		}
		if (!empty($this->request->data)) {
			if ($this->ProductStore->save($this->request->data)) {
				$this->flash(__('The store has been saved.'), array('action' => 'index'));
			} else {
			}
		}
		if (empty($this->request->data)) {
			$this->request->data = $this->ProductStore->read(null, $id);
		}
	}

	
/**
 * Delete method.
 *
 * @param string
 * @return void
 */
	public function delete($id = null) {
		if (!$id) {
			$this->flash(__('Invalid store'), array('action' => 'index'));
		}
		if ($this->ProductStore->delete($id)) {
			$this->flash(__('Store deleted'), array('action' => 'index'));
		}
	}
	
}