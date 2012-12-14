<?php
App::uses('ProductsAppController', 'Products.Controller');
/**
 * Options Controller
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
 * @license       GPL v3 License (http://www.gnu.org/licenses/gpl.html) and Future Versions
 */
class OptionsController extends ProductsAppController {

/**
 * Name
 *
 * @var string
 */
	public $name = 'Options';

/**
 * Uses
 *
 * @var string
 */
	public $uses = 'Products.Option';
    
    public function add() {
        if (!empty($this->request->data['Option'])) {
            if ($this->Option->save($this->request->data)) {
                $this->Session->setFlash(__('Option saved'));
            }
        }
        $this->redirect($this->referer());
    }

	public function delete($id = null) {
		$this->Option->id = $id;
		if (!$this->Option->exists()) {
			throw new NotFoundException(__('Invalid option'));
		}
		if ($this->Option->delete($id)) {
			$this->Session->setFlash(__('Option deleted'));
		}
    	$this->redirect($this->referer());
	}
}