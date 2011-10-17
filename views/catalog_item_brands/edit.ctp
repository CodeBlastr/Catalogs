<div class="catalogBrands form">
<h2><?php __('Add a New Brand');?></h2>
<?php echo $form->create('CatalogItemBrand', array('enctype'=>'multipart/form-data'));?>
	<fieldset>
	<?php
		echo $form->hidden('CatalogItemBrand.id');
		echo $form->input('CatalogItemBrand.catalog_id', array('label' => 'Which catalog does this brand belong to?'));
		echo $form->input('CatalogItemBrand.name', array('A brand needs a name.'));
		echo $form->input('GalleryImage.filename', array('type' => 'file', 'label' => 'Upload the brand\'s logo.'));
		echo $form->input('CatalogItemBrand.description', array('type' => 'richtext', 'label' => 'Promotional copy for this brand?', 'ckeSettings' => array('buttons' => array('Bold','Italic','Underline','FontSize','TextColor','BGColor','-','NumberedList','BulletedList','Blockquote','JustifyLeft','JustifyCenter','JustifyRight','-','Link','Unlink','-', 'Image'))));
		
		#echo $form->input('address');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>

<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__('List CatalogItemBrands', true), array('action' => 'index'));?></li>
	</ul>
</div>