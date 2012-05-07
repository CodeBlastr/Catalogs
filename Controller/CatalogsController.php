<?php
/**
 * Catalogs Controller
 *
 * Handles the logic for catalog items. 
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
 * @link          http://zuha.com Zuha™ Project
 * @package       zuha
 * @subpackage    zuha.app.plugins.catalogs
 * @since         Zuha(tm) v 0.0.1
 * @license       GPL v3 License (http://www.gnu.org/licenses/gpl.html) and Future Versions
 */
class CatalogsController extends CatalogsAppController {

/**
 * var string
 */
	public $name = 'Catalogs';

/**
 * var string
 */
	public $uses = 'Catalogs.Catalog';
	


/**
 * Ecommerce dashboard.
 *
 */
	public function dashboard(){
		$this->set('transactionStatuses', ClassRegistry::init('Orders.OrderTransaction')->statuses());
		$this->set('itemStatuses', ClassRegistry::init('Orders.OrderItem')->statuses());
		$this->set('page_title_for_layout', __('Ecommerce Dashboard'));
	}

	
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
		$this->set('catalogs', $this->paginate());
	}
	
/**
 * View method.
 *
 * @param string
 * @return void
 */
	public function view($id = null) {
		# setup paginate
		$this->paginate['contain']['CatalogItemPrice']['conditions']['CatalogItemPrice.user_role_id'] = $this->userRoleId;
		$this->paginate['conditions']['OR'] = array(
			array('CatalogItem.end_date >' => date('Y-m-d h:i:s')),
			array('CatalogItem.end_date' => null),
			array('CatalogItem.end_date' => '0000-00-00 00:00:00')
		);
		$this->paginate['conditions']['CatalogItem.parent_id'] = null;
		$catalogItems = $this->paginate('CatalogItem');
		# removes items and changes prices based on user role
		$catalogItems = $this->Catalog->CatalogItem->cleanItemsPrices($catalogItems, $this->userRoleId);
		$this->set(compact('catalogItems'));
	}

	
/**
 * Add method.
 *
 * @param void
 * @return void
 */
	public function add() {
		if (!empty($this->request->data)) {
			$this->Catalog->create();
			if ($this->Catalog->save($this->request->data)) {
				$this->flash(__('Catalog saved.'), array('action' => 'index'));
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
			$this->flash(__('Invalid Catalog', true), array('action' => 'index'));
		}
		if (!empty($this->request->data)) {
			if ($this->Catalog->save($this->request->data)) {
				$this->flash(__('The Catalog has been saved.'), array('action' => 'index'));
			} else {
			}
		}
		if (empty($this->request->data)) {
			$this->request->data = $this->Catalog->read(null, $id);
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
			$this->flash(__('Invalid Catalog'), array('action' => 'index'));
		}
		if ($this->Catalog->delete($id)) {
			$this->flash(__('Catalog deleted'), array('action' => 'index'));
		}
	}
	
}