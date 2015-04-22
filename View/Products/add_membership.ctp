<?php
/**
 * Products Membership Add View
 *
 * PHP versions 5
 *
 * Zuha(tm) : Business Management Applications (http://zuha.com)
 * Copyright 2009-2012, Zuha Foundation Inc. (http://zuhafoundation.org)
 *
 * Licensed under GPL v3 License
 * Must retain the above copyright notice and release modifications publicly.
 *
 * @copyright     Copyright 2009-2012, Zuha Foundation Inc. (http://zuha.com)
 * @link          http://zuha.com Zuha� Project
 * @package       zuha
 * @subpackage    zuha.app.plugins.products.views
 * @since         Zuha(tm) v 0.0.1
 * @license       GPL v3 License (http://www.gnu.org/licenses/gpl.html) and Future Versions
 */
 ?>

<div class="productAdd form">
    <?php echo $this->Form->create('Product', array('type' => 'file')); ?>
    <div class="row">
    	<div class="span4 col-md-4">
	    	<?php echo $this->Form->input('Product.is_public', array('value' => 1, 'type' => 'hidden')); ?>
			<?php echo $this->Form->input('Product.is_buyable', array('value' => 1, 'type' => 'hidden')); ?>
			<?php echo $this->Form->input('Product.cart_max', array('type' => 'hidden', 'value' => 1)); ?>
			<?php echo $this->Form->input('Product.model', array('type' => 'hidden', 'value' => 'UserRole')); ?>
			<?php echo $this->Form->input('Product.name', array('label' => 'Display Name')); ?>
	        <?php echo CakePlugin::loaded('Media') ? $this->Element('Media.selector', array('multiple' => true)) : null; ?>
			<?php //echo $this->Form->input('Product.price', array('label' => 'Price <small>(Use 0 for a free trial peroids.)</small>', 'type' => 'number', 'step' => '.01', 'min' => '0', 'max' => '99999999999', 'data-mask' => 'X.00')); ?>
	    	<?php 		
			$arbSettingsValues = array(
				array(
					'name' => 'PaymentAmount',
					'label' => 'Cost of Membership* <small>($USD)</small>',
					'data-mask' => '0000000000000.00',
					'data-mask-option' => 'reverse',
					'default' => '1.00',
					'min' => 0,
				),
				array(
					'name' => 'ExecutionFrequencyType',
					'label' => 'Scheduled frequency.', 
					'type' => 'select',
					'options' => array('Daily' => 'Daily', 'Weekly' => 'Weekly', 'BiWeekly' => 'BiWeekly', 'Monthly' => 'Monthly', 'FirstofMonth' => 'First of Month', 'SpecificDayofMonth' => 'Specific day of Month', 'LastofMonth' => 'Last of Month', 'Quarterly' => 'Quarterly', 'SemiAnnually' => 'SemiAnnually', 'Annually' => 'Annually'),
					'default' => 'Monthly',
				),
				array(
					'name' => 'ExecutionFrequencyParameter',
					'class' => 'date-execution',
					'label' => false,
					'empty' => '--Select--',
					'options' => range(0,31),
				),
				array(
					'name' => 'ExecutionFrequencyParameter',
					'class' => 'weekly-execution',
					'label' => false,
					'empty' => '--Select--', 
					'options' => array('Sunday' => 'Sunday', 'Monday' => 'Monday', 'Tuesday' => 'Tuesday', 'Wednesday' => 'Wednesday', 'Thursday' => 'Thursday', 'Friday' => 'Friday', 'Saturday' => 'Saturday'),
				),
				array(
					'name' => 'HasTrialPeriod',
					'label' => 'Trial Period?',
					'type' => 'checkbox',
				),
				array(
					'name' => 'FirstPaymentAmount',
					'class' => 'trial-period',
					'desc' => 'A First Payment of a different dollar amount or off the desired frequency may be set up for Recurring Payment.',
					'data-mask' => '0000000000000.00',
					'min' => 0,
				),
				array(
					'name' => 'StartDate',
					'class' => 'trial-period',
					'label' => 'Trial Period <small>(days)</small><!--small>The number of days after purchase to start the recurring payment.</small-->',
					'type' => 'number',
					'data-mask' => '000',
					'min' => 0,
					'max' => '365',
				),
				// array(
					// 'name' => 'FirstPaymentDate',
					// 'class' => 'trial-period',
					// 'label' => 'Optional first payment date <small>The number of days after purchase that the optional First Payment should process.</small>',
					// 'type' => 'number',
					// 'desc' => '',
					// 'default' => 0
				// ),
				// array(
					// 'name' => 'EndDate',
					// 'label' => 'End date',
					// 'desc' => 'The number of days after purchase to end the Recurring Payment.  If empty, the schedule will run indefinitely.',
				// ),
				array(
					'name' => 'BraintreePlan',
					'label' => 'Brantree Plan ID',
					'type' => 'text'
				)
			);
			
			foreach($arbSettingsValues as $arbSetting) :
				$name = $arbSetting['name']; unset($arbSetting['name']);
				echo $this->Form->input('Product.arb_settings.'.$name, $arbSetting);
			endforeach; ?>
			<fieldset>
		 		<legend class="toggleClick"><?php echo __('Categorize?');?></legend>
				<?php echo $this->Form->input('Category', array('multiple' => 'checkbox', 'label' => 'Which categories? ('.$this->Html->link('add', array('plugin' => 'categories', 'controller' => 'categories', 'action' => 'tree')).' / '.$this->Html->link('edit', array('plugin' => 'categories', 'controller' => 'categories', 'action' => 'tree')).' categoies)')); ?>
			</fieldset>
			
			<?php if(!empty($paymentOptions)) : ?>
		    <fieldset>
		        <legend class="toggleClick"><?php echo __('Select Payment Types For The Item.');?></legend>
		        <?php
		            echo $this->Form->input('Product.payment_type', array('options' => $paymentOptions, 'multiple' => 'checkbox'));
		        ?>
		    </fieldset>
			<?php endif; ?>
		</div>
		<div class="span8 col-md-8">
			<?php echo $this->Form->input('Product.foreign_key', array('label' => 'Upgrade to...', 'after' => ' ' . $this->Html->link('edit roles', array('plugin' => 'users', 'controller' => 'user_roles', 'action' => 'index'), array('class' => 'btn btn-inverse')))); ?>
			<?php echo $this->Form->input('Product.summary', array('type' => 'text', 'label' => 'Promo Text <small>(short lead in blurb)</small>')); ?>
			<?php echo $this->Form->input('Product.description', array('type' => 'richtext', 'label' => 'Sales copy')); ?>
		</div>
    </div>
	
	<?php echo $this->Form->end(array('label' => 'Save Membership Product', 'class' => 'btn btn-success')); ?>

<?php echo $this->Html->script('plugins/jquery.mask.min'); ?>
<script type="text/javascript">
	$('.date-execution').hide();
	$('.weekly-execution').hide();
	$('select#ProductArbSettingsExecutionFrequencyType').change(function() {
		$('.date-execution').hide().val(0);
		$('.weekly-execution').hide().val(0);
		if ($(this).val() == 'SpecificDayofMonth') {
			$('.date-execution').show();
		}
		if ($(this).val() == 'Weekly' || $(this).val() == 'BiWeekly') {
			$('.weekly-execution').show();
		}
	});
	$('.trial-period').parent().hide();
	$('input#ProductArbSettingsHasTrialPeriod').change(function() {
		if ($(this).is(':checked')) {
			$('.trial-period').parent().show();
		} else {
			$('.trial-period').parent().hide().val('');
		}
	});
</script>

<?php
// set the contextual menu items
$this->set('context_menu', array('menus' => array(
	array(
		'heading' => 'Products',
		'items' => array(
			$this->Html->link(__('Dashboard'), array('admin' => true, 'controller' => 'products', 'action' => 'dashboard')),
			$this->Html->link(__('List'), array('controller' => 'products', 'action' => 'index')),
			)
		),
	))); ?>


</div>