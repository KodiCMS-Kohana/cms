$(function() {
	var password_generator_status = function() {
		var checkbox = $('#installPasswordGenerateField');

		if(checkbox.is(':checked')){
			$('#password_form').hide();
		} else {
			$('#password_form').show();
		}
	}
	
	$('#installPasswordGenerateField').change(password_generator_status);
	password_generator_status();
})

