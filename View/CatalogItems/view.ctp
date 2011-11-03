<?php 
# @todo Add the behavior dynamically, and show these links if the behavior is loaded for this view.
# echo $this->Favorites->toggleFavorite('favorite', $catalogItem['CatalogItem']['id']); 
# echo $this->Favorites->toggleFavorite('watch', $catalogItem['CatalogItem']['id']); . 
?>

<div class="catalogItem view">
  <h2>
    <?php  echo $catalogItem['CatalogItem']['name']; echo !empty($catalogItem['CatalogItemBrand']['name']) ? ' by ' . $this->Html->link($catalogItem['CatalogItemBrand']['name'], array('controller' => 'catalog_item_brands', 'action' => 'view', $catalogItem['CatalogItemBrand']['id'])) : ''; ?>
  </h2>
  <div class="catalogItemGallery"> <?php echo $this->element($gallery['Gallery']['type'], array('id' => $gallery['Gallery']['id']), array('plugin' => 'galleries')); ?> </div>
  <?php if (!empty($catalogItem['CatalogItemChildren'][0])) : ?>
  <?php foreach ($catalogItem['CatalogItemChildren'] as $child) : ?>
  <div class="childrenGalleries hide" id="childGallery<?php echo $child['id']; ?>"><?php echo $this->element($child['Gallery']['type'], array('id' => $child['Gallery']['id']), array('plugin' => 'galleries')); ?></div>
  <?php endforeach; ?>
  <?php endif; ?>
  <div class="catalogItemDescription"> <?php echo $catalogItem['CatalogItem']['description']; ?> </div>
  <div class="actions">
    <div class="catalogItemPrice">
      <?php echo __('Price: $'); ?><span id="itemPrice"><?php echo (!empty($catalogItem['CatalogItemPrice'][0]['price']) ? $catalogItem['CatalogItemPrice'][0]['price'] : $catalogItem['CatalogItem']['price']); ?></span>
    </div>
    <div class="action catalogItemCartText">
      <?php if(!$no_stock) : ?>
      <div class="action catalogItemAddCart">
        <?php 
		echo $this->Form->create('OrderItem', array('url' => array('plugin' => 'orders', 'controller'=>'order_items', 'action'=>'add')));
		echo $this->Form->input('OrderItem.quantity' , array('label' => 'Add (Quantity)', 'value' => 1));
		echo $this->Form->hidden('OrderItem.parent_id' , array('value' => $catalogItem['CatalogItem']['id']));
		echo $this->Form->hidden('OrderItem.catalog_item_id' , array('value' => $catalogItem['CatalogItem']['id']));
		echo $this->Form->hidden('OrderItem.price' , array('value' => $catalogItem['CatalogItem']['price']));
		echo $this->Form->hidden('OrderItem.payment_type' , array('value' => $catalogItem['CatalogItem']['payment_type']));
		?>
        <div id="stock"> </div>
        <?php 
	 
		if(isset($options) && !empty($options)) {
			//get group for minimum atributes
			foreach($options as $key => $opt) {
				$count[$key] = count($opt['children']);
			}
			//minimun attribute value
			// geting group key for minimum atributes
			$min_key = array_search(min($count), $count); 
			
			foreach($options as $key => $opt) {
				?>
        <div style ="float:left; width: 200px; clear:none;">
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
					echo $this->Form->input('CategoryOption.'.$opt['CategoryOption']['id'], 
						array('options'=>$sel, 'multiple'=>'checkbox', 'div'=>false, 
								'selected' => $selected, 'class' => 'CatalogAttribute', 'legend' => false,
								//'default' => $default,
								'type'=> $opt['CategoryOption']['type'] == 'Attribute Group' ? 'radio' : 'select'));
				?>
          </fieldset>
        </div>
        <?php
			}
		}
		
		// check for payment types if session has payment type value the show according to selected payment type 
		if(!empty($catalogItem['CatalogItem']['payment_type'])) :
			if($this->Session->check('OrderPaymentType')) :
				$paymentTypes = $this->Session->read('OrderPaymentType');
				$newPaymentTypes = explode(',', $catalogItem['CatalogItem']['payment_type']);
				$commonPaymentType = array_intersect($paymentTypes, $newPaymentTypes);
				if(!empty($commonPaymentType)) :
					echo $this->Form->submit(__('+ Cart' , true), array ('type'=>'submit', 'id'=>'add_button'));
				else :
					echo "Please use the same payment type as previous items. ";
		  			echo $this->Form->submit(__('+ Cart' , true), array ('type'=>'submit', 'id'=>'add_button', 'disabled' => 'disabled'));
				endif;
			else :
				echo $this->Form->submit(__('+ Cart' , true), array ('type'=>'submit', 'id'=>'add_button'));	 
			endif;
		endif;
		
		echo $this->Form->end();
		?>
      </div>
      <?php else:?>
   	  <p>The item is out of stock. Please come back later</p>
  	  <?php endif;?>
    </div>
  </div>
</div>
<script type="text/javascript"><!--

var radioState = Array();
var index = 0;

$.ajax({
    type: "POST",
    data: $('#OrderItemViewForm').serialize(),
	url: "/catalogs/catalog_items/get_attribute_values/co_id:" ,
    dataType: "text",						 
    success:function(data){
	response(data)
    }
});

$('#add_button').click(function (e) {
	// it will check if radio button exists on form and not checked 
	if($('.CatalogAttribute').length != 0 && 
				!$('input[type=radio]:checked', '#OrderItemViewForm').val()) {
		e.preventDefault();
		alert('<?php echo __('Please choose options', true)?>');
	}
});

//on radio selected
$(':radio').click(function () {
	radioState = Array();
	$('#stock').html('');
	if ( $('#' + $(this).attr('id')).attr("checked") == true) {
		// radio state stores the state of the adjacent radio button clicked
		$(this).siblings(':radio').each(function(){
			if(!$(this).attr('disabled')) {
				radioState[index++] = $(this).attr('value');
			}
		});
	}
	
	if ( $('#' + $(this).attr('id')).attr("checked") == true) {
		$.ajax({
	        type: "POST",
	        data: $('#OrderItemViewForm').serialize(),
			url: "/catalogs/catalog_items/get_attribute_values/co_id:" +$(this).attr('value') ,
	        dataType: "text",						 
	        success:function(data){
			response(data)
	        }
	    });
	}
});
function response(data) {
	if (data.length > 0) {
		var response = JSON.parse(data);
		$(':radio').attr('disabled', false);

		// take the options to be shown and disable rest.
		$(':radio').each(function(){

			flag = false;
			for (x in response["CategorizedOption"]) {
				// if id is present in enbaled list response or id is in same group as clicked
				// then enable it
				if (response["CategorizedOption"][x] == $(this).attr('value')  
						|| $.inArray($(this).attr('value'), radioState) >= 0
				){
					flag = true;
					break;
				}
			}
			if (!flag) {
				$(this).attr('checked', false);
				$(this).attr('disabled', true);
			}
			
		});

		if(response["CatalogItem"]) {
			if(response["CatalogItem"]["stock"] != '' && response["CatalogItem"]["stock"] != "0") {
				$('#OrderItemCatalogItemId').val(response["CatalogItem"]["id"]);
				if (response["CatalogItem"]["stock"] < 10) {
					st = '<div> Only ' + response["CatalogItem"]["stock"] + ' left. </div>';
					$("#stock").html(st);
				}
				
				$("#itemPrice").html(response["CatalogItem"]["price"]);
				
				var childItem = response["CatalogItem"]["id"];
				var childGallery = $("#childGallery" + childItem).html();
				if (childGallery) {
					$(".catalogItemGallery").html(childGallery);
				}
			}
		}
	}
	
}
</script>
