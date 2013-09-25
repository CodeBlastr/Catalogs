<?php
// Buyable Test
App::uses('PurchasableBehavior', 'Products.Model/Behavior');


if (!class_exists('ProductArticle')) {
	class ProductArticle extends CakeTestModel {
	/**
	 *
	 */
		public $callbackData = array();

	/**
	 *
	 */
		public $actsAs = array(
			'Products.Purchasable' => array(
				'foreignKey' => 'id' // used for habtm saves, where this might be something like category_id
				)
			);
	/**
	 *
	 */
		public $useTable = 'product_articles';

	/**
	 *
	 */
		public $name = 'Article';
	/**
	 *
	 */
		public $alias = 'Article';
	}
}


if (!class_exists('MockSession')) {
	class MockSession {
	/**
	 * read
	 */
		public function read() {
			return array('Auth' => array(
				'User' => array(
					'id' => 68,
					'username' => 'donovan',
				)
			));
		}
	}
}


/**
 * BuyableBehavior Test Case
 *
 */
class BuyableBehaviorTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.Products.Product',
		'plugin.Products.ProductPrice',
		'plugin.Products.ProductArticle',
		
		'plugin.Transactions.TransactionItem',
		
		'app.Meta'
		);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->Purchasable = new PurchasableBehavior();
		$this->Article = Classregistry::init('Products.ProductArticle');
		$this->Product = Classregistry::init('Products.Product');
		$this->TransactionItem = Classregistry::init('Transactions.TransactionItem');
		
		if (!class_exists('CakeSession')) {
			App::uses('CakeSession', 'Model/Datasource');
			CakeSession::write(MockSession::read());
		} 
	}

/**
 * tearDown method
 *
 * @return void
 */
    public function tearDown() {
		unset($this->Article);
		unset($this->Product);
		unset($this->TransactionItem);
		ClassRegistry::flush();

		parent::tearDown();
	}
	

/**
 * Test behavior instance
 *
 * @return void
 */
	public function testBehaviorInstance() {
		$this->assertTrue(is_a($this->Article->Behaviors->Purchasable, 'PurchasableBehavior'));
	}
/**
 * Test saving 
 * 
 * Imagine that we're buying the ability to edit an article once.
 */ 
	public function testSaving() {
		$article = $this->Article->find('first');
		$transactionItem = array(
			'TransactionItem' => array(
				'name' => 'Article Product',
				'transaction_id' => 'a043572d-9040-43c9-85b1-22d400000002', // doesn't exist
				'status' => 'paid',
				'quantity' => '1',
				'model' => 'Article',
				'foreign_key' => $article['Article']['id'],
				'price' => 10,
				'customer_id' => CakeSession::read('Auth.User.id'), 
			)
		);
		// insert a fake transaction item as if it was purchased
		if ($this->TransactionItem->save($transactionItem, array('callbacks' => false))) {
			$transactionId = $this->TransactionItem->id;
			$data = array(
				'Product' => array(
					'name' => 'Article Product',
					'model' => 'Article', 
					'foreign_key' => $article['Article']['id'],
					'price' => 10
				)
			);
			// insert a fake product and this is how we know it
			if ($this->Product->save($data)) {
				// now we need to resave the article in order to test that the transaction item is changed to used
				$article = array(
					'Article' => array(
						'id' => $article['Article']['id'],
						'title' => 'New Title'
						)
					);
				if ($this->Article->save($article)) {
					$transactionItem = $this->TransactionItem->read(null, $transactionId);
				}
			}
		}
		$this->assertEqual($transactionItem['TransactionItem']['status'], 'used');
	}

/**
 * Test finding 
 * 
 * You should be able to make something purchasable, by simply creating a product.
 * Once you create the product we automatically return the product with the records
 * if it is Purchasable.
 * 
 * Imagine that we're buying the ability to edit an article once. (tested in testSaving)
 */ 
	public function testFinding() {
		$article = $this->Article->find('first');
		$transactionItem = array(
			'TransactionItem' => array(
				'name' => 'Article Product',
				'transaction_id' => 'a043572d-9040-43c9-85b1-22d400000002', // doesn't exist
				'status' => 'paid',
				'quantity' => '1',
				'model' => 'Article',
				'foreign_key' => $article['Article']['id'],
				'price' => 10,
				'customer_id' => CakeSession::read('Auth.User.id'), 
			)
		);
		// insert a fake transaction item as if it was purchased
		if ($this->TransactionItem->save($transactionItem, array('callbacks' => false))) {
			$data = array(
				'Product' => array(
					'name' => 'Article Product',
					'model' => 'Article', 
					'foreign_key' => $article['Article']['id'],
					'price' => 10
				)
			);
			// insert a fake product and this is how we know it
			if ($this->Product->save($data)) {
				$results = $this->Article->find('all');
				$result[0] = Set::extract('/Product[foreign_key='.$article['Article']['id'].']', $results);
				$result[1] = Set::extract('/Article[id='.$article['Article']['id'].']', $results);
				$result[2] = Set::extract('/TransactionItem', $results);
			}
		}
		$this->assertTrue(!empty($result[0][0]['Product']) && !empty($result[1][0]['Article']) && !empty($result[2][0]['TransactionItem']));
	}

}
