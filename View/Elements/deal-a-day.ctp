<?php 
// this should be at the top of every element created with format __ELEMENT_PLUGIN_ELEMENTNAME_instanceNumber.
// it allows a database driven way of configuring elements, and having multiple instances of that configuration.
if(!empty($instance) && defined('__ELEMENT_CATALOGS_DEALADAY_'.$instance)) {
	extract(unserialize(constant('__ELEMENT_CATALOGS_DEALADAY_'.$instance)));
} else if (defined('__ELEMENT_CATALOGS_DEALADAY')) {
	extract(unserialize(__ELEMENT_CATALOGS_DEALADAY));
}
# setup defaults
$dealItem = $this->requestAction('catalogs/catalog_items/deal_a_day');
$gallery = $dealItem;
echo $this->Html->script('/catalogs/js/time.js');

if (!empty($dealItem)) {
	$startDateTime = explode(' ', $dealItem['CatalogItem']['start_date']);
	$endDateTime = explode(' ', $dealItem['CatalogItem']['end_date']);
	#$starttime		=	explode(":", $startDateTime[1]);
	$endtime		=	explode(":", $endDateTime[1]);
	$stardate		=	explode("-", $startDateTime[0]);
	$enddate		=	explode("-", $endDateTime[0]);
	$curdate		=	date('Y-m-d h:i:s');
	$starttime		=	explode(" ",$curdate);
	$curtime		=	explode(":",$starttime[1]);
	$curdate		=	explode("-",$starttime[0]);
	$date_diff		=  ((mktime($endtime[0],$endtime[1],$endtime[2],$enddate[1],$enddate[2],$enddate[0]))-(mktime($curtime[0],$curtime[1],$curtime[2],$curdate[1],$curdate[2],$curdate[0])));
?>
	<script type="text/javascript" language="javascript">
		var todate_extra='';
		var spannumber='';
		var month = '';
		var day = '';
		var hour = '';
		var minute = '';
		var tz = -5;	
		var lab = 'tzcd'+spannumber;
		function start(){	
			displayTZCountDown('<?php echo $date_diff?>', lab);
		}
	</script>


<div class="dealItem view">
  <div class="dealItemBrandThumb"> <?php echo $this->element('thumb', array('plugin' => 'galleries', 'model' => 'CatalogItemBrand', 'foreignKey' => $dealItem['CatalogItemBrand']['id'], 'showDefault' => 'false', 'thumbSize' => 'small', 'thumbLink' => "/catalogs/catalog_item_brands/view/".$dealItem['CatalogItemBrand']['id'])); ?> </div>
  <h2>
    <?php  echo $dealItem['CatalogItem']['name']; __(' by '); echo $this->Html->link($dealItem['CatalogItemBrand']['name'], array('controller' => 'catalog_item_brands', 'action' => 'view', $dealItem['CatalogItemBrand']['id'])); ?>
  </h2>
  <div class="dealItemGallery"> <?php echo $this->element('gallery', array('model' => 'CatalogItem', 'foreignKey' => $dealItem['CatalogItem']['id']), array('plugin' => 'galleries')); ?> </div>
  
  <div id="timer"><h1>Time Left</h1><div id="tzcd"><script>start();</script></div></div>
  <div class="dealItemSummary"> <?php echo $dealItem['CatalogItem']['summary']; ?> </div>
  <div class="dealItemDescription"> <?php echo $dealItem['CatalogItem']['description']; ?> </div>
  
  
  <div class="actions">
    <div class="dealItemPrice">
      <?php echo __('$ '); echo (!empty($dealItem['CatalogItemPrice'][0]['price']) ? $dealItem['CatalogItemPrice'][0]['price'] : $dealItem['CatalogItem']['price']); ?>
    </div>
    
    <div class="action dealItemCartText">
      <?php /*if(!$no_stock): ?>
      <?php if($stockAmount != 0): ?>
      <?php if($itemInCart): printf("You have %d %s in your cart" , $itemInCart , $dealItem["CatalogItem"]["name"]); endif; ?>
      <?php echo $this->Form->create('OrderItem' , array('url' => array('plugin' => 'orders', 'controller'=>'order_items' , 'action' => 'add')));?> <?php echo $this->Form->input('OrderItem.quantity' , array('label' => 'Add (Quantity)', 'value' => 1))?> <?php echo $this->Form->input('OrderItem.status' , array('type'=>'hidden' , 'value'=>'incart'))?> <?php echo $this->Form->input('OrderItem.catalog_item_id' , array('type'=>'hidden' , 'value'=>$dealItem["CatalogItem"]["id"]))?> <?php echo $this->Form->end(__('Add to Cart' , true)); */ ?>
      <?php /* else: ?>
      <p>The item is out of stock. Please come back later</p>
      <?php endif; ?>
      <?php else:?>
      <?php if($itemInCart):?>
      <?php echo $this->Html->link(__($this->Html->tag('span', 'view cart', array('class' => 'button')), true), array('plugin' => 'orders', 'controller'=>'order_items' , 'action'=>'cart'), array('id' => 'viewCart', 'class' => 'button', 'escape' => false));?> <?php printf("You have %d of %s in your cart" , $itemInCart , $dealItem["CatalogItem"]["name"])?>
      <?php else: ?>
      <?php echo __("This item is not in your cart."); ?>
      <?php endif; */ ?>
    </div>
    <div class="action dealItemAddCart">
    <?php /*
	echo $this->Form->create('OrderItem', array('url' => array('plugin' => 'orders', 'controller'=>'order_items', 'action'=>'add')));
	echo $this->Form->input('OrderItem.quantity' , array('type' => 'hidden', 'label' => 'Add (Quantity)', 'value' => 1)); 
	echo $this->Form->hidden('OrderItem.status' , array('value'=>'incart'));
	echo $this->Form->hidden('OrderItem.catalog_item_id' , array('value' => $dealItem['CatalogItem']['id']));
	echo $this->Form->hidden('OrderItem.name' , array('value' => $dealItem['CatalogItem']['name']));
	echo $this->Form->hidden('OrderItem.length' , array('value' => $dealItem['CatalogItem']['length']));
	echo $this->Form->hidden('OrderItem.width' , array('value' => $dealItem['CatalogItem']['width']));
	echo $this->Form->hidden('OrderItem.height' , array('value' => $dealItem['CatalogItem']['height']));
	echo $this->Form->hidden('OrderItem.weight' , array('value' => $dealItem['CatalogItem']['weight']));
	echo $this->Form->hidden('OrderItem.assignee_id' , array('value' => $this->Session->read('Auth.User.id')));

	echo $this->Form->hidden('OrderItem.model' , array('value' => $dealItem['CatalogItem']['model']));
	echo $this->Form->hidden('OrderItem.foreign_key' , array('value' => $dealItem['CatalogItem']['foreign_key']));
	echo $this->Form->end(__('Add to Cart' , true));
	*/ ?>
    <?php 
	echo $this->Html->link('Yes! I Want One', array('plugin' => 'catalogs', 'controller' => 'catalog_items', 'action' => 'view', $dealItem['CatalogItem']['id']), array('id' => 'dealItemViewButton'));
	?>
    </div>
    <?php /*endif;*/ ?>
  </div>
</div>
<?php 
} // end check for dealItem
?>
