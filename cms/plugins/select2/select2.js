cms.ui.add('select2', function() {
	var select = $('select').not('.no-script');
	select.select2();
	select.trigger("liszt:updated");
});