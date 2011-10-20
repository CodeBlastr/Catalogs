// @todo 	This js needs to be moved to webroot and delete this vendors directory.

function stock_form(){
	if($('#CatalogItemStock').val() == 1){
		$('#stock_amount').html('<div class="input text"><label for="OrderAmount">Amount</label><input name="data[OrderItem][amount]" type="text" maxlength="11" value="" id="OrderAmount" /></div>');
	}else{
		$('#stock_amount').html('');
	}
}