<?php 
class CatalogsSchema extends CakeSchema {

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {
	}
	
	public $catalog_item_brands = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'summary' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'catalog_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'owner_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'comment' => 'User responsible for this brand (and possibly the fulfillment of orders for this brand)'),
		'creator_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'modifier_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'description' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);
	public $catalog_item_prices = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'price' => array('type' => 'float', 'null' => true, 'default' => NULL, 'length' => '10,2'),
		'catalog_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'catalog_item_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'user_role_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'creator_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'modifier_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);
	public $catalog_items = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'parent_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'lft' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'rght' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'sku' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'summary' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'video_url' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 128, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'started' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'ended' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'is_public' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'catalog_item_brand_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'catalog_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'stock' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'stock_item' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'cart_min' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'cart_max' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'cost' => array('type' => 'float', 'null' => true, 'default' => NULL),
		'price' => array('type' => 'float', 'null' => true, 'default' => NULL),
		'location' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'weight' => array('type' => 'float', 'null' => true, 'default' => NULL),
		'height' => array('type' => 'float', 'null' => true, 'default' => NULL),
		'width' => array('type' => 'float', 'null' => true, 'default' => NULL),
		'length' => array('type' => 'float', 'null' => true, 'default' => NULL),
		'shipping_type' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'shipping_charge' => array('type' => 'float', 'null' => true, 'default' => NULL),
		'payment_type' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'arb_settings' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'is_virtual' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'hours_expire' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'comment' => 'Used to denote how long a catalog item should be available after purchase.'),
		'model' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 155, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'foreign_key' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'owner_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'children' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'creator_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'modifier_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);
	public $catalogs = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'alias_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'summary' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'introduction' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'additional' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'started' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'ended' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'is_public' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
		'creator_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'comment' => 'the user id who created this catalog'),
		'modifier_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);
}
