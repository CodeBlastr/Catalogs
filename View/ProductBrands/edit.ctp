<div class="storeBrands form">
<h2><?php echo __('Edit Brand');?></h2>
<?php echo $this->Form->create('ProductBrand', array('enctype'=>'multipart/form-data'));?>
	<fieldset>
	<?php
		echo $this->Form->hidden('ProductBrand.id');
		echo $this->Form->input('ProductBrand.name', array('A brand needs a name.'));
		echo $this->Form->input('GalleryImage.filename', array('type' => 'file', 'label' => 'Upload the brand\'s logo.'));
		echo $this->Form->input('ProductBrand.summary', array('type' => 'richtext', 'label' => 'Quick brand data summary', 'ckeSettings' => array('buttons' => array('Source','Bold','Italic','Underline','FontSize','TextColor','BGColor','-','NumberedList','BulletedList','Blockquote','JustifyLeft','JustifyCenter','JustifyRight','-','Link','Unlink','-', 'Image'))));
		echo $this->Form->input('ProductBrand.description', array('type' => 'richtext', 'label' => 'Promotional copy for this brand?', 'ckeSettings' => array('buttons' => array('Source','Bold','Italic','Underline','FontSize','TextColor','BGColor','-','NumberedList','BulletedList','Blockquote','JustifyLeft','JustifyCenter','JustifyRight','-','Link','Unlink','-', 'Image'))));

		#echo $this->Form->input('address');
	?>
	</fieldset>
<?php echo $this->Form->end('Submit');?>
</div>

<?php
// set the contextual menu items
$this->set('context_menu', array('menus' => array(
    array(
		'heading' => 'Products',
		'items' => array(
			$this->Html->link(__('Dashboard'), array('controller' => 'products', 'action' => 'dashboard')),
			)
		),
	array(
		'heading' => 'Product',
		'items' => array(
			$this->Html->link(__d('products', 'List'), array('action' => 'index')),
			$this->Html->link(__d('products', 'Delete'), array('action' => 'delete', $this->request->data['ProductBrand']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->request->data['ProductBrand']['id'])),
			),
		),
	))); ?>