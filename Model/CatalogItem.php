<?php
App::uses('CatalogsAppModel', 'Catalogs.Model');
/**
 * Catalog Item Model
 *
 *
 * PHP versions 5
 *
 * Zuha(tm) : Business Management Applications (http://zuha.com)
 * Copyright 2009-2012, Zuha Foundation Inc. (http://zuha.org)
 *
 * Licensed under GPL v3 License
 * Must retain the above copyright notice and release modifications publicly.
 *
 * @copyright     Copyright 2009-2012, Zuha Foundation Inc. (http://zuha.com)
 * @link          http://zuha.com Zuha� Project
 * @package       zuha
 * @subpackage    zuha.app.plugins.catalogs.models
 * @since         Zuha(tm) v 0.0.1
 * @license       GPL v3 License (http://www.gnu.org/licenses/gpl.html) and Future Versions
 */
class CatalogItem extends CatalogsAppModel {

	public $name = 'CatalogItem';

	public $validate = array(
		'name' => array('notempty'),
	);

	public $actsAs = array(
		'Tree' => array('parent' => 'parent_id'),
	);

	public $order = '';

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	public $hasMany = array(
		'OrderItem' => array(
			'className' => 'Orders.OrderItem',
			'foreignKey' => 'catalog_item_id',
			'dependent' => false,
		),
		'CatalogItemPrice' => array(
			'className' => 'Catalogs.CatalogItemPrice',
			'foreignKey' => 'catalog_item_id',
			'dependent' => true,
			'order' => 'CatalogItemPrice.user_role_id asc'
		),
		'CatalogItemChildren' => array(
			'className' => 'Catalogs.CatalogItem',
			'foreignKey' => 'parent_id',
			'dependent' => true,
		),
	);

	public $hasOne = array(
		'Gallery' => array(
			'className' => 'Galleries.Gallery',
			'foreignKey' => 'foreign_key',
			'dependent' => false,
			'conditions' => array('Gallery.model' => 'CatalogItem'),
			'fields' => '',
			'order' => ''
		)
	);

	//catalog items association.
	public $belongsTo = array(
		'Catalog'=>array(
			'className' => 'Catalogs.Catalog',
			'foreignKey' => 'catalog_id',
		),
		'CatalogItemParent'=>array(
			'className' => 'Catalogs.CatalogItem',
			'foreignKey' => 'parent_id',
			'counterCache' => 'children',
			'counterScope' => array('CatalogItem.parent_id IS NOT NULL'),
		),
		'CatalogItemBrand' => array(
			'className' => 'Catalogs.CatalogItemBrand',
			'foreignKey' => 'catalog_item_brand_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Owner' => array(
			'className' => 'Users.User',
			'foreignKey' => 'owner_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);

    public $hasAndBelongsToMany = array(
        'Category' => array(
            'className' => 'Categories.Category',
       		'joinTable' => 'categorized',
            'foreignKey' => 'foreign_key',
            'associationForeignKey' => 'category_id',
    		'conditions' => 'Categorized.model = "CatalogItem"',
    		// 'unique' => true,
        ),
        'CategoryOption' => array(
            'className' => 'Categories.CategoryOption',
       		'joinTable' => 'categorized_options',
            'foreignKey' => 'foreign_key',
            'associationForeignKey' => 'category_option_id',
    		//'unique' => true,
        ),
    );

	public function __construct($id = null, $table = null, $ds = null) {
		// load associated plugins
		if (in_array('Locations', CakePlugin::loaded())) {
			$this->hasOne['Location'] = array(
				'className' => 'Locations.Location',
				'foreignKey' => 'foreign_key',
				'dependent' => false,
				'conditions' => array('Location.model' => 'CatalogItem'),
				);
		}
		
		parent::__construct($id, $table, $ds);
		
		$this->categorizedParams = array('conditions' => array($this->alias.'.parent_id' => null));
		$this->order = array($this->alias . '.' . 'price');
	}

	public function beforeFind($queryData) {
		$this->filterPrice = true;
		if (defined('__CATALOGS_ENABLE_LOCATIONS')) {
			// restricted locations to be removed
			$restricted = $this->Location->get_restricted_keys($this->name);
			if ($restricted && (is_array($queryData['conditions']) || empty($queryData['conditions']))) {
				$queryData['conditions'][] = array("{$this->alias}.id not in ({$restricted})");
			}
			// if no item is available in this zip code dont show anything.
			$available = $this->Location->get_available_keys($this->name);
			if ($available && (is_array($queryData['conditions']) || empty($queryData['conditions']))) {
				$queryData['conditions'][] = array("{$this->alias}.id  in ({$available})");
			}
		}
		// always limit catalog items by the user role if the price matrix is used
		App::import('Model', 'CakeSession');
        $this->Session = new CakeSession();
		$userRoleId = $this->Session->read('Auth.User.user_role_id');
		$queryData['contain']['CatalogItemPrice']['conditions']['CatalogItemPrice.user_role_id'] = $userRoleId;

		// stop filtering the price if we use fields and price isn't included
		$this->filterPrice = !empty($queryData['fields']) && is_array($queryData['fields']) && (array_search('price', $queryData['fields']) === false && array_search('CatalogItem.price', $queryData['fields']) === false) ? false : true;

		return $queryData;
	}

	public function afterFind($results, $primary) {
		// only play with prices if the find is not list type (which doesn't need prices)
		if (!empty($this->filterPrice)) {
			// this is for the find "all" type where the data format is $results[0]['CatalogItem']['id'];
			if (isset($results[0]['CatalogItem']) && !empty($results[0]['CatalogItem'])) {
				$results = $this->cleanItemsPrices($results);
			}

			// this is for single catalog items being returned
			if (isset($results['CatalogItem']['id']) && !empty($results['CatalogItem']['id'])) {
				$results = $this->cleanItemPrice($results);
			}
		}
		
		$i = 0;
		foreach ($results as $result) {
			$i = $i + 1;
			if(!empty($result['CatalogItem']['arb_settings'])) {
				// set arb back to input values
				$arbSettingsArray = unserialize($result['CatalogItem']['arb_settings']);
				$arbSettingsString = '';
				foreach ($arbSettingsArray as $key => $value ){
					$arbSettingsString .= "$key = $value\n";
				}
				$results[$i]['CatalogItem']['arb_settings'] = $arbSettingsString ;
			}
		}
		
		return $results;
	}

/**
 * Handles the adding of catalog items and any additional functions that need to run with it.
 *
 * @todo		We need to change this from a random sku generator to something that checks for existence of the sku already, and throws an error back to the controller if it does.
 * @todo		Make it a catalogs plugin setting for whether skus will be randomly generated or not.
 * @todo 		Not sure why we have deleteAll there, when I believe anytime you save a HABTM model it will delete all automatically.  If its not working without that, then there is a problem with the relationships.
 * @todo		The manual items that come after saveAll should be verified and roll back the item if its not updated correctly.
 * @todo		This function should use the throw exception syntax, and the controller should catch.
 */
	public function add($data) {
		$data = $this->_cleanAddData($data);
		$ret = false;

		// remove some information for saveAll, because we need to deal with it manually
		$itemData = array('CatalogItem' => $data['CatalogItem']);
		
		if (isset($data['CatalogItemPrice'])) {
			# why is this here?  save HABTM does this for you ... RK - 7/18/2011
			$this->CatalogItemPrice->deleteAll(array('catalog_item_id' => $itemData['CatalogItem']['id']));
			#$itemData['CatalogItemPrice'] = $data['CatalogItemPrice'];
		}
		if ($this->save($itemData)) {
			$data['CatalogItem']['id'] = $this->id ;
			$data['Gallery']['model'] = 'CatalogItem';
			$data['Gallery']['foreign_key'] = $this->id;
			$imageSaved = false;

			if (isset($data['GalleryImage'])){
				if ($data['GalleryImage']['filename']['error'] == 0 && $this->Gallery->GalleryImage->add($data, 'filename')) {
					$imageSaved = true;
				}
			}
			if (isset($data['CatalogItem']['id']) || $imageSaved) {
				# this is how the categories data should look when coming in.
				if (isset($data['Category']['Category'][0])) :
					$categorized = array('CatalogItem' => array('id' => array($this->id)));
					foreach ($data['Category']['Category'] as $catId) :
						$categorized['Category']['id'][] = $catId;
					endforeach;
					$this->Category->categorized($categorized, 'CatalogItem');
				endif;

				if(isset($data['CategoryOption'])) {
					$this->CategoryOption->categorized_option($data, 'CatalogItem');
				}
				$this->Location->add($this->id, $this->name, $data);
				$ret = true;
			} else {
				$this->delete($this->id);
			}
		} else {
			$errors = $this->validationErrors;
			debug($errors);
			throw new Exception(__d('catalogs', 'Error: ...', true));
		}
		return $ret;
	}
	
/**
 * Cleans data for adding
 * 
 * @access protected
 * @param array
 * @return array
 */ 
 	protected function _cleanAddData($data) {
		if (!empty($data['CatalogItem']['arb_settings'])) {
			$data['CatalogItem']['arb_settings'] = serialize(parse_ini_string($this->request->data['CatalogItem']['arb_settings']));
		}
			
		if(!empty($data['CatalogItem']['payment_type'])) {
			$data['CatalogItem']['payment_type'] = implode(',', $this->request->data['CatalogItem']['payment_type']);
		}
		
		if (empty($data['CatalogItem']['sku'])) {
			$data['CatalogItem']['sku'] = rand(10000, 99000); // generate random sku if none exists
		}
		
		return $data;
	}

/**
 * Cleans catalogItems
 *
 * If the advanced price matrix exists, then we set the price using that.
 * If no price matrix exists we just use the default price
 * If price matrix is there, but empty (because the userRoleId weeded it out in the controller) we remove the item.
 *
 * @param {array} 		Typical structured data array
 */
	public function cleanItemsPrices($catalogItems) {
		$i = 0;
		# get the price for the logged in user
		foreach ($catalogItems as $catalogItem) {
			# this is to check for single CI.
			if (isset($catalogItem['CatalogItem']['id']) && !empty($catalogItem['CatalogItem']['id'])) :
				unset($catalogItemPriceCount);
				# count the prices to see if the price matrix was used at all
				$catalogItemPriceCount = $this->CatalogItemPrice->find('count', array('conditions' => array(
					'CatalogItemPrice.catalog_item_id' => $catalogItem['CatalogItem']['id'],
					)));
				# remove the default price if matrix was used
				if ($catalogItemPriceCount > 0) {
					unset($catalogItems[$i]['CatalogItem']['price']);
				}
				$catalogItems[$i] = $this->cleanItemPrice($catalogItem);

				# remove the product all together if the price matrix was used, and price is 0 for this user's role
				if (empty($catalogItems[$i]['CatalogItem']['price'])) {
					unset($catalogItems[$i]);
				}
				$i++;
			endif;
		}
		return $catalogItems;
	}

/**
 * Cleans a single catalogItems
 *
 * If the advanced price matrix exists, then we set the price using that, other wise leave the default price intact.
 *
 * @param {array} 		Typical structured data array
 * @todo				This price with Zuha::enum() thing is not very reliable, as the names are hard coded.  Haven't thought of a good way around it quite yet, but no one is using multiple or sales prices so removing giving it an easy default for now.  But if we use more prices in the matrix than we need to, its going to cause the wrong prices to be spit out.
 */
	public function cleanItemPrice($catalogItem) {
		if (!empty($catalogItem['CatalogItemPrice'][0])) {
			foreach ($catalogItem['CatalogItemPrice'] as $price) {
				# set the price in the original catalogItems to user role price
				$catalogItem['CatalogItem']['price'] = ZuhaInflector::pricify($price['price']);
			}
		}

		if (!empty($catalogItem['CatalogItem']['price'])) {
			$catalogItem['CatalogItem']['price'] = ZuhaInflector::pricify($catalogItem['CatalogItem']['price']);
		}

		unset($catalogItem['CatalogItemPrice']); // its not needed now
		return $catalogItem;
	}
	
/**
 * Payment Options 
 * 
 * @access public
 * @param void
 * @return string
 */
	public function paymentOptions() {
		if(defined('__ORDERS_ENABLE_SINGLE_PAYMENT_TYPE') && defined('__ORDERS_ENABLE_PAYMENT_OPTIONS')) {
			return unserialize(__ORDERS_ENABLE_PAYMENT_OPTIONS); 
		} else {
			return null;
		}
	}

}