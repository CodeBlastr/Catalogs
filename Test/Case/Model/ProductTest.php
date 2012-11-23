<?php
App::uses('Product', 'Products.Model');

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
        'app.Alias',
        'app.Meta',
        'plugin.Contacts.Contact',
        'plugin.Categories.Category',
        'plugin.Categories.Categorized',
        'plugin.Categories.CategorizedOption',
        'plugin.Galleries.Gallery',
        'plugin.Galleries.GalleryImage',
        'plugin.Products.Product',
        'plugin.Products.ProductBrand',
        'plugin.Products.ProductPrice',
        'plugin.Products.ProductStore',
        'plugin.Webpages.Webpage',
        'plugin.Users.User'
        );
/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Product = ClassRegistry::init('Products.Product');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Product);
		parent::tearDown();
	}

/**
 * testAdd method
 *
 * @return void
 */
	public function testSave() {
        
        $testData = array(
            'Product' => array(
                'is_public' => '1',
                'name' => 'Lorem ipsum',
                'sku' => '',
                'price' => '93.00',
                'summary' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut pretium est sed risus malesuada mollis.',
                )
            );
      
        $this->Product->save($testData);
        $result = $this->Product->findById($this->Product->id);
        $this->assertEqual($result['Product']['id'], $this->Product->id);  // make sure the item was added
        $this->assertTrue(!empty($result['Product']['sku']));  // the sku should be filled automatically when it's empty
	}
    
/**
 * testDelete method
 * 
 */
    public function testDelete() {
        $testData = array(
            'Product' => array(
                'is_public' => '1',
                'name' => 'Lorem ipsum',
                'sku' => '',
                'price' => '93.00',
                'summary' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut pretium est sed risus malesuada mollis.',
                ),
            );
		$this->Product->create();
        $this->Product->save($testData);
		$id = $this->Product->id;
        $result = $this->Product->find('first', array('conditions' => array('Product.id' => $id)));
        $this->assertTrue(!empty($result)); // product was created
       	$this->Product->delete($id);
        $result = $this->Product->find('first', array('conditions' => array('Product.id' => $this->Product->id)));
        $this->assertTrue(empty($result)); // product should be gone
    }
    
    
/**
 * testCleanItemsPrices method
 *
 * @return void
 */
	public function testCleanItemsPrices() {

	}
/**
 * testCleanItemPrice method
 *
 * @return void
 */
	public function testCleanItemPrice() {

	}
/**
 * testPaymentOptions method
 *
 * @return void
 */
	public function testPaymentOptions() {

	}
}
