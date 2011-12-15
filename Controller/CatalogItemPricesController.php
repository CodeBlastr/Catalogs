<?php
/**
 * Catalog Item Prices Controller
 *
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
 * @link          http://zuha.com Zuha™ Project
 * @package       zuha
 * @subpackage    zuha.app.plugins.catalogs
 * @since         Zuha(tm) v 0.0.1
 * @license       GPL v3 License (http://www.gnu.org/licenses/gpl.html) and Future Versions
 */
class CatalogItemPricesController extends CatalogsAppController {

	public $name = 'CatalogItemPrices';
	public $helpers = array('Html', 'Form');
	public $uses = 'Catalogs.CatalogItemPrice';

	function index() {
		$this->CatalogItemPrice->recursive = 0;
		$this->set('catalogItemPrices', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->flash(__('Invalid Price', true), array('action'=>'index'));
		}
		$this->set('price', $this->CatalogItemPrice->read(null, $id));
	}

	function add($catalogItemId = null) {
	}

	function edit($catalogItemId = null) {
		if (!empty($this->request->data['CatalogItemPrice'])) : 
			if ($this->CatalogItemPrice->saveAll($this->request->data['CatalogItemPrice'])) :
				$this->Session->setFlash(__('Successful price update.', true));
				$this->redirect(array('plugin' => 'catalogs', 'controller' => 'catalog_items', 'action' => 'edit', $this->request->data['CatalogItemPrice'][0]['catalog_item_id']));
			else : 
				$this->Session->setFlash(__('Advanced pricing save failed.', true));
				$this->redirect($this->referer());
			endif;
		endif;
		
		if (!empty($catalogItemId)) : 
			$catalogItem = $this->CatalogItemPrice->CatalogItem->find('first', array('conditions' => array('CatalogItem.id' => $catalogItemId)));
			$userRoles = $this->CatalogItemPrice->UserRole->find('list');
			$priceTypes = $this->CatalogItemPrice->PriceType->find('list', array('conditions' => array('PriceType.type' => 'PRICETYPE'),));
			$this->set(compact('catalogItem', 'userRoles', 'priceTypes'));
		else : 
			$this->Session->setFlash(__('Invalid Item', true));
			$this->redirect($this->referer());
		endif;
	}

	function delete($id = null) {
		if (!$id) {
			$this->flash(__('Invalid Price', true), array('action'=>'index'));
		}
		if ($this->CatalogItemPrice->delete($id)) {
			$this->flash(__('CatalogItemPrice deleted', true), array('action'=>'index'));
		}
	}


	function admin_index() {
		$this->CatalogItemPrice->recursive = 0;
		$this->set('catalogItemPrices', $this->paginate());
	}

	function admin_view($id = null) {
		if (!$id) {
			$this->flash(__('Invalid Price', true), array('action'=>'index'));
		}
		$this->set('price', $this->CatalogItemPrice->read(null, $id));
	}

	function admin_add($catalogItemId = null) {
		if (!empty($this->request->data)) {
			$this->set('referer', $this->referer());
			$userRoles = $this->CatalogItemPrice->UserRole->find('list');
			$priceTypes = ($this->CatalogItemPrice->PriceType->find('list', array('conditions' => array('PriceType.type' => 'PRICETYPE'),)));
			$this->set(compact('userRoles', 'priceTypes'));
		}
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->request->data)) {
			$this->flash(__('Invalid Price', true), array('action'=>'index'));
		}
		if (!empty($this->request->data)) {
			if ($this->CatalogItemPrice->save($this->request->data)) {
				$this->flash(__('The Price has been saved.', true), array('action'=>'index'));
			} else {
			}
		}
		if (empty($this->request->data)) {
			$this->request->data = $this->CatalogItemPrice->read(null, $id);
		}
		$userRoles = $this->CatalogItemPrice->UserRole->find('list');
		$catalogItems = $this->CatalogItemPrice->CatalogItem->find('list');
		$this->set(compact('userRoles','catalogItems'));
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->flash(__('Invalid Price', true), array('action'=>'index'));
		}
		if ($this->CatalogItemPrice->delete($id)) {
			$this->flash(__('CatalogItemPrice deleted', true), array('action'=>'index'));
		}
	}

}
?>