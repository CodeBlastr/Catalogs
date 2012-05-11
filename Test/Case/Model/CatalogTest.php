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
	public $fixtures = array('plugin.catalogs.catalog', 'app.alias', 'plugin.catalogs.catalog_item', 'plugin.catalogs.catalog_item_brand', 'plugin.users.user', 'plugin.users.user_role', 'plugin.users.user_wall', 'plugin.contacts.contact', 'app.enumeration', 'plugin.contacts.contact_address', 'plugin.contacts.contact_detail', 'plugin.tasks.task', 'plugin.projects.project', 'plugin.projects.project_issue', 'plugin.timesheets.timesheet_time', 'plugin.timesheets.timesheet', 'app.timesheets_timesheet_time', 'plugin.projects.projects_member', 'plugin.users.user_group', 'plugin.users.users_user_group', 'plugin.users.user_group_wall_post', 'plugin.users.used', 'plugin.messages.message', 'plugin.tags.tag', 'plugin.tags.tagged', 'app.invoice', 'app.invoice_item', 'app.invoice_time', 'app.projects_watcher', 'plugin.wikis.wiki', 'plugin.wikis.wiki_page', 'plugin.wikis.wiki_content', 'plugin.galleries.gallery', 'plugin.galleries.gallery_image', 'plugin.wikis.wiki_content_version', 'app.projects_wiki', 'plugin.categories.category', 'plugin.categories.category_option', 'plugin.categories.categorized_option', 'plugin.categories.categorized', 'app.contacts_contact', 'plugin.users.user_status', 'plugin.users.user_follower', 'plugin.orders.order_payment', 'plugin.orders.order_item', 'plugin.orders.order_shipment', 'plugin.orders.order_transaction', 'plugin.orders.order_coupon', 'app.location', 'plugin.catalogs.catalog_item_price');

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
