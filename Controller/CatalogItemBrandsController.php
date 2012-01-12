<?php
/**
 * Catalog Brands Controller
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
 * @link          http://zuha.com Zuha� Project
 * @package       zuha
 * @subpackage    zuha.app.plugins.catalogs
 * @since         Zuha(tm) v 0.0.1
 * @license       GPL v3 License (http://www.gnu.org/licenses/gpl.html) and Future Versions
 */
class CatalogItemBrandsController extends CatalogsAppController {

	public $name = 'CatalogItemBrands';
	public $uses = 'Catalogs.CatalogItemBrand';
	public $paginate = array();

	public function index() {
		$this->paginate = array(
			'fields' => array(
				'id',
				'name',
				),
			'order' => array(
				'CatalogItemBrand.name'
				),
			'limit' => 10,
			);
		$this->set('displayName', 'name');
		$this->set('displayDescription', '');
		#$this->paginate = $this->settings;
		$this->set('catalogItemBrands', $this->paginate());
	}


	public function add() {
		if (!empty($this->request->data)) {
			if ($this->CatalogItemBrand->add($this->request->data)) {
				$this->Session->setFlash(__('The CatalogItemBrand has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The CatalogItemBrand could not be saved. Please, try again.', true));
			}
		}
		$this->set('catalogs', $this->CatalogItemBrand->Catalog->find('list'));
	}

	public function edit($id = null) {
		if (!empty($this->request->data)) {
			if ($this->CatalogItemBrand->save($this->request->data)) {
				$this->Session->setFlash(__('The CatalogItemBrand has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The CatalogItemBrand could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->request->data)) {
			$this->request->data = $this->CatalogItemBrand->read(null, $id);
			$this->set('catalogs', $this->CatalogItemBrand->Catalog->find('list'));
		}
	}
	
	
/**
 * @todo		This cleanItemsPrices thing is probably in the model.  Check and remove unecessary code from this function.
 */
	public function view($id = null) {
		$catalogItemBrand = $this->CatalogItemBrand->find('first' , array(
			'conditions'=>array(
				'CatalogItemBrand.id'=>$id
				),
			));

		$this->set(compact('catalogItemBrand'));

		# get the items for this brand
		$this->paginate['conditions']['CatalogItem.catalog_item_brand_id'] = $id;
		$this->paginate['contain']['CatalogItemPrice']['conditions']['CatalogItemPrice.user_role_id'] = $this->userRoleId;
		$catalogItems = $this->paginate($this->CatalogItemBrand->CatalogItem, array('catalog_item_brand_id'=>$id));
		# removes items and changes prices based on user role
		$catalogItems = $this->CatalogItemBrand->CatalogItem->cleanItemsPrices($catalogItems, $this->userRoleId);
		$this->set(compact('catalogItems'));
	}

	public function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for CatalogItemBrand', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->CatalogItemBrand->delete($id)) {
			$this->Session->setFlash(__('CatalogItemBrand deleted', true));
			$this->redirect(array('action'=>'index'));
		}
	}

	public function get_brands($catalogID = null) {
		if ($catalogID) {
			$this->set('brands', $this->CatalogItemBrand->find('list', array(
					'conditions' => array('CatalogItemBrand.catalog_id'=>$catalogID))));
		}
	}

}
?>