<?php 
// this should be at the top of every element created with format __ELEMENT_PLUGIN_ELEMENTNAME_instanceNumber.
// it allows a database driven way of configuring elements, and having multiple instances of that configuration.
if(!empty($instance) && defined('__ELEMENT_PRODUCTS_DEALADAY_'.$instance)) {
	extract(unserialize(constant('__ELEMENT_PRODUCTS_DEALADAY_'.$instance)));
} else if (defined('__ELEMENT_PRODUCTS_DEALADAY')) {
	extract(unserialize(__ELEMENT_PRODUCTS_DEALADAY));
}
# setup defaults
$dealItem = $this->requestAction('products/products/deal_a_day');
$gallery = $dealItem;
echo $this->Html->script('/products/js/time.js');

if (!empty($dealItem)) {
	$startDateTime = explode(' ', $dealItem['Product']['started']);
	$endDateTime = explode(' ', $dealItem['Product']['ended']);
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
  <div class="dealItemBrandThumb"> <?php echo $this->element('thumb', array('plugin' => 'galleries', 'model' => 'ProductBrand', 'foreignKey' => $dealItem['ProductBrand']['id'], 'showDefault' => 'false', 'thumbSize' => 'small', 'thumbLink' => "/products/product_brands/view/".$dealItem['ProductBrand']['id'])); ?> </div>
  <h2>
    <?php  echo $dealItem['Product']['name']; __(' by '); echo $this->Html->link($dealItem['ProductBrand']['name'], array('controller' => 'product_brands', 'action' => 'view', $dealItem['ProductBrand']['id'])); ?>
  </h2>
  <div class="dealItemGallery"> <?php echo $this->element('gallery', array('model' => 'Product', 'foreignKey' => $dealItem['Product']['id']), array('plugin' => 'galleries')); ?> </div>
  
  <div id="timer"><h1>Time Left</h1><div id="tzcd"><script>start();</script></div></div>
  <div class="dealItemSummary"> <?php echo $dealItem['Product']['summary']; ?> </div>
  <div class="dealItemDescription"> <?php echo $dealItem['Product']['description']; ?> </div>
  
  
  <div class="actions">
    <div class="dealItemPrice">
      <?php echo __('$ '); echo (!empty($dealItem['ProductPrice'][0]['price']) ? $dealItem['ProductPrice'][0]['price'] : $dealItem['Product']['price']); ?>
    </div>
    
    <?php 
	echo $this->Html->link('Yes! I Want One', array('plugin' => 'products', 'controller' => 'products', 'action' => 'view', $dealItem['Product']['id']), array('id' => 'dealItemViewButton'));
	?>
    </div>
    <?php /*endif;*/ ?>
  </div>
</div>
<?php 
} // end check for dealItem
?>
