// @todo 	This js needs to be moved to webroot and delete this vendors directory.

function stock_form(){
	if($('#CatalogItemStock').val() == 1){
		$('#stock_amount').html('<div class="input text"><label for="TransactionAmount">Amount</label><input name="data[TransactionItem][amount]" type="text" maxlength="11" value="" id="TransactionAmount" /></div>');
	}else{
		$('#stock_amount').html('');
	}
}