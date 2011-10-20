<?php
	$index = 0;
	// count check is for making sure that there are other entries as well. 
	if($this->request->data && count($this->request->data) > 1) {
		echo $this->Form->hidden('CatalogItem.id', array('value'=>$this->request->data['CatalogItem']['id']));
		echo $this->Form->hidden('CatalogItem.sku',array('value'=>$this->request->data['CatalogItem']['sku']));
		echo $this->Form->hidden('CatalogItem.user_role_id',array('value'=>$this->request->data['CatalogItem']['user_role_id']));
		
		// might need to update this default thing later to an actual default group
		echo $this->Form->hidden('CatalogItem.catalog_item_brand_id',array('value'=>$this->request->data['CatalogItem']['catalog_item_brand_id']));
		echo $this->Form->hidden('CatalogItem.name',array('value'=>$this->request->data['CatalogItem']['name']));
		// echo $this->Form->hidden('GalleryImage.filename', array('type' => 'file'));
	    echo $this->Form->input('GalleryImage.dir', array('type' => 'hidden'));
	    echo $this->Form->input('GalleryImage.mimetype', array('type' => 'hidden'));
	    echo $this->Form->input('GalleryImage.filesize', array('type' => 'hidden'));
		
		echo $this->Form->hidden('CatalogItem.description',array('value'=>$this->request->data['CatalogItem']['description']));
		echo $this->Form->hidden('CatalogItem.published',array('value'=>$this->request->data['CatalogItem']['published']));
		echo $this->Form->hidden('CatalogItem.catalog_id',array('value'=>$this->request->data['CatalogItem']['catalog_id']));
		
		foreach($this->request->data['Category'] as $val) {
			echo $this->Form->hidden('Category.'.$index++, array('value'=>$val));
		}
		if(!empty($this->request->data['CategoryOption'])) {
			foreach($this->request->data['CategoryOption'] as $key => $val) {
				echo $this->Form->hidden('CategoryOption.'.$key, array('value'=>$val));
				if (is_array($val)) {
					foreach($val as $pos => $opt) {
						echo $this->Form->hidden('CategoryOption.'.$key.'.'.$pos, array('value'=>$opt));
					}
				}
				else 
					echo $this->Form->hidden('CategoryOption.'.$key, array('value'=>$val));
			}
		}
	}
?>