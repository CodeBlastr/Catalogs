<?php 
if(isset($options)) {
	foreach($options as $key => $opt) {
		$sel = array();
		foreach($opt['children'] as $child) {
			$sel[$child['CategoryOption']['id']] = $child['CategoryOption']['name'];
		}
		if (!empty($sel)) {
			echo $this->Form->input('CategoryOption.'.$opt['CategoryOption']['id'], array(
				'options' => $sel, 
				'multiple' => 'checkbox',
				'legend' => $opt['CategoryOption']['name'],
				'type' => $opt['CategoryOption']['type'] == 'Attribute Group' ? 'radio' : 'select'));
		}
	}
	echo $this->Form->input('CatalogItem.stock_item', array('label' => 'How many of the selected attribute type do you have in stock?', 'value' => 999999));
}