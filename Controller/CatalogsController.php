<?php
/**
 * Catalogs Controller
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
 * @link          http://zuha.com Zuha™ Project
 * @package       zuha
 * @subpackage    zuha.app.plugins.catalogs
 * @since         Zuha(tm) v 0.0.1
 * @license       GPL v3 License (http://www.gnu.org/licenses/gpl.html) and Future Versions
 */
class CatalogsController extends CatalogsAppController {

	public $name = 'Catalogs';
	public $uses = 'Catalogs.Catalog';
	


/**
 * Ecommerce dashboard.
 *
 */
	public function dashboard(){
		$this->set('transactionStatuses', ClassRegistry::init('Orders.OrderTransaction')->statuses());
		$this->set('itemStatuses', ClassRegistry::init('Orders.OrderItem')->statuses());
	}

	
	function index() {
		$this->paginate = array(
			'fields' => array(
				'id',
				'name',
				'summary',
				'published',
				));
		$this->set('catalogs', $this->paginate());
	}
	
	/*
	 * Viewing a catalog. 
	 * Displays all the items in the catalog.
	 */
	function view($id) {
		$catalog = $this->Catalog->find('first' , array(
			'conditions' => array(
				'Catalog.id'=>$id
				),
			'fields' => array(
				'name',
				'summary',
				'introduction',
				'description',
				'additional',
				),
			));
		$this->set(compact('catalog'));
	}

	function add() {
		if (!empty($this->request->data)) {
			$this->Catalog->create();
			if ($this->Catalog->save($this->request->data)) {
				$this->flash(__('Catalog saved.', true), array('action'=>'index'));
			} else {
			}
		}
	}

	function edit($id = null) {
		if (!$id && empty($this->request->data)) {
			$this->flash(__('Invalid Catalog', true), array('action'=>'index'));
		}
		if (!empty($this->request->data)) {
			if ($this->Catalog->save($this->request->data)) {
				$this->flash(__('The Catalog has been saved.', true), array('action'=>'index'));
			} else {
			}
		}
		if (empty($this->request->data)) {
			$this->request->data = $this->Catalog->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->flash(__('Invalid Catalog', true), array('action'=>'index'));
		}
		if ($this->Catalog->delete($id)) {
			$this->flash(__('Catalog deleted', true), array('action'=>'index'));
		}
	}
	
}
?>