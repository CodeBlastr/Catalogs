<?php 
class ProductsSchema extends CakeSchema {

	public $renames = array();

	public function __construct($options = array()) {
		parent::__construct();
	}
	
	public function before($event = array()) {
	    $db = ConnectionManager::getDataSource('default');
	    $db->cacheSources = false;
		App::uses('UpdateSchema', 'Model'); 
		$this->UpdateSchema = new UpdateSchema;
		$before = $this->UpdateSchema->before($event);
		return $before;
	}

	public function after($event = array()) {
		$this->_installData($event);
		$this->UpdateSchema->rename($event, $this->renames);
		$this->UpdateSchema->after($event);
	}

	public $product_brands = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'summary' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'store_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'owner_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'creator_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'modifier_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

	public $product_options = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'parent_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'lft' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'rght' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'record_count' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

	public $product_prices = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'price' => array('type' => 'float', 'null' => false, 'default' => null),
		'store_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'product_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'user_role_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'creator_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'modifier_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

	public $product_stores = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'summary' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'introduction' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'additional' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'is_public' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
		'creator_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'modifier_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'started' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'ended' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

	public $products = array( 
        'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'), 
        'parent_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'), 
        'lft' => array('type' => 'integer', 'null' => true, 'default' => null), 
        'rght' => array('type' => 'integer', 'null' => true, 'default' => null), 
        'sku' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'), 
        'name' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'), 
        'summary' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'), 
        'description' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'), 
        'video_url' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 128, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'), 
        'is_public' => array('type' => 'boolean', 'null' => true, 'default' => null), 
        'product_brand_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'), 
        'store_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'), 
        'stock' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10), 
        'cart_min' => array('type' => 'integer', 'null' => true, 'default' => null), 
        'cart_max' => array('type' => 'integer', 'null' => true, 'default' => null), 
        'cost' => array('type' => 'float', 'null' => true, 'default' => null), 
        'price' => array('type' => 'float', 'null' => true, 'default' => null), 
        'weight' => array('type' => 'float', 'null' => true, 'default' => null), 
        'height' => array('type' => 'float', 'null' => true, 'default' => null), 
        'width' => array('type' => 'float', 'null' => true, 'default' => null), 
        'length' => array('type' => 'float', 'null' => true, 'default' => null), 
        'shipping_type' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'), 
        'shipping_charge' => array('type' => 'float', 'null' => true, 'default' => null), 
        'payment_type' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'), 
        'arb_settings' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'), 
        'is_virtual' => array('type' => 'boolean', 'null' => true, 'default' => null), 
        'is_buyable' => array('type' => 'boolean', 'null' => true, 'default' => null), 
        'is_expired' => array('type' => 'boolean', 'null' => true, 'default' => null), 
        'hours_expire' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'comment' => 'Used to denote how long a product should be available after purchase.'), 
        'order' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 2), 
        'model' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 155, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'), 
        'foreign_key' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'), 
        'owner_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'), 
        'children' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'comment' => 'counter cache'), 
        'seller_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'), 
        'creator_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'), 
        'modifier_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'), 
        'started' => array('type' => 'datetime', 'null' => true, 'default' => null), 
        'ended' => array('type' => 'datetime', 'null' => true, 'default' => null), 
        'search_tags' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'), 
        'created' => array('type' => 'datetime', 'null' => false, 'default' => null), 
        'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)), 
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM') 
	);

	public $products_product_options = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'option_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'product_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);
	
/**
 * Install Data Method
 * 
 * @param string $event
 */
	protected function _installData($event) {
		if (isset($event['create'])) {
			switch ($event['create']) {
	            case 'products':
	                $Model = ClassRegistry::init('Products.Product');
					$Model->create();
					$Model->saveAll(array(
						'Product' => array(
							'parent_id' => null, 
							'lft' => 1, 
							'rght' => 2, 
							'sku' => '481298791', 
							'name' => 'Product One', 
							'summary' => 'No, no I didnt. Unless... you consider "World\'s Coolest Daddy" a job. I\'m looking for something', 
							'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla varius, lectus vulputate accumsan dapibus, mauris tortor ullamcorper dolor, pellentesque pulvinar tellus neque sit amet sapien. Nunc non dapibus tellus. Etiam luctus velit eget tellus vestibulum, sagittis faucibus erat aliquet. Curabitur fermentum massa dapibus auctor elementum. Cras feugiat semper accumsan. Aenean fringilla ut ipsum quis molestie. In ultrices massa risus, vitae dictum dui porttitor at. Aliquam erat volutpat. Integer mattis, neque varius pharetra cursus, neque lacus adipiscing massa, id mollis urna tortor eget risus. Aliquam blandit ipsum id scelerisque auctor.', 
							'is_public' => 1, 
							'price' => '5.00', 
							'is_buyable' => 1, 
							'is_expired' => 0, 
							'model' => 'Product', 
							'search_tags' => 'product one really cool product',
							'created' => date('Y-m-d h:i:s'),
							'modified' => date('Y-m-d h:i:s'),
						)
					));
				break;
			}
		}
	}
}
