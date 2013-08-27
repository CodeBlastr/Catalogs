<div class="row-fluid">
	<div class="pull-left span4 bs-docs-sidebar" id="productNav">
	    <ul class="nav-list bs-docs-sidenav">
	        <li><a class="tab-trigger" data-target="#productDetails"><i class="icon-chevron-right"></i> Property Information</a></li>
	        <li><a class="tab-trigger" data-target="#optionalDetails"><i class="icon-chevron-right"></i> Optional Details</a></li>
	        <li><a class="tab-trigger" data-target="#productImages"><i class="icon-chevron-right"></i> Property Images</a></li>
	        <li><a class="tab-trigger" data-target="#productCategorization"><i class="icon-chevron-right"></i> Categorization</a></li>
	    </ul>
	</div>
	
	<div class="products productAdd form pull-right span8" data-spy="scroll" data-target="#productNav">
		<?php echo $this->Form->create('Product', array('type' => 'file')); ?>
	    <fieldset id="productDetails">
	        <legend class="sectionTitle"><?php echo __d('products', 'Property Information'); ?></legend>
	    	<?php
			echo $this->Form->input('Product.id');
			echo $this->Form->input('Product.name', array('label' => 'Display Name'));
	        echo $this->Form->input('Product.price', array('label' => 'Retail Price <small><em>(ex. 0000.00)</em></small>', 'step' => '0.01', 'min' => '0', 'max' => '99999999999'));
			echo $this->Form->input('Product.description', array('type' => 'richtext', 'label' => 'What is the sales copy for this item?')); 
			echo $this->Form->input('Product.Meta.street');
			echo $this->Form->input('Product.Meta.bedroom');
			echo $this->Form->input('Product.Meta.bathroom');
			echo $this->Form->input('Product.Meta.footage');
			echo $this->Form->input('Product.Meta.acreage');?>
	    </fieldset>
	    <fieldset id="optionalDetails">
	        <legend class="toggleClick"><?php echo __d('products', 'Optional property details'); ?></legend>
	        <?php
	    	echo $this->Form->input('Product.sku', array('label' => 'MLS Number'));
	        echo $this->Form->input('Product.summary', array('type' => 'text', 'label' => 'Promo Text <br /><small><em>Short blurb of text to entice people to view more about this property.</em></small>'));
			echo $this->Form->input('Product.is_public', array('default' => 1, 'label' => 'Published'));
	        echo $this->Form->input('Product.is_buyable', array('default' => 1, 'label' => 'Can you buy this property online <small><em>(not typical)</em></small>')); ?>
	    </fieldset>
	    <fieldset id="productImages">
	        <legend class="toggleClick"><?php echo __d('products', 'Property images'); ?></legend>
	        <?php
	        echo $this->Html->link('Edit Images', array('admin' => 1, 'plugin' => 'galleries', 'controller' => 'galleries', 'action' => 'edit', 'Product', $this->request->data['Product']['id']));    
	        echo $this->Element('gallery', array('model' => 'Product', 'foreignKey' => $this->request->data['Product']['id']), array('plugin' => 'galleries')); ?>
	        </fieldset>
	    
	    <?php if (empty($this->request->data['Product']['parent_id']) && in_array('Categories', CakePlugin::loaded())) { ?>
		<fieldset id="productCategorization">
	 		<legend class="toggleClick"><?php echo __d('products', 'Property categories');?></legend>
			<?php echo $this->Form->input('Category', array('multiple' => 'checkbox', 'selected' => $selectedCategories, 'label' => __('Choose categories (%s)', $this->Html->link('manage categories', array('admin' => 1, 'plugin' => 'products', 'controller' => 'products', 'action' => 'categories'))))); ?>
		</fieldset>
	    <?php } ?>
		
		<?php if(!empty($paymentOptions)) { ?>
	    <fieldset>
	        <legend class="toggleClick"><?php echo __d('products', 'Select Payment Types For The Item.');?></legend>
	        <?php
	            echo $this->Form->input('Product.payment_type', array('options' => $paymentOptions, 'multiple' => 'checkbox'));
	        ?>
	    </fieldset>
		<?php } 
	    echo $this->Form->submit('Save & Continue', array('name' => 'SaveAndContinue', 'class' => 'btn pull-right'));
		
	    echo $this->Form->end('Save'); ?>
	</div>
</div>

<script type="text/javascript">
$(function() {
    $(document).ready( function(){
        // animation 
        var offset = $('#productNav').offset().top;
        $('#productNav a.tab-trigger').click(function(e) {
            e.preventDefault();
            var wait = $('legend.toggleClick').siblings().length;
            var i = 1;
            var me = $(this);
            $('legend.toggleClick').siblings().hide('fast', function() {
                if(i == wait) {
                    var navTo = $(me.attr('data-target')).offset().top;
                    $('#productNav a').parent().removeClass('active');
                    me.parent().addClass('active');
                    $('html, body').animate({ scrollTop: navTo - offset }, 800, function() {
                        $('legend.toggleClick', me.attr('data-target')).siblings().show('slow');
                    });
                }
                ++i;
            });
        });
        // side nav clean up
        $('#productNav a[data-target="#productDetails"]').parent().addClass('active');
        $('body').css('padding-bottom', '800px');

        var shipTypeValue = null;
        $('input.shipping_type').click(function(e){
            shipTypeValue = ($('input.shipping_type:checked').val());
            if(shipTypeValue == 'FIXEDSHIPPING') {
                $('#ProductShippingCharge').parent().show();
            } else {
                $('#ProductShippingCharge').parent().hide();
            }

        });

        $('.cancelOptionType').parent().hide();
        $('.cancelOptionType').parent().next().hide();
        $('.newOptionType, .cancelOptionType').click(function(e){
            e.preventDefault();
            $(this).parent().hide();
            $(this).parent().next().hide();
            $($(this).attr('data-target')).parent().parent().show();
            $($(this).attr('data-target')).parent().parent().prev().show();
        });
    });
})

</script>



<?php
// set the contextual menu items
$this->set('context_menu', array('menus' => array(
    array(
		'heading' => 'Products',
		'items' => array(
			$this->Html->link(__('Dashboard'), array('admin' => true, 'controller' => 'products', 'action' => 'dashboard')),
			)
		),
    array(
    	'heading' => 'Products',
		'items' => array(
			$this->Html->link(__('List'), array('controller' => 'products', 'action' => 'index')),
            $this->Html->link(__('View'), array('controller' => 'products', 'action' => 'view', $this->request->data['Product']['id'])),
			$this->Html->link(__('Delete'), array('controller' => 'products', 'action' => 'delete', $this->request->data['Product']['id']), array(), 'Are you sure? (cannot be undone)'),
    		)
		),
	))); ?>