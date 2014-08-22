<?php
App::uses('AppModel', 'Model');
/**
 * Products App Model
 *
 *
 * PHP versions 5.3
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
 * @subpackage    zuha.app.plugins.products.model
 * @since         Zuha(tm) v 0.0.1
 * @license       GPL v3 License (http://www.gnu.org/licenses/gpl.html) and Future Versions
 */

class ProductsAppModel extends AppModel {
	
/**
 * Menu Init method
 * Used by WebpageMenuItem to initialize when someone creates a new menu item for the users plugin.
 * 
 */
 	public function menuInit($data = null) {
 		App::uses('Product', 'Products.Model');
		$Product = new Product();
		$product = $Product->find('first');
		if (!empty($product)) {
	 		// link to properties index and first property
			$data['WebpageMenuItem']['item_url'] = '/products/products/index';
			$data['WebpageMenuItem']['item_text'] = 'Product List';
			$data['WebpageMenuItem']['name'] = 'Product List';
			$data['ChildMenuItem'] = array(
				array(
					'name' => $product['Product']['name'],
					'item_text' => $product['Product']['name'],
					'item_url' => '/products/products/view/'.$product['Product']['id']
				),
				array(
					'name' => 'Add Product',
					'item_text' => 'Add Product',
					'item_url' => '/products/products/add'
				)
			);
		}
 		return $data;
 	}

}