<?php
App::uses('ProductBid', 'Products.Model');

/**
 * Product Test Case
 *
 */
class ProductTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.Products.ProductBid'
        // 'app.Alias',
        // 'app.Meta',
        // 'plugin.Contacts.Contact',
        // 'plugin.Categories.Category',
        // 'plugin.Categories.Categorized',
        // 'plugin.Categories.CategorizedOption',
        // 'plugin.Galleries.Gallery',
        // 'plugin.Galleries.GalleryImage',
        // 'plugin.Products.Product',
        // 'plugin.Products.ProductBrand',
        // 'plugin.Products.ProductPrice',
        // 'plugin.Products.ProductStore',
        // 'plugin.Products.ProductsProductOption',
        // 'plugin.Webpages.Webpage',
        // 'plugin.Users.User'
        );
/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ProductBid = ClassRegistry::init('Products.ProductBid');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ProductBid);
		parent::tearDown();
	}

/**
 * testSave method
 *
 * @return void
 */
	public function testSaveUnderBid() {
		$data['ProductBid'] = array(
			'user_id' => 7,
			'product_id' => 15,
			'amount' => '5.00'
			);
		$this->ProductBid->create();
		if ($this->ProductBid->save($data)) {
			$data['ProductBid'] = array(
				'user_id' => 7,
				'product_id' => 15,
				'amount' => '4.00'  // this is lower than the current high bid of 5.00
				);
			$this->ProductBid->create();
			$this->ProductBid->save($data);
		}
		
		$invalidFields = $this->ProductBid->invalidFields();
		$this->assertTrue(!empty($invalidFields['amount']));
	}

/**
 * testSave method
 *
 * @return void
 */
	public function testSave() {
		$data['ProductBid'] = array(
			'user_id' => 7,
			'product_id' => 15,
			'amount' => '5.00'
			);
		$this->ProductBid->save($data);
		$this->assertTrue(!empty($this->ProductBid->id));
	}
}
