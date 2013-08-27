<div id="viewProperty">
	<div class="row-fluid">
		<div class="span4 border_set" id="propAddress">
			<div class="row-fluid">
				<div class="span12">
					<div class="head_bar">
						<h4 class="text-center">Property address</h4>
					</div>
				</div>
			</div>
			<div class="row-fluid top_space" id="housePrice">
				<div class="span12 text-center">
					<span>Offered At</span>
					<p>$$$$</p>
				</div>
			</div>
			<div class="row-fluid top_space" id="houseName">
				<div class="span12 text-center">
					<span>Name/style of house</span>
				</div>
			</div>
			<div class="row-fluid" id="houseInfo">
				<div class="span12 text-center">
					<p>Bedrooms</p>
					<p>Bathrooms</p>
					<p>Sq. Footage</p>
					<p>Acres</p>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span12 text-center">
					<p><img src="/theme/default/upload/1/img/calicon.jpg" alt="Open house button" />Property Status info/update</p>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span12 text-center">
					<p><button class="btn btn-medium same_width red_button">Download Brochure</button></p>
					<p><button class="btn btn-medium same_width red_button">Download MLS Detail</button></p>
				</div>
			</div>
		</div>
		<div class="span4 border_set" id="propDescrip">
			<div class="row-fluid">
				<div class="span12">
					<div class="head_bar">
						<h4>Key Features</h4>
					</div>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span12" id="propFeatures">
					<ul>
						<li><span>Item</span></li>
						<li><span>Item</span></li>
						<li><span>Item</span></li>
						<li><span>Item</span></li>
						<li><span>Item</span></li>
					</ul>
				</div>
			</div>
		</div>
		<div class="span4 border_set" id="propContact">
			<div class="row-fluid">
				<div class="span12">
					<div class="head_bar">
						<h5>Location Map</h5>
					</div>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<div>
						<img src="http://placehold.it/200&text=Map" alt="map" />
					</div>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<div class="head_bar">
						<h5 class="text-center">Like this Listing? Contact Us</h5>
					</div>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span12" id="contactPad">
					<form class="form-search text-center" id="contactForm">
						<input type="text" class="span12 search-query" placeholder="Please Enter Your Full Name">
						<input type="text" class="span12 search-query" placeholder="Please Enter Your Phone Number">
						<input type="text" class="span12 search-query" placeholder="Please Enter Your Email">
						<input type="text" class="span12 search-query" placeholder="Human Test, Please Enter 10">
						<button type="submit" class="btn blue_button">Contact Us</button>
					</form>
				</div>
			</div>
		</div>
	</div>		
</div>
<style>
	#viewProperty li{
		color:#7D2010;
	}
	#viewProperty li > span{
		color:#000000;
	}
	#viewProperty .border_set{
		border:thin solid #d3d3d3;
		/*box-shadow:0 1em 1em #000000;*/	
	}
	#viewProperty .head_bar{
		background-color:#EAEAEA;
		padding:2%;	
	}
	#viewProperty .top_space{
		padding-top:3%;
	}
	#viewProperty .same_width{
		width:60%;
	}
	#viewProperty .search-query{
		margin:2% 0;
	}
	#viewProperty .blue_button{
		margin-top:2%;	
	}
	#viewProperty .red_button {
	    
	}
	#viewProperty #houseName{
		border-top:thin solid #D3D3D3;
		border-bottom:thin solid #d3d3d3;
	}
	#viewProperty #houseInfo{
		margin:2% 0;
		padding:2%;
	}
	#viewProperty #houseDescrip{
		padding: 2%;
	}
	#viewProperty #contactPad{
		margin-top:2%;
		padding:2%;
	}
	#viewProperty #propFeatures{
		margin-top:4%;
	}
</style>
<script>
	function equalHeight(group) {
		var tallest = 0;
		group.each(function() {
			var thisHeight = $(this).height();
			if(thisHeight > tallest) {
				tallest = thisHeight;
			}
		});
		group.height(tallest);
	}
	
	$(document).ready(function() {
		equalHeight($(".border_set"));
	});
</script>
<!--<?php echo $product['Product']['description']; ?>-->


<?php

echo $this->Element('mapped', array('locations' => $products, 'mapHeight' => '400px', 'mapZoom' => 11, 'autoZoomMultiple' => true), array('plugin' => 'maps')); 

if (!empty($product['Children'])) {
    echo __('<div class="children">');
    foreach ($product['Children'] as $child) {
        echo $this->Element('product', array('product' => array('Product' => $child, 'Gallery' => $child['Gallery'])), array('plugin' => 'products'));
    }
    echo __('</div>');
} else {
    echo $this->Element('product', array('product' => $product), array('plugin' => 'products'));
}
?>

<script type="text/javascript">
    $(function() {
        $('.children .product.view').hide();
        $('.children .product.view:first-child').show();
        $('.ProductSelectId').removeAttr('disabled');
        $('.ProductSelectId').change(function() {
            $('.children .product.view').hide();
            $('#ProductSelectId' + $(this).val()).val($(this).val());
            $('#product' + $(this).val()).show();
        });
    });
</script>

<?php
// set contextual search options
$this->set('forms_search', array(
    'url' => '/products/products/index/', 
	'inputs' => array(
		array(
			'name' => 'contains:name', 
			'options' => array(
				'label' => '', 
				'placeholder' => 'Product Search',
				'value' => !empty($this->request->params['named']['contains']) ? substr($this->request->params['named']['contains'], strpos($this->request->params['named']['contains'], ':') + 1) : null,
				)
			),
		)
	));
// set the contextual menu items
$this->set('context_menu', array('menus' => array(
    array(
		'heading' => 'Products',
		'items' => array(
			$this->Html->link(__('Dashboard'), array('admin' => true, 'controller' => 'products', 'action' => 'dashboard')),
			$this->Html->link(__('Cart'), array('plugin' => 'transactions', 'controller' => 'transactions', 'action' => 'cart')),
			)
		),
	array(
		'heading' => 'Product',
		'items' => array(
			$this->Html->link(__d('products', 'List'), array('action' => 'index')),
			$this->Html->link(__d('products', 'Edit'), array('action' => 'edit', $product['Product']['id'])),
			$this->Html->link(__d('products', 'Delete'), array('action' => 'delete', $product['Product']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $product['Product']['id'])),
			),
		),
	))); ?>
