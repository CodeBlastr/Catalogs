<?php
/**
 * Random Product Element
 *
 * Displays a random product image and information.
 *
 * PHP versions 5
 *
 * Zuha(tm) : Business Management Applications (http://zuha.com)
 * Copyright 2009-2012, Zuha Foundation Inc. (http://zuhafoundation.org)
 *
 * Licensed under GPL v3 License
 * Must retain the above copyright notice and release modifications publicly.
 *
 * @copyright     Copyright 2009-2012, Zuha Foundation Inc. (http://zuha.com)
 * @link          http://zuha.com Zuha™ Project
 * @package       zuha
 * @subpackage    zuha.app.plugins.products.views.elements
 * @since         Zuha(tm) v 0.0.1
 * @license       GPL v3 License (http://www.gnu.org/licenses/gpl.html) and Future Versions
 */
?>
<?php
// this should be at the top of every element created with format __ELEMENT_PLUGIN_ELEMENTNAME_instanceNumber.
// it allows a database driven way of configuring elements, and having multiple instances of that configuration.
	if(!empty($instance) && defined('__ELEMENT_PRODUCTS_RANDOM_'.$instance)) {
		extract(unserialize(constant('__ELEMENT_PRODUCTS_RANDOM_'.$instance)));
	}

// set up the default config vars
	$itemCount = (!empty($itemCount) ? $itemCount : 3);
	$showGalleryThumb = (isset($showGalleryThumb) ? $showGalleryThumb : false);
	$galleryThumbSize = ($showGalleryThumb == 1) ? 'small' : $showGalleryThumb;
	$showItemName = (!empty($showItemName) ? $showItemName : true);
	$showItemDescription = (isset($showItemDescription) ? $showItemDescription : true);
	$showViewLink = (isset($showViewLink) ? $showViewLink : true);
	$viewLinkText = (!empty($viewLinkText) ? $viewLinkText : 'View');
	$before = (!empty($before) ? '<p>'.$before.'</p>' : false);
	$inbetween = (!empty($inbetween) ? '<p>'.$inbetween.'</p>' : false);
	$after = (!empty($after) ? '<p>'.$after.'</p>' : false);
	$productBrandId = (!empty($productBrandId) ? $productBrandId : false);

	#$products = $this->requestAction(array('plugin' => 'products', 'controller' => 'products', 'action' => 'random_product'), array('pass' => array($itemCount, $product_brand_id)));
	$products = $this->requestAction('/products/products/random_product/'.$itemCount.'/'.$productBrandId);
	if (!empty($products)) :
?>
<ul class="elementProductRandom" id="elementProductRandom<?php echo $instance; ?>">
<?php foreach ($products as $product) : ?>
  <li>
    <div class="img"><?php echo $this->element('thumb',
            array('model' => 'Product',
                'foreignKey' => $product['Product']['id'],
                'thumbSize' => $galleryThumbSize,
                'thumbLink' => '/products/products/view/'.$product['Product']['id']
                ), array('plugin' => 'galleries'));  ?></div>
    <div class="txt">
	  <?php echo $before; ?>
      <b><?php echo $this->Html->link($product['Product']['name'] , array('plugin' => 'products', 'controller' => 'products' , 'action'=>'view' , $product["Product"]["id"])); ?></b>
	  <?php echo $inbetween; ?>
      <?php if($showViewLink): ?>
      <div class="more-holder"><?php echo $this->Html->link($viewLinkText , array('plugin' => 'products', 'controller' => 'products' , 'action'=>'view' , $product["Product"]["id"])); ?></div>
      <?php endif; ?>
	  <?php echo $after; ?>
    </div>
    <!-- /txt end -->
  <?php endforeach; ?>
</ul>
<?php endif; ?>
