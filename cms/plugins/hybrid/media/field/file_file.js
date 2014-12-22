$(function() {
	$('.remove-file-checkbox').on('change', function () {
		var $self = $(this);
		var $cont = $self.closest('.file-container');

		if ($(this).is(':checked')) {
			$('.upload-new-cont input', $cont).attr('disabled', 'disabled');
			$('.panel-toggler', $cont).hide();
			$('.panel-spoiler', $cont).show();
			$('.thumbnail', $cont).hide();
		} else {
			$('.panel-toggler', $cont).show();
			$('.upload-new-cont input', $cont).removeAttr('disabled');
			$('.panel-spoiler', $cont).hide();
			$('.thumbnail', $cont).show();
		}
	});
});