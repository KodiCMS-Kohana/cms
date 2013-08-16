cms.init.add(['roles_edit', 'roles_add'], function () {
	$('input[name=check_all]').on('change', function() {
		var $list = $(this)
			.parent()
			.parent()
			.parent()
			.parent()
			.find('tbody input');

		if(!$(this).is(':checked')) {
			$list.uncheck();
		} else {
			$list.check();
		}
	});
});