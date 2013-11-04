<?php
/**
 * Copyright 2010, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2010, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * CakePHP Product Plugin
 *
 * Rating helper
 *
 * @package 	ratings
 * @subpackage 	ratings.views.helpers
 */
class ProductHelper extends AppHelper {

/**
 * helpers variable
 *
 * @var array
 */
	public $helpers = array ('Html', 'Form', 'Js' => 'Jquery');

/**
 * Allowed types of html list elements
 *
 * @var array $allowedTypes
 */
	public $allowedTypes = array('ul', 'ol', 'radio');

/**
 * Constructor method
 * 
 */
    public function __construct(View $View, $settings = array()) {
    
    	$this->View = $View;
    	//$this->defaults = array_merge($this->defaults, $settings);
		parent::__construct($View, $settings);
    }


/**
 * Handle data method
 * 
 * Might be a break from the MVC pattern but the benefits seem too high.
 * 1. Being able to just do $this->element('ratable', array('model' => 'MyModel', 'foreignKey' => $someForeignKey))
 * 2. We don't need to load a bunch of components and helpers and worry about whether it is need for multi-sites
 * 3. Commence beating me :) 
 */
 	public function latestProduct() {
	 	App::uses('Product', 'Products.Model');
	    $Product = new Product;
		$data = $Product->find('all', array(
			'order' => array(
				'Product.created' => 'DESC'
				),
				'limit' => 2
			));
		return $data;
 	}


}