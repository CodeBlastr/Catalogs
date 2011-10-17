<?php
	$index = 0;
	// count check is for making sure that there are other entries as well. 
	if($this->data && count($this->data) > 1) {
		echo $form->hidden('CatalogItem.id', array('value'=>$this->data['CatalogItem']['id']));
		echo $form->hidden('CatalogItem.sku',array('value'=>$this->data['CatalogItem']['sku']));
		echo $form->hidden('CatalogItem.user_role_id',array('value'=>$this->data['CatalogItem']['user_role_id']));
		
		// might need to update this default thing later to an actual default group
		echo $form->hidden('CatalogItem.catalog_item_brand_id',array('value'=>$this->data['CatalogItem']['catalog_item_brand_id']));
		echo $form->hidden('CatalogItem.name',array('value'=>$this->data['CatalogItem']['name']));
		// echo $form->hidden('GalleryImage.filename', array('type' => 'file'));
	    echo $form->input('GalleryImage.dir', array('type' => 'hidden'));
	    echo $form->input('GalleryImage.mimetype', array('type' => 'hidden'));
	    echo $form->input('GalleryImage.filesize', array('type' => 'hidden'));
		
		echo $form->hidden('CatalogItem.description',array('value'=>$this->data['CatalogItem']['description']));
		echo $form->hidden('CatalogItem.published',array('value'=>$this->data['CatalogItem']['published']));
		echo $form->hidden('CatalogItem.catalog_id',array('value'=>$this->data['CatalogItem']['catalog_id']));
		
		foreach($this->data['Category'] as $val) {
			echo $form->hidden('Category.'.$index++, array('value'=>$val));
		}
		if(!empty($this->data['CategoryOption'])) {
			foreach($this->data['CategoryOption'] as $key => $val) {
				echo $form->hidden('CategoryOption.'.$key, array('value'=>$val));
				if (is_array($val)) {
					foreach($val as $pos => $opt) {
						echo $form->hidden('CategoryOption.'.$key.'.'.$pos, array('value'=>$opt));
					}
				}
				else 
					echo $form->hidden('CategoryOption.'.$key, array('value'=>$val));
			}
		}
	}
?>