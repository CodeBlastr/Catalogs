<?php
App::uses('ProductsAppController', 'Products.Controller');
class ProductBidsController extends ProductsAppController {

	public $name = 'ProductBids';
	public $uses = 'Products.ProductBid';

	public function add() {
		if ($this->request->is('post')) {
			$this->request->data('ProductBid.user_id', $this->userId);
			if ($this->ProductBid->save($this->request->data)) {
				$this->Session->setFlash('Bid received');
				$this->redirect($this->referer());
			} else {
				$this->Session->setFlash('Unable to process bid. Please try again. ' . ZuhaInflector::flatten($this->ProductBid->invalidFields()));
				$this->redirect($this->referer());
			}
		}
	}

}
