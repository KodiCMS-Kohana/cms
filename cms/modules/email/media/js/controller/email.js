cms.init.add('email_settings', function () {
    
    var driver = $('#email_driver');
			
	$('.widget').on('change', '#email_driver', function() {
        change_email_driver($(this).val());
    });
    change_email_driver(driver.val());
});

function change_email_driver(driver) {
    $('fieldset').attr('disabled', 'disabled').hide();
    
    $('fieldset#' + driver + '-driver-settings').removeAttr('disabled').show();
}