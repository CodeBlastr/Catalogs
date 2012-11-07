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
        'app.Condition',
        'plugin.Contacts.Contact',
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
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut pretium est sed risus malesuada mollis. Integer quis consectetur purus. Donec sollicitudin metus et nunc bibendum et malesuada ligula elementum. Nulla leo nisi, imperdiet vitae aliquam eleifend, accumsan eu justo. ',
                'product_brand_id' => '',
                'stock' => '',
                'cost' => '',
                'cart_min' => '',
                'cart_max' => '',
                'shipping_type' => '',
                'shipping_charge' => ''
                )
            );

        $this->Product->save($testData);
        $result = $this->Product->find();
        $this->assertEqual($result['Product']['id'], $this->Product->id);  // make sure the item was added
        $this->assertTrue(!empty($result['Product']['sku']));  // the sku should be filled automatically when it's empty
        
//        $testData = array(
//            'Product' => array(
//                'is_public' => '1',
//                'name' => 'Lorem ipsum',
//                'sku' => '',
//                'price' => '93.00',
//                'summary' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
//                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut pretium est sed risus malesuada mollis. Integer quis consectetur purus. Donec sollicitudin metus et nunc bibendum et malesuada ligula elementum. Nulla leo nisi, imperdiet vitae aliquam eleifend, accumsan eu justo. ',
//                'product_brand_id' => '',
//                'stock' => '',
//                'cost' => '',
//                'cart_min' => '',
//                'cart_max' => '',
//                'shipping_type' => '',
//                'shipping_charge' => ''
//                ),
//            'GalleryImage' => array(
//                'dir' => '',
//                'mimetype' => '',
//                'filesize' => '',
//                'filename' => array(
//                    'name' => 'thumb5.jpg',
//                    'type' => 'image/jpeg',
//                    'tmp_name' => '/Applications/XAMPP/xamppfiles/temp/phpaNwZO7',
//                    'error' => (int) 0,
//                    'size' => (int) 6628
//                )
//            )
//        );
//        debug($this->Product->save($testData));
//        debug($this->Product->Gallery->find('all')); // can't test file uploads apparently
//        break;
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
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut pretium est sed risus malesuada mollis. Integer quis consectetur purus. Donec sollicitudin metus et nunc bibendum et malesuada ligula elementum. Nulla leo nisi, imperdiet vitae aliquam eleifend, accumsan eu justo. ',
                'product_brand_id' => '',
                'stock' => '',
                'cost' => '',
                'cart_min' => '',
                'cart_max' => '',
                'shipping_type' => '',
                'shipping_charge' => ''
                ),
            );
        $this->Product->save($testData);
        $result = $this->Product->find('first', array('conditions' => array('Product.id' => $this->Product->id)));
        
        $this->assertTrue(!empty($result)); // product was created
        
        $this->Product->Gallery->save(array(
           'Gallery' => array(
               'id' => '509ad808-262c-4b2c-8238-b89f124e0d46',
               'model' => 'Product',
               'foreign_key' => $this->Product->id
               )
            ));
        $result = $this->Product->Gallery->find('first', array('conditions' => array('Gallery.id' => '509ad808-262c-4b2c-8238-b89f124e0d46')));
        $this->assertTrue(!empty($result)); // gallery was created
        
        $this->Product->delete($this->Product->id);
        
        $result = $this->Product->find('first', array('conditions' => array('Product.id' => $this->Product->id)));
        $this->assertEqual($result, false); // product should be gone
        
        $result = $this->Product->Gallery->find('first', array('conditions' => array('Gallery.id' => '509ad808-262c-4b2c-8238-b89f124e0d46')));
        $this->assertEqual($result, false); // gallery should be deleted too
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
