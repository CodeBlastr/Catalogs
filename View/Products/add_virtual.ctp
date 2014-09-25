<?php
/**
 * Products Admin Add View
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
 * @link          http://zuha.com Zuha� Project
 * @package       zuha
 * @subpackage    zuha.app.plugins.products.views
 * @since         Zuha(tm) v 0.0.1
 * @license       GPL v3 License (http://www.gnu.org/licenses/gpl.html) and Future Versions
 */
 ?>
<div class="productAdd form">
	<?php
	echo $this->Form->create('Product', array('type' => 'file'));
	?>
    <h2><?php echo __('Purchase Access to a Page'); ?></h2>
    <fieldset>
    	<?php echo $this->Form->input('Product.is_public', array('default' => 1, 'type' => 'hidden')); ?>
    	<?php echo $this->Form->input('Product.name', array('label' => 'Purchase display name')); ?>
    	<?php echo $this->Form->input('Product.price', array('label' => 'Price')); ?>
		<?php echo $this->Form->input('Product.cart_min', array('label' => 'Minimun Cart Quantity?', 'after' => '<p>Enter the minimum cart quantity or leave blank for 1</p>')); ?>
		<?php echo $this->Form->input('Product.cart_max', array('label' => 'Maximum Cart Quantity?', 'after' => '<p>Enter the max cart quantity or leave blank for unlimited</p>')); ?>
        <?php echo CakePlugin::loaded('Media') ? $this->Element('Media.selector', array('multiple' => true)) : null; ?>
		<?php echo $this->Form->input('Product.summary', array('type' => 'text', 'label' => 'Promo or Summary Text', 'after' => '<p>Used to entice people to view more about this item.</p>')); ?>
		<?php echo $this->Form->input('Product.description', array('type' => 'richtext', 'label' => 'What is the sales copy for this item?')); ?>
    </fieldset>
	
	<?php
		if(!empty($paymentOptions)) { ?>
		<fieldset>
			<legend class="toggleClick"><?php echo __('Select Payment Types For The Item.');?></legend>
			<?php
				echo $this->Form->input('Product.payment_type', array('options' => $paymentOptions, 'multiple' => 'checkbox'));
			?>
		</fieldset>
	<?php
		} ?>
	<?php echo $this->Form->input('Product.hours_expire', array('after' => 'Number of hours that this item will be available to the customer after purchase'));	?>


 <div class="webpages form">
	<?php echo $this->Form->create('Webpage');?>
	<h2><?php echo __('Webpage Builder');?></h2>
	<fieldset>
    <?php echo $this->Form->input('Alias.name', array('label' => 'SEO Url (unique)')); ?>
	<?php echo $this->Form->input('Webpage.type', array('value' => 'content', 'type' => 'hidden')); 	?>
    <?php echo $this->Form->input('Webpage.name'); ?>
	<?php echo $this->Form->input('Webpage.content', array('type' => 'richtext'));	?>
	</fieldset>
	<?php debug('This will not work, we have two forms going here, and need to handle the saving of webpages in the _addVirtual method'); exit; ?>
	<?php echo $this->Form->end('Submit'); ?>
</div>

<?php
// set the contextual menu items
$this->set('context_menu', array('menus' => array(
	array(
		'heading' => 'Products',
		'items' => array(
			$this->Html->link(__('List'), array('controller' => 'products', 'action' => 'index')),
			$this->Html->link(__('Add'), array('controller' => 'products', 'action' => 'add')),
			)
		),
 	array(
		'heading' => 'Webpages',
		'items' => array(
			 $this->Html->link(__('List'), array('action' => 'index')),
			 )
		)
	)));
?>

<script type="text/javascript">

$('#addCat').click(function(e){
	e.preventDefault();
	$('#anotherCategory').show();
});

$('#priceID').click(function(e){
	e.preventDefault();
	action = '<?php echo $this->Html->url(array('plugin' => 'products',
					'controller'=>'product_prices', 'action'=>'add', 'admin'=>true))?>';
	$("#ProductAddForm").attr("action" , action);
	$("#ProductAddForm").submit();
});
function rem($id) {
	$('#div'+$id).remove();
}

//
// begin Webpages::add()
//
$(function() {

	var webpageType = $("#WebpageType").val();
	$("#WebpageIsDefault").parent().parent().hide();
	if (webpageType == 'template' || webpageType == 'element') {
		$("#RecordLevelAccessUserRole").parent().parent().hide();
 		$("#AliasName").parent().parent().hide();
	}
	if (webpageType == 'template') {
		$("#WebpageIsDefault").parent().parent().show();
	}
	$("#WebpageType").change(function() {
		var webpageType = $("#WebpageType").val();
		if (webpageType == 'template' || webpageType == 'element') {
			  $("#RecordLevelAccessUserRole").parent().parent().hide();
			  $("#AliasName").parent().parent().hide();
		} else {
			  $("#WebpageIsDefault").parent().parent().hide();
			  $("#RecordLevelAccessUserRole").parent().parent().show();
			  $("#AliasName").parent().parent().show();
		}
		if (webpageType == 'template') {
			$("#WebpageIsDefault").parent().parent().show();
		}
		if (webpageType == 'element') {
			$("#WebpageIsDefault").parent().parent().hide();
		}
	});


	if ($("#WebpageIsDefault").is(":checked")) {
		$("#WebpageTemplateUrls").parent().hide();
	}

	$("#WebpageIsDefault").change(function() {
		if ($(this).is(":checked")) {
			$("#WebpageTemplateUrls").parent().hide();
		} else {
			$("#WebpageTemplateUrls").parent().show();
		}
	});
});
</script>


</div>