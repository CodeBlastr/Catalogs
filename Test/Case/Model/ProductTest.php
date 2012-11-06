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
	public $fixtures = array();
/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Product = ClassRegistry::init('Product');
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
	public function testAdd() {

      /**
       *  set some fake data
       */
      $testData = array(
          'Product' => array(
              'parent_id' => '4faace84-703c-47ff-a658-755345a3a949',
              'lft' => '421',
              'rght' => '438',
              'sku' => 'PHF-50',
              'name' => 'Ready To Use Pop-Up Foil ',
              'summary' => 'Pre-cut foil sheet, Silver',
              'description' => 'Loreum ipoeusm... nothing nothing nothing',
              'video_url' => '',
              'start_date' => null,
              'end_date' => null,
              'published' => true,
              'product_brand_id' => '3',
              'store_id' => '2',
              'stock' => '999999',
              'cart_min' => null,
              'cart_max' => null,
              'cost' => '1.533',
              'price' => '500.99',
              'location' => null,
              'deadline' => null,
              'weight' => null,
              'height' => null,
              'width' => null,
              'length' => null,
              'shipping_type' => 'FREESHIPPING',
              'shipping_charge' => null,
              'payment_type' => null,
              'arb_settings' => '',
              'is_virtual' => false,
              'hours_expire' => null,
              'model' => '',
              'foreign_key' => '',
              'owner_id' => null,
              'children' => null,
              'creator_id' => '4',
              'modifier_id' => '1',
              'created' => '2012-05-09 13:07:32',
              'modified' => '2012-05-09 15:06:18'
          ),
          'ProductStore' => array(
              'id' => '2'
          ),
          'Category' => array(
              '4f471dfb-5d34-4503-a4b3-2f9545a3a949',
              '4faad6a3-3ed4-4675-b5c7-2d1245a3a949'
          ),
          'CategoryOption' => array(
              '4faaea30-d5f0-4d7e-a4be-484045a3a949' => '4faaeb8d-b380-48e2-827d-6d5545a3a949'
          ));

      /**
       *  do some tests..
       *
       *  @todo I couldn't get the tests working on my machine.
       *  It seemed to be callin the SQL "add" instead of what it should be doing.
       *  I came to that conclusion by passing NULL instead of $testData.
       *  ^JB
       */

      // This test is mimicking products/products/update >> $this->Product->add($data, $this->Auth->user('id'))
      $ret = $this->Product->add($testData);
      $this->assertTrue($ret);

      // I want to check to ensure that the categorized_options.category_option_id, categorized_options.model, and categorized_options.foreign_key are set
      $addedItem = $this->Product->find('first', array(
              'conditions' => array(
                  'CategorizedOption.foreign_key' => $this->Product->id
              )
          ));

      $this->assertIsA($addedItem, 'array'); /** @todo idk if this is correct **/

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
