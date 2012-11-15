cms.ui.add('chosen', function() {
	var select = $('select').not('.no-script');
	select.chosen();
	select.trigger("liszt:updated");
});