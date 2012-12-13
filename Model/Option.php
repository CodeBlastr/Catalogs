<?php
App::uses('ProductsAppModel', 'Products.Model');
/**
 * Option model
 *
 * @package products
 * @subpackage products.models
 */
class Option extends ProductsAppModel {
    
/**
 * Use Table
 *
 * @var string
 */
    public $useTable = 'product_options';

/**
 * Name
 *
 * @var string
 */
    public $name = 'Option';

/**
 * ActsAs
 *
 * @var array
 */
	public $actsAs = array('Tree');

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array();


/**
 * hasMany associations
 *
 * @var array $hasMany
 */
	public $hasMany = array(
		'ProductsOption' => array(
			'className' => 'Products.ProductsOption',
			'foreignKey' => 'option_id',
			'dependent' => true
			),
		'Children' => array(
			'className' => 'Products.Option',
			'foreignKey' => 'parent_id',
			'dependent' => true,
			'finderQuery' => 'SELECT * FROM `product_options` AS `Children` WHERE `Children`.`parent_id` = ({$__cakeID__$})',
			),
		);

	public function __construct($id = false, $table = null, $ds = null) {
    	parent::__construct($id, $table, $ds);
 		$this->order = $this->alias . '.lft';
    }

}