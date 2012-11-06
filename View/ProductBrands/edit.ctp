<div class="storeBrands form">
<h2><?php echo __('Add a New Brand');?></h2>
<?php echo $this->Form->create('ProductBrand', array('enctype'=>'multipart/form-data'));?>
	<fieldset>
	<?php
		echo $this->Form->hidden('ProductBrand.id');
		echo $this->Form->input('ProductBrand.store_id', array('label' => 'Which store does this brand belong to?'));
		echo $this->Form->input('ProductBrand.name', array('A brand needs a name.'));
		echo $this->Form->input('GalleryImage.filename', array('type' => 'file', 'label' => 'Upload the brand\'s logo.'));
		echo $this->Form->input('ProductBrand.summary', array('type' => 'richtext', 'label' => 'Quick brand data summary', 'ckeSettings' => array('buttons' => array('Source','Bold','Italic','Underline','FontSize','TextColor','BGColor','-','NumberedList','BulletedList','Blockquote','JustifyLeft','JustifyCenter','JustifyRight','-','Link','Unlink','-', 'Image'))));
		echo $this->Form->input('ProductBrand.description', array('type' => 'richtext', 'label' => 'Promotional copy for this brand?', 'ckeSettings' => array('buttons' => array('Source','Bold','Italic','Underline','FontSize','TextColor','BGColor','-','NumberedList','BulletedList','Blockquote','JustifyLeft','JustifyCenter','JustifyRight','-','Link','Unlink','-', 'Image'))));

		#echo $this->Form->input('address');
	?>
	</fieldset>
<?php echo $this->Form->end('Submit');?>
</div>

<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__('List ProductBrands', true), array('action' => 'index'));?></li>
	</ul>
</div>