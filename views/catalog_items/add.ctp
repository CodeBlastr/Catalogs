<?php 
/**
 * Catalog Items Admin Add View
 *
 * PHP versions 5
 *
 * Zuha(tm) : Business Management Applications (http://zuha.com)
 * Copyright 2009-2010, Zuha Foundation Inc. (http://zuhafoundation.org)
 *
 * Licensed under GPL v3 License
 * Must retain the above copyright notice and release modifications publicly.
 *
 * @copyright     Copyright 2009-2010, Zuha Foundation Inc. (http://zuha.com)
 * @link          http://zuha.com Zuha� Project
 * @package       zuha
 * @subpackage    zuha.app.plugins.catalogs.views
 * @since         Zuha(tm) v 0.0.1
 * @license       GPL v3 License (http://www.gnu.org/licenses/gpl.html) and Future Versions
 */
 ?>
<div class="catalogItemAdd form">
	<?php 
	echo $form->create('CatalogItem', array('type' => 'file'));
	?>
    <h2><?php __('Add a Catalog Item'); ?></h2>
    <fieldset>
    	<?php			
		echo $form->input('CatalogItem.published', array('default' => 1, 'type' => 'hidden'));
		echo $form->input('CatalogItem.name', array('label' => 'Item display name'));
		echo $form->input('CatalogItem.sku');
		echo $form->input('CatalogItem.catalog_id', array('label' => 'Which catalog should hold this item? ('.$this->Html->link('add', array('controller' => 'catalogs', 'action' => 'add')).' / '.$this->Html->link('edit', array('controller' => 'catalogs', 'action' => 'index')).' catalogs)'));
					
		echo $form->input('CatalogItem.catalog_item_brand_id', array('empty' => '-- Select --', 'label' => 'What is this item\'s brand name? ('.$this->Html->link('add', array('controller' => 'catalog_item_brands', 'action' => 'add')).' / '.$this->Html->link('edit', array('controller' => 'catalog_item_brands', 'action' => 'index')).' brands)'));
		echo $form->input('CatalogItem.price', array('label' => 'What is the retail price?'));
		echo $form->input('CatalogItem.stock_item', array('label' => 'Would you like to track inventory?', 'after' => '<p>Enter your current item count or leave blank for unlimited</p>'));
		echo $form->input('GalleryImage.filename', array('type' => 'file', 'label' => 'Upload your best image for this item.', 'after' => ' <p> This image will be the thumbnail. You can add additional images after save.</p>'));
	    echo $form->input('GalleryImage.dir', array('type' => 'hidden'));
	    echo $form->input('GalleryImage.mimetype', array('type' => 'hidden'));
	    echo $form->input('GalleryImage.filesize', array('type' => 'hidden'));
		echo $form->input('CatalogItem.summary', array('type' => 'text', 'label' => 'Promo or Summary Text', 'after' => '<p>Used to entice people to view more about this item.</p>'));
		echo $form->input('CatalogItem.description', array('type' => 'richtext', 'label' => 'What is the sales copy, or full description for this item?', 'after' => 'This is what people will read in order to decide if they want it.'));
		?>
    </fieldset>
	<fieldset>
 		<legend class="toggleClick"><?php __('Do you offer shipping for this item?');?></legend>
    	<?php 
		$fedexSettings = defined('__ORDERS_FEDEX') ? unserialize(__ORDERS_FEDEX) : null;
		$radioOptions = array();
		if (!empty($fedexSettings)) : foreach($fedexSettings as $k => $val) :
			$radioOptions[$k] = $val ;
			echo $form->input('CatalogItem.weight', array('label' => 'Weight (lbs)'));
			echo $form->input('CatalogItem.height', array('label' => 'Height (8-70 inches)'));
			echo $form->input('CatalogItem.width', array('label' => 'Width (50-119 inches)'));
			echo $form->input('CatalogItem.length', array('label' => 'Length (50-119 inches)'));
		endforeach; endif;
		$radioOptions += array('FIXEDSHIPPING' => 'FIX SHIPPING', 'FREESHIPPING' => 'FREE SHIPPING') ;
		echo $form->radio('CatalogItem.shipping_type', $radioOptions, array('class' => 'shipping_type' , 'default' => ''));
	 	?>
	 	<div id='ShippingPrice'>
	 		<?php echo $form->input('CatalogItem.shipping_charge');?>
		</div>
    </fieldset>
	<fieldset>
 		<legend class="toggleClick"><?php __('Do you want to limit geographic availability of this item?');?></legend>
    	<?php
		echo $form->input('Location.available', array('rows'=>1, 'cols' => 30,'label' => 'Zip Codes Available (comma separated)'));
		echo $form->input('Location.restricted', array('rows'=>1, 'cols' => 30,'label' => 'Zip Codes Restricted (comma separated)'));
		echo $form->hidden('Location.model', array('value' => Inflector::camelize(Inflector::singularize($this->name))));
		?>
    </fieldset>
	<fieldset>
 		<legend class="toggleClick"><?php __('Does this item have a schedule?');?></legend>
    	<?php	
		echo $form->input('CatalogItem.start_date', array('empty' => true));
		echo $form->input('CatalogItem.end_date', array('empty' => true));
				
		if (isset($this->data['CatalogItemPrice'])) :
			foreach($this->data['CatalogItemPrice'] as $index => $val) :
				echo $form->hidden("CatalogItemPrice.{$index}.id", array('value'=>$val['id']));
				echo $form->hidden("CatalogItemPrice.{$index}.price", array('value'=>$val['price']));
				echo $form->hidden("CatalogItemPrice.{$index}.catalog_item_id", array('value'=>$val['catalog_item_id'])); 
				echo $form->hidden("CatalogItemPrice.{$index}.user_role_id", array('value'=>$val['user_role_id']));
				echo $form->hidden("CatalogItemPrice.{$index}.price_type_id", array('value'=>$val['price_type_id']));
			endforeach;
		endif;
		$i = 0;
		if (!empty($this->data['Category'])) { foreach($this->data['Category'] as $value) {
			++$i;
			echo '<div id="divCategory'.$i.'">';
			echo $i . ' '. $categories[$value];
			echo $this->Html->link('Remove' , "javascript:rem('Category{$i}')", array('')); 
			echo $form->hidden('Category.'.$i, array('value' => $value));
			echo '</div>';
		}?>
		<h3>Options</h3>
		<?php 
		if(isset($options)) {
			foreach($options as $key => $opt) {
				echo '<div style ="float:left; width: 200px; clear:none;">';
				echo '<fieldset>';
				echo '<legend>' . $opt['CategoryOption']['name'] . '</legend>';
				$sel = array();
				foreach($opt['children'] as $child) {
					$sel[$child['CategoryOption']['id']] = $child['CategoryOption']['name'];
				}
				if (!empty($sel))
					echo $form->input('CategoryOption.'.$opt['CategoryOption']['id'], 
						array('options'=>$sel, 'multiple'=>'checkbox', 'label'=> false, 'div'=>false,
								'type'=> $opt['CategoryOption']['type'] == 'Attribute Group' ? 'radio' : 'select'));
				echo '</fieldset>';
				echo '</div>';
			}
		} }
	?>
	</fieldset>

	<fieldset>
 		<legend class="toggleClick"><?php __('Does this item need to be categorized?');?></legend>
			<?php
				echo $this->Form->input('Category', array('multiple' => 'checkbox', 'label' => 'Which categories? ('.$this->Html->link('add', array('plugin' => 'categories', 'controller' => 'categories', 'action' => 'tree')).' / '.$this->Html->link('edit', array('plugin' => 'categories', 'controller' => 'categories', 'action' => 'tree')).' categoies)'));	 
			?>
	</fieldset>
	<fieldset>
 		<legend class="toggleClick"><?php __('Is this a recurring billing item?');?></legend>
			<?php
				echo $form->input('CatalogItem.arb_settings', array('rows'=>1, 'cols' => 30 ,'label' => 'Arb Settings (
																			trialOccurrences (No Of Billing Cycles For Trial),
																			totalOccurrences (Total Billing Cycles),
																			interval_length (How Many Months Do You Want In A Billing Cycle),
																			trialAmount (Amount If Any For Trial Period) )'
				));	 
			?>
	</fieldset>

	<?php
    echo $form->end('Submit');
	?>
    
<?php 
// set the contextual menu items
$menu->setValue(array(
	array(
		'heading' => 'Catalog Items',
		'items' => array(
			$this->Html->link(__('List', true), array('controller' => 'catalog_items', 'action' => 'index')),
			$this->Html->link(__('Add', true), array('controller' => 'catalog_items', 'action' => 'add')),
			)
		),
	)
); 
?>

<script type="text/javascript">

$('#addCat').click(function(e){
	e.preventDefault();
	$('#anotherCategory').show();
});

$('#priceID').click(function(e){
	e.preventDefault();
	action = '<?php echo $this->Html->url(array('plugin'=>'catalogs',
					'controller'=>'catalog_item_prices', 'action'=>'add', 'admin'=>true))?>';
	$("#CatalogItemAddForm").attr("action" , action);
	$("#CatalogItemAddForm").submit(); 
});
function rem($id) {
	$('#div'+$id).remove();
}

$(document).ready( function(){
	if($('input.shipping_type:checked').val() == 'FIXEDSHIPPING') {
		$('#ShippingPrice').show();
	} else {
		$('#ShippingPrice').hide();
	}	
});

var shipTypeValue = null;
$('input.shipping_type').click(function(e){
	shipTypeValue = ($('input.shipping_type:checked').val());
	if(shipTypeValue == 'FIXEDSHIPPING') {
		$('#ShippingPrice').show();
	} else {
		$('#ShippingPrice').hide();
	}
});

</script>


</div>