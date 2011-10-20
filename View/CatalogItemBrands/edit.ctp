<div class="catalogBrands form">
<h2><?php echo __('Add a New Brand');?></h2>
<?php echo $this->Form->create('CatalogItemBrand', array('enctype'=>'multipart/form-data'));?>
	<fieldset>
	<?php
		echo $this->Form->hidden('CatalogItemBrand.id');
		echo $this->Form->input('CatalogItemBrand.catalog_id', array('label' => 'Which catalog does this brand belong to?'));
		echo $this->Form->input('CatalogItemBrand.name', array('A brand needs a name.'));
		echo $this->Form->input('GalleryImage.filename', array('type' => 'file', 'label' => 'Upload the brand\'s logo.'));
		echo $this->Form->input('CatalogItemBrand.description', array('type' => 'richtext', 'label' => 'Promotional copy for this brand?', 'ckeSettings' => array('buttons' => array('Bold','Italic','Underline','FontSize','TextColor','BGColor','-','NumberedList','BulletedList','Blockquote','JustifyLeft','JustifyCenter','JustifyRight','-','Link','Unlink','-', 'Image'))));
		
		#echo $this->Form->input('address');
	?>
	</fieldset>
<?php echo $this->Form->end('Submit');?>
</div>

<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__('List CatalogItemBrands', true), array('action' => 'index'));?></li>
	</ul>
</div>