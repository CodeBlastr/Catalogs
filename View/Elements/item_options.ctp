<?php
if(isset($options) && !empty($options)) {
	echo '<div id="stock"> </div>';
	# get group for minimum atributes
	foreach($options as $key => $opt) {
		$count[$key] = count($opt['children']);
	}
	# minimun attribute value
	# geting group key for minimum atributes
	$min_key = array_search(min($count), $count);

	foreach($options as $key => $opt) { ?>
      <div class="catalogItemOptions">
        <fieldset>
          <legend><?php echo $opt['CategoryOption']['name']; ?></legend>
          <?php
		$sel = array();
		$selected = array();
		if($key == $min_key) {
			foreach($opt['children'] as $child) {
				$sel[$child['CategoryOption']['id']] = $child['CategoryOption']['name'];
				//$default = $child['CategoryOption']['id'] ;
			}
		} else {
			foreach($opt['children'] as $child) {
				$sel[$child['CategoryOption']['id']] = $child['CategoryOption']['name'];
			}
		}

		if (!empty($sel))
			echo $this->Form->input('CategoryOption.'.$opt['CategoryOption']['id'], array(
				'options' => $sel,
				'multiple' => 'checkbox',
				'div' => false,
				'selected' => $selected,
				'class' => 'CatalogAttribute',
				'legend' => false,
				//'default' => $default,
				'type'=> $opt['CategoryOption']['type'] == 'Attribute Group' ? 'radio' : 'select'
				)); ?>
        </fieldset>
      </div><!-- end .catalogItemOptions -->
      <?php
	} // end foreach options
    echo $this->Form->button('Reset the Options', array('type'=>'button', 'id'=>'resetOptions'));
	echo $this->Html->script('/catalogs/js/options');
} // endif options