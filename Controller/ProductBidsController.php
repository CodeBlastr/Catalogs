<?php
App::uses('ProductsAppController', 'Products.Controller');
class ProductBidsController extends ProductsAppController {

	public $name = 'ProductBids';
	public $uses = 'Products.ProductBid';

	public function add() {
		if (!empty($this->request->data)) {
			$this->request->data('ProductBid.user_id', $this->userId);
			$bidSaved = $this->ProductBid->save($this->request->data);
			if ($bidSaved) {
				$this->Session->setFlash('Bid received');
				$this->redirect($this->referer());
			} else {
				$this->Session->setFlash('Unable to process bid.  Try again.');
				$this->redirect($this->referer());
			}
		}
	}

}
