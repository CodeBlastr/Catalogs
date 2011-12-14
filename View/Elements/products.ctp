<div class="catalogItems" id="elementProducts">
  <h2><?php echo !empty($elementTitle) ? $elementTitle : 'Products';?></h2>
  <div class="indexContainer">
	<div class="indexRow" id="headingRow">
	    <div class="indexCell columnHeading"></div>
    </div>
<?php
$i = 0;
foreach ($catalogItems as $catalogItem):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
    <div class="indexRow">
		<div class="indexCell galleryThumb" id="galleryThumb<?php echo $catalogItem['CatalogItem']['id']; ?>">
			<?php echo $this->Element('thumb', array('model' => 'CatalogItem', 'foreignKey' => $catalogItem['CatalogItem']['id'], 'thumbSize' => 'small', 'thumbLink' => '/catalogs/catalog_items/view/'.$catalogItem['CatalogItem']['id']), array('plugin' => 'galleries'));  ?>
        </div>

    	<div class="indexCell catalogItemName" id="catalogItemName<?php echo $catalogItem["CatalogItem"]["id"]; ?>">
			<?php echo $this->Html->link($catalogItem['CatalogItem']['name'] , array('controller' => 'catalog_items' , 'action'=>'view' , $catalogItem["CatalogItem"]["id"])); ?>
       	</div>
		
        <?php if (!empty($catalogItem['CatalogItemBrand'])) { ?>
    	<div class="indexCell catalogItemBrand" id="catalogItemBrand<?php echo $catalogItem["CatalogItem"]["id"]; ?>">
			<?php echo $this->Html->link($catalogItem['CatalogItemBrand']['name'] , array('controller' => 'catalog_item_brands' , 'action'=>'view' , $catalogItem["CatalogItemBrand"]["id"])); ?>
       	</div>
        <?php } ?>
        
	    <div class="indexCell catalogItemDescription" id="catalogItemDescription<?php echo $catalogItem["CatalogItem"]["id"]; ?>">
        	<?php echo $this->Text->truncate(strip_tags($catalogItem['CatalogItem']['summary']), 30, array('ending' => '...', 'html' => true)); ?>
        </div>
        
	    <div class="indexCell catalogItemPrice" id="catalogItemPrice<?php echo $catalogItem["CatalogItem"]["id"]; ?>">
    		<?php echo __('$'); ?><?php echo (!empty($catalogItem['CatalogItemPrice'][0]['price']) ? $catalogItem['CatalogItemPrice'][0]['price'] : $catalogItem['CatalogItem']['price']); ?>
        </div>
        
	    <div class="indexCell catalogItemAction" id="catalogItemAction<?php echo $catalogItem["CatalogItem"]["id"]; ?>">
			<?php echo $this->Html->link(__($this->Html->tag('span', 'view'), true), array('plugin' => 'catalogs', 'controller' => 'catalog_items', 'action' => 'view', $catalogItem['CatalogItem']['id']), array('escape' => false, 'class' => 'button')); ?>
			<?php echo $this->Html->link(__($this->Html->tag('span', 'edit'), true), array('plugin' => 'catalogs', 'controller' => 'catalog_items', 'action' => 'edit', $catalogItem['CatalogItem']['id']), array('escape' => false, 'class' => 'button', 'checkPermissions' => true)); ?>
			<?php echo $this->Html->link(__($this->Html->tag('span', 'delete'), true), array('plugin' => 'catalogs', 'controller' => 'catalog_items', 'action' => 'delete', $catalogItem['CatalogItem']['id']), array('escape' => false, 'class' => 'button', 'checkPermissions' => true), sprintf(__('Are you sure you want to delete %s?', true), $catalogItem['CatalogItem']['name'])); ?>
        </div>
  </div>
<?php endforeach; ?>
</div>
<?php echo $this->element('paging');?>
</div>