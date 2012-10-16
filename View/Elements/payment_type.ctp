<?php
    // check for payment types if session has payment type value the show according to selected payment type 
		if(!empty($catalogItemPaymentType)) :
			if($this->Session->check('TransactionPaymentType')) :
				$paymentTypes = $this->Session->read('TransactionPaymentType');
				$newPaymentTypes = explode(',', $catalogItemPaymentType);
				$commonPaymentType = array_intersect($paymentTypes, $newPaymentTypes);
				if(!empty($commonPaymentType)) :
					echo $this->Form->submit(__('+ Cart' , true), array ('type' => 'submit', 'id'=>'add_button'));
				else :
					echo "Please use the same payment type as previous items. ";
		  			echo $this->Form->submit(__('+ Cart' , true), array ('type' => 'submit', 'id'=>'add_button', 'disabled' => 'disabled'));
				endif;
			else :
				echo $this->Form->submit(__('+ Cart' , true), array ('type' => 'submit', 'id'=>'add_button'));	 
			endif;
		else :
			echo $this->Form->submit(__('+ Cart' , true), array ('type' => 'submit', 'id'=>'add_button'));
		endif;