cms.init.add('email_settings', function () {
    
    var driver = $('#smtp_driver').on('change', function() {
        change_smtp_driver($(this).val());
    })
    change_smtp_driver(driver.val());
});

function change_smtp_driver(driver) {
    $('fieldset').attr('disabled', 'disabled').hide();
    
    $('fieldset#' + driver + '-driver-settings').removeAttr('disabled').show();
}