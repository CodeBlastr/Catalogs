<?php
App::uses('ProductsAppController', 'Products.Controller');  
/**
 * Product Brands Controller
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
 * @subpackage    zuha.app.plugins.products
 * @since         Zuha(tm) v 0.0.1
 * @license       GPL v3 License (http://www.gnu.org/licenses/gpl.html) and Future Versions
 */
class ProductBrandsController extends ProductsAppController {

	public $name = 'ProductBrands';
	public $uses = 'Products.ProductBrand';
	public $paginate = array();

	public function index() {
		$this->paginate['fields'] = array('id', 'name');
		$this->paginate['order'] = array('ProductBrand.name');
		$this->set('displayName', 'name');
		$this->set('displayDescription', '');
		$this->set('productBrands', $this->paginate());
	}


	public function add() {
		if (!empty($this->request->data)) {
			if ($this->ProductBrand->save($this->request->data)) {
				$this->Session->setFlash(__('Brand saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Brand could not be saved. Please, try again.'));
			}
		}
		$this->set('products', $this->ProductBrand->Product->find('list'));
	}

	public function edit($id = null) {
		if (!empty($this->request->data)) {
			if ($this->ProductBrand->save($this->request->data)) {
				$this->Session->setFlash(__('The ProductBrand has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The ProductBrand could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->request->data)) {
			$this->request->data = $this->ProductBrand->read(null, $id);
			$this->set('products', $this->ProductBrand->Product->find('list'));
		}
	}


/**
 * @todo		This cleanItemsPrices thing is probably in the model.  Check and remove unecessary code from this function.
 */
	public function view($id = null) {
		$productBrand = $this->ProductBrand->find('first' , array(
			'conditions'=>array(
				'ProductBrand.id'=>$id
				),
			));

		$this->set(compact('productBrand'));

		# get the items for this brand
		$this->paginate['conditions']['Product.product_brand_id'] = $id;
		$this->paginate['conditions']['Product.parent_id'] = null; // don't show child products on the brand page
		$this->paginate['contain']['ProductPrice']['conditions']['ProductPrice.user_role_id'] = $this->userRoleId;
		$products = $this->paginate('Product');
		# removes items and changes prices based on user role
		$products = $this->ProductBrand->Product->cleanItemsPrices($products, $this->userRoleId);
		$this->set(compact('products'));
	}

	public function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for ProductBrand', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->ProductBrand->delete($id)) {
			$this->Session->setFlash(__('ProductBrand deleted', true));
			$this->redirect(array('action'=>'index'));
		}
	}

	public function get_brands($productID = null) {
		if ($productID) {
			$this->set('brands', $this->ProductBrand->find('list', array(
					'conditions' => array('ProductBrand.product_id'=>$productID))));
		}
	}

}