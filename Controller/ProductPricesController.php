<?php
/**
 * Product Prices Controller
 *
 *
 * PHP versions 5
 *
 * Zuha(tm) : Business Management Applications (http://zuha.com)
 * Copyright 2009-2012, Zuha Foundation Inc. (http://zuha.org)
 *
 * Licensed under GPL v3 License
 * Must retain the above copyright notice and release modifications publicly.
 * @copyright     Copyright 2009-2012, Zuha Foundation Inc. (http://zuha.com)
 * @link          http://zuha.com Zuha� Project
 * @package       zuha
 * @subpackage    zuha.app.plugins.products
 * @since         Zuha(tm) v 0.0.1
 * @license       GPL v3 License (http://www.gnu.org/licenses/gpl.html) and Future Versions
 */
	public $name = 'ProductPrices';
	public $helpers = array('Html', 'Form');
	public $uses = 'Products.ProductPrice';

	public function index() {
		$this->ProductPrice->recursive = 0;
		$this->set('productPrices', $this->paginate());
	}

	public function view($id = null) {
		if (!$id) {
			$this->flash(__('Invalid Price', true), array('action'=>'index'));
		}
		$this->set('price', $this->ProductPrice->read(null, $id));
	}

	public function add($productId = null) {
	}

	public function edit($productId = null) {
		if (!empty($this->request->data['ProductPrice'])) :
			if ($this->ProductPrice->saveAll($this->request->data['ProductPrice'])) :
				$this->Session->setFlash(__('Successful price update.', true));
				$this->redirect(array('plugin' => 'products', 'controller' => 'products', 'action' => 'edit', $this->request->data['ProductPrice'][0]['product_id']));
			else :
				$this->Session->setFlash(__('Advanced pricing save failed.', true));
				$this->redirect($this->referer());
			endif;
		endif;

		if (!empty($productId)) :
			$product = $this->ProductPrice->Product->find('first', array('conditions' => array('Product.id' => $productId)));
			$userRoles = $this->ProductPrice->UserRole->find('list');
			$this->set(compact('product', 'userRoles'));
		else :
			$this->Session->setFlash(__('Invalid Item', true));
			$this->redirect($this->referer());
		endif;
	}

	public function delete($id = null) {
		if (!$id) {
			$this->flash(__('Invalid Price', true), array('action'=>'index'));
		}
		if ($this->ProductPrice->delete($id)) {
			$this->flash(__('ProductPrice deleted', true), array('action'=>'index'));
		}
	}


}