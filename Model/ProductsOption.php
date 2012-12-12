<?php
App::uses('ProductsAppModel', 'Products.Model');
/**
 * ProductsOption model
 *
 * @package categories
 * @subpackage categories.models
 */
class ProductsOption extends ProductsAppModel {

/**
 * Use Table
 *
 * @var string
 */
    public $useTable = 'products_product_options';
    
/**
 * Name
 *
 * @var string
 */
    public $name = 'ProductsOption';

/**
 * Belongs To
 * 
 * @var array
 */
	public $belongsTo = array(
		'Option' => array(
			'className' => 'Products.Option'
            )
        );
            
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array();

}