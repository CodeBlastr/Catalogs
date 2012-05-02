<?php
/**
 * Catalog App Model
 *
 *
 * PHP versions 5.3
 *
 * Zuha(tm) : Business Management Applications (http://zuha.com)
 * Copyright 2009-2010, Zuha Foundation Inc. (http://zuha.org)
 *
 * Licensed under GPL v3 License
 * Must retain the above copyright notice and release modifications publicly.
 *
 * @copyright     Copyright 2009-2010, Zuha Foundation Inc. (http://zuha.com)
 * @link          http://zuha.com Zuha™ Project
 * @package       zuha
 * @subpackage    zuha.app.plugins.catalogs
 * @since         Zuha(tm) v 0.0.1
 * @license       GPL v3 License (http://www.gnu.org/licenses/gpl.html) and Future Versions
 */

class CatalogsAppModel extends AppModel {
	
	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
		
		// automatic upgrade the workflow item events table 4/19/2012
		if (defined('__SYSTEM_ZUHA_DB_VERSION') && __SYSTEM_ZUHA_DB_VERSION < 0.0190) {
			$columns = $this->query('SHOW COLUMNS FROM catalog_items');
			foreach ($columns as $column) {
				if ($column['COLUMNS']['Field'] == 'cost') {
					//its there 
					$alter = false;
					break;
				} else {
					$alter = true;
				}
			}
			if (!empty($alter)) {
				$this->query('ALTER TABLE `catalog_items` ADD `cost` FLOAT NULL AFTER `cart_max`');
			}
		}
	}
}