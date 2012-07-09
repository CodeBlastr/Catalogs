<?php
App::uses('Catalog', 'Catalogs.Model');

/**
 * Catalog Test Case
 *
 */
class CatalogTestCase extends CakeTestCase {
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
		$this->Catalog = ClassRegistry::init('Catalog');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Catalog);

		parent::tearDown();
	}

/**
 * testAdd method
 *
 * @return void
 */
	public function testAdd() {

	}
}
